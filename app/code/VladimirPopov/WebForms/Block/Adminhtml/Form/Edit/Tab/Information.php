<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class Information extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Form */
        $model = $this->_coreRegistry->registry('webforms_form');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('VladimirPopov_WebForms::manage_forms')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('form_');
        $form->setFieldNameSuffix('form');


        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );
        $form->setDataObject($model);

        if ($model->getId())
            $form->addField('id', 'hidden', ['name' => 'id']);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Form Information')]);


        $fieldset->addField('name', 'text', array(
            'label' => __('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name'
        ));

        $fieldset->addField('code', 'text', array(
            'label' => __('Code'),
            'name' => 'code',
            'note' => __('Code is used to help identify this web-form in scripts'),
        ));

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField(
            'description',
            'editor',
            [
                'label' => __('Description'),
                'name' => 'description',
                'style' => 'height:20em;',
                'note' => __('This text will appear under the form name'),
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField(
            'success_text',
            'editor',
            [
                'label' => __('Success text'),
                'name' => 'success_text',
                'style' => 'height:20em;',
                'note' => __('This text will be displayed after the form completion'),
                'config' => $wysiwygConfig
            ]
        );

        $fieldset->addField('submit_button_text', 'text', array(
            'label' => __('Submit button text'),
            'name' => 'submit_button_text',
            'note' => __('Set text for the submit button of the form. If empty the default value &quot;Submit&quot; will be used'),
        ));

        $fieldset->addField('menu', 'select', array(
            'label' => __('Display form in admin menu'),
            'title' => __('Display form in admin menu'),
            'name' => 'menu',
            'note' => __('Show web-form in admin backend menu under Web-forms section'),
            'options' => ['1' => __('Yes'), '0' => __('No')]
        ));

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Form Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => $model->getAvailableStatuses(),
                'disabled' => $isElementDisabled
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $this->_eventManager->dispatch('adminhtml_webforms_form_edit_tab_information_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}