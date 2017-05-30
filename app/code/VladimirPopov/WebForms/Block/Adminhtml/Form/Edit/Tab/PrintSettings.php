<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class PrintSettings extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Print Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Print Settings');
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
        /* @var $model \Magento\Cms\Model\Form */
        $model = $this->_coreRegistry->registry('webforms_form');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Vladimipopov_WebForms::form_save')) {
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

        $form->setHtmlIdPrefix('form_');
        $form->setFieldNameSuffix('form');


        $fieldset = $form->addFieldset('webforms_print', array(
            'legend' => __('Admin Print Settings'),
        ));
        if (!@class_exists('mPDF')) {
            $fieldset->setComment(__('Printing is disabled. Please install mPDF library. Run command: composer require mpdf/mpdf'));
        }
        
        $fieldset->addField('print_template_id', 'select', array(
            'label' => __('Admin print template'),
            'name' => 'print_template_id',
            'note' => __('Select template for printable version of submission results for admin'),
            'values' => $model->getTemplateOptions(),
        ));

        $fieldset->addField('print_attach_to_email', 'select', array(
            'label' => __('Attach PDF to admin email'),
            'name' => 'print_attach_to_email',
            'note' => __('Attach printable version of the result to email'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset = $form->addFieldset('webforms_print_customer', array(
            'legend' => __('Customer Print Settings')
        ));

        $fieldset->addField('customer_print_template_id', 'select', array(
            'label' => __('Customer print template'),
            'name' => 'customer_print_template_id',
            'note' => __('Select template for printable version of submission results for customer'),
            'values' => $model->getTemplateOptions(),
        ));

        $fieldset->addField('customer_print_attach_to_email', 'select', array(
            'label' => __('Attach PDF to customer email'),
            'name' => 'customer_print_attach_to_email',
            'note' => __('Attach printable version of the result to customer email'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset = $form->addFieldset('webforms_print_approval', array(
            'legend' => __('Approval Print Settings')
        ));

        $fieldset->addField('approved_print_template_id', 'select', array(
            'label' => __('Approved result print template'),
            'name' => 'approved_print_template_id',
            'note' => __('Select template for printable version of submission results for approved result'),
            'values' => $model->getTemplateOptions(),
        ));

        $fieldset->addField('approved_print_attach_to_email', 'select', array(
            'label' => __('Attach PDF to approved result email'),
            'name' => 'approved_print_attach_to_email',
            'note' => __('Attach printable version of the result to customer approved result email'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset->addField('completed_print_template_id', 'select', array(
            'label' => __('Completed result print template'),
            'name' => 'completed_print_template_id',
            'note' => __('Select template for printable version of submission results for completed result'),
            'values' => $model->getTemplateOptions(),
        ));

        $fieldset->addField('completed_print_attach_to_email', 'select', array(
            'label' => __('Attach PDF to completed result email'),
            'name' => 'completed_print_attach_to_email',
            'note' => __('Attach printable version of the result to customer completed result email'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $this->_eventManager->dispatch('adminhtml_webforms_form_edit_tab_print_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}