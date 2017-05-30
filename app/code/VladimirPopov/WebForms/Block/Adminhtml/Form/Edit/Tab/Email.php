<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class Email extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('E-mail Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('E-mail Settings');
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

        $fieldset = $form->addFieldset('email_settings', array(
            'legend' => __('E-mail Settings')
        ));

        $fieldset->addField('add_header', 'select', array(
            'label'  => __('Add header to the message'),
            'title'  => __('Add header to the message'),
            'name'   => 'add_header',
            'note'   => __('Add header with Store Group, IP and other information to the message'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset->addField('email_reply_template_id', 'select', array(
            'label'    => __('Reply template'),
            'title'    => __('Reply template'),
            'name'     => 'email_reply_template_id',
            'required' => false,
            'note'     => __('E-mail template for replies'),
            'values'   => $model->getTemplateOptions(),
        ));

        $fieldset = $form->addFieldset('admin_notification', array(
            'legend' => __('Admin Notification')
        ));

        $send_email = $fieldset->addField('send_email', 'select', array(
            'label'    => __('Enable admin notification'),
            'title'    => __('Enable admin notification'),
            'name'     => 'send_email',
            'required' => false,
            'options' => ['1' => __('Yes'), '0' => __('No')],
            'note'     => __('Send new results by e-mail. If you have Select/Contact field in the form, e-mail notification will be sent twice: to admin and to selected contact')
        ));

        $template = $fieldset->addField('email_template_id', 'select', array(
            'label'    => __('Admin notification template'),
            'title'    => __('Admin notification template'),
            'name'     => 'email_template_id',
            'required' => false,
            'note'     => __('E-mail template for admin notification letters'),
            'values'   => $model->getTemplateOptions(),
        ));

        $email = $fieldset->addField('email', 'text', array(
            'label' => __('Notification e-mail address'),
            'note'  => __('If empty default notification e-mail address will be used. You can set multiple addresses comma-separated'),
            'name'  => 'email'
        ));

        $bcc_admin_email = $fieldset->addField('bcc_admin_email', 'text', array(
            'label' => __('Bcc e-mail address'),
            'note'  => __('Send blind carbon copy of notification to specified address. You can set multiple addresses comma-separated'),
            'name'  => 'bcc_admin_email'
        ));

        $attachments_admin = $fieldset->addField('email_attachments_admin', 'select', array(
            'label'  => __('Attach files to notification for admin'),
            'note'  => __('Attach uploaded files to admin notification e-mail'),
            'name'   => 'email_attachments_admin',
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset = $form->addFieldset('customer_notification', array(
            'legend' => __('Customer Notification')
        ));

        $duplicate_email = $fieldset->addField('duplicate_email', 'select', array(
            'label'    => __('Enable customer notification'),
            'title'    => __('Enable customer notification'),
            'note'     => __('Send customer notification email.'),
            'name'     => 'duplicate_email',
            'required' => false,
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $customer_template = $fieldset->addField('email_customer_template_id', 'select', array(
            'label'    => __('Customer notification template'),
            'title'    => __('Customer notification template'),
            'name'     => 'email_customer_template_id',
            'required' => false,
            'note'     => __('E-mail template for customers notification letters'),
            'values'   => $model->getTemplateOptions(),
        ));
        
        $email_sender_name = $fieldset->addField('email_customer_sender_name', 'text', array(
            'label' => __('Sender name'),
            'note'  => __('Sender name for the customer notification. Leave empty for Store Name'),
            'name'  => 'email_customer_sender_name'
        ));

        $reply_to = $fieldset->addField('email_reply_to', 'text', array(
            'label' => __('Reply-to address for customer'),
            'note'  => __('Set reply-to parameter in customer notifications'),
            'name'  => 'email_reply_to'
        ));

        $attachments_customer = $fieldset->addField('email_attachments_customer', 'select', array(
            'label'  => __('Attach files to notification for customer'),
            'note'  => __('Attach uploaded files to customer notification e-mail'),
            'name'   => 'email_attachments_customer',
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

//        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence','form_email_dependence')
//            ->addFieldMap($duplicate_email->getHtmlId(), $duplicate_email->getName())
//            ->addFieldMap($customer_template->getHtmlId(), $customer_template->getName())
//            ->addFieldMap($reply_to->getHtmlId(), $reply_to->getName())
//            ->addFieldMap($attachments_customer->getHtmlId(), $attachments_customer->getName())
//            ->addFieldMap($send_email->getHtmlId(), $send_email->getName())
//            ->addFieldMap($email->getHtmlId(), $email->getName())
//            ->addFieldMap($template->getHtmlId(), $template->getName())
//            ->addFieldMap($attachments_admin->getHtmlId(), $attachments_admin->getName())
//            ->addFieldDependence(
//                $customer_template->getName(),
//                $duplicate_email->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $reply_to->getName(),
//                $duplicate_email->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $attachments_customer->getName(),
//                $duplicate_email->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $email->getName(),
//                $send_email->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $template->getName(),
//                $send_email->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $attachments_admin->getName(),
//                $send_email->getName(),
//                1
//            )
//        );

        $this->_eventManager->dispatch('adminhtml_webforms_form_edit_tab_email_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}