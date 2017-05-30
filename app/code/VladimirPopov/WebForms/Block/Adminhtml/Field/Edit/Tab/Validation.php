<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Field\Edit\Tab;

class Validation extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Validation');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Validation');
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

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \VladimirPopov\WebForms\Model\Field */
        $model = $this->_coreRegistry->registry('webforms_field');

        /* @var $model \VladimirPopov\WebForms\Model\Form */
        $modelForm = $this->_coreRegistry->registry('webforms_form');

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

        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );
        $form->setDataObject($model);

        $form->setHtmlIdPrefix('field_');
        $form->setFieldNameSuffix('field');

        $fieldset = $form->addFieldset('webforms_form',array(
            'legend' => __('Validation')
        ));


        $fieldset->addField('validate_length_min','text',array(
            'label' => __('Minimum length'),
            'class' => 'validate-number',
            'name' => 'validate_length_min',
        ));

        $fieldset->addField('validate_length_max','text',array(
            'label' => __('Maximum length'),
            'class' => 'validate-number',
            'name' => 'validate_length_max',
        ));

        $fieldset->addField('validate_regex','text',array(
            'label' => __('Validation RegEx'),
            'name' => 'validate_regex',
            'note' => __('Validate with custom regular expression')
        ));

        $fieldset->addField('validate_message','textarea',array(
            'label' => __('Validation error message'),
            'name' => 'validate_message',
            'note' => __('Displayed error message text if regex validation fails')
        ));

        if($model->getData('validate_length_min') == 0){
            $model->setData('validate_length_min','');
        }

        if($model->getData('validate_length_max') == 0){
            $model->setData('validate_length_max','');
        }

        $this->_eventManager->dispatch('adminhtml_webforms_field_edit_tab_validation_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}