<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Field\Edit\Tab;

class Design extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Design');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Design');
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

        $fieldset = $form->addFieldset('webforms_form', array(
            'legend' => __('Design')
        ));


//        $fieldset->addField('size', 'select', array(
//            'label' => __('Size'),
//            'name' => 'size',
//            'values' => $model->getSizeTypes(),
//            'note' => __('Standard - two neighbour fields will be merged in one row<br>Wide - field will be wide and single in a row')
//        ));

        $fieldset->addField('css_class_container', 'text', array(
            'label' => __('CSS classes for the Container element'),
            'name' => 'css_class_container',
            'note' => __('Set CSS classes for the container element that holds Label and Input elements')
        ));

        $fieldset->addField('css_class', 'text', array(
            'label' => __('CSS classes for the Input element'),
            'name' => 'css_class',
            'note' => __('You can use it for additional field validation (see Prototype validation classes)')
        ));

        $fieldset->addField('css_style', 'text', array(
            'label' => __('Additional CSS style for the input element'),
            'name' => 'css_style',
            'note' => __('Add custom stylization to the input element')
        ));

        $fieldset = $form->addFieldset('field_result', array(
            'legend' => __('Results / Notifications')
        ));

        $fieldset->addField('result_display', 'select', array(
            'label' => __('Display field'),
            'title' => __('Display field'),
            'name' => 'result_display',
            'note' => __('Display field in result / notification messages'),
            'values' => $model->getDisplayOptions(),
        ));

        $this->_eventManager->dispatch('adminhtml_webforms_field_edit_tab_design_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}