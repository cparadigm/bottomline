<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Fieldset\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_displayConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \VladimirPopov\WebForms\Model\Config\Fieldset\Display $displayConfig,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_displayConfig = $displayConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('fieldset_form');
        $this->setTitle(__('Fieldset Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('webforms_fieldset');
        $modelForm = $this->_coreRegistry->registry('webforms_form');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getUrl('*/*/save',['store' => $this->getRequest()->getParam('store')]), 'method' => 'post']]
        );

        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );
        $form->setDataObject($model);

        $form->setHtmlIdPrefix('fieldset_');
        $form->setFieldNameSuffix('fieldset');

        if ($model->getId())
            $form->addField('id', 'hidden', array(
                'name' => 'id',
            ));

        $form->addField('webform_id', 'hidden', array(
            'name' => 'webform_id',
        ));

        $fieldset = $form->addFieldset('fieldset_information', array(
            'legend' => __('Fieldset Information')
        ));

        $fieldset->addField('name', 'text', array(
            'label' => __('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name'
        ));

        $fieldset->addField('position', 'text', array(
            'label' => __('Position'),
            'required' => true,
            'name' => 'position',
            'note' => __('Fieldset position in the form'),
        ));

        $fieldset->addField('css_class', 'text', array(
            'label' => __('Custom CSS classes'),
            'name' => 'css_class',
            'note' => __('Add custom CSS classes to the fieldset container'),
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => __('Status'),
            'title' => __('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => $model->getAvailableStatuses(),
        ));


        $fieldset = $form->addFieldset('fieldset_result', array(
            'legend' => __('Results / Notifications Settings')
        ));

        $fieldset->addField('result_display', 'select', array(
            'label' => __('Display fieldset name in results overview and notifications'),
            'title' => __('Display fieldset name in results overview and notifications'),
            'name' => 'result_display',
            'note' => __('Display fieldset name in result / notification messages'),
            'values' => $this->_displayConfig->toOptionArray(),
        ));

        if (!$model->getId()) {
            $model->setData('is_active', '0');
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}