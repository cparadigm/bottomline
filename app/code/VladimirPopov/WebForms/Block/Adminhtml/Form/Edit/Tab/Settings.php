<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class Settings extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \VladimirPopov\WebForms\Model\Config\Captcha
     */
    protected $_captchaConfig;

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
        \VladimirPopov\WebForms\Model\Config\Captcha $captchaConfig,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_captchaConfig = $captchaConfig;
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

        $fieldset = $form->addFieldset('webforms_general', array(
            'legend' => __('General Settings')
        ));

        $fieldset->addField('accept_url_parameters', 'select', array(
            'label'    => __('Accept URL parameters'),
            'title'    => __('Accept URL parameters'),
            'name'     => 'accept_url_parameters',
            'required' => false,
            'note'     => __('Accept URL parameters to set field values. Use field Code value as parameter name'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));
        
        $fieldset->addField('survey', 'select', array(
            'label' => __('Survey mode'),
            'title' => __('Survey mode'),
            'name' => 'survey',
            'required' => false,
            'note' => __('Survey mode allows filling up the form only one time'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $fieldset->addField('redirect_url', 'text', array(
            'label' => __('Redirect URL'),
            'title' => __('Redirect URL'),
            'name' => 'redirect_url',
            'note' => __('Redirect to specified url after successful submission'),
        ));

        $fieldset = $form->addFieldset('webforms_approval', array(
            'legend' => __('Result Approval Settings')
        ));

        $approve = $fieldset->addField('approve', 'select', array(
            'label' => __('Enable result approval controls'),
            'title' => __('Enable result approval controls'),
            'name' => 'approve',
            'required' => false,
            'note' => __('You can switch submission result status: pending, approved or not approved'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $email_result_approval = $fieldset->addField('email_result_approval', 'select', array(
            'label' => __('Enable approval status notification'),
            'title' => __('Enable approval status notification'),
            'name' => 'email_result_approval',
            'required' => false,
            'note' => __('Send customer notification email on submission result status change'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $bcc_approval_email = $fieldset->addField('bcc_approval_email', 'text', array(
            'label' => __('Bcc e-mail address'),
            'note'  => __('Send blind carbon copy of notification to specified address. You can set multiple addresses comma-separated'),
            'name'  => 'bcc_approval_email'
        ));

        $email_result_notapproved_template = $fieldset->addField('email_result_notapproved_template_id', 'select', array(
            'label' => __('Result NOT approved notification email template'),
            'title' => __('Result NOT approved notification email template'),
            'name' => 'email_result_notapproved_template_id',
            'required' => false,
            'values' => $model->getTemplateOptions(),
        ));

        $email_result_approved_template = $fieldset->addField('email_result_approved_template_id', 'select', array(
            'label' => __('Result approved notification email template'),
            'title' => __('Result approved notification email template'),
            'name' => 'email_result_approved_template_id',
            'required' => false,
            'values' => $model->getTemplateOptions(),
        ));

        $email_result_completed_template = $fieldset->addField('email_result_completed_template_id', 'select', array(
            'label' => __('Result completed notification email template'),
            'title' => __('Result completed notification email template'),
            'name' => 'email_result_completed_template_id',
            'required' => false,
            'values' => $model->getTemplateOptions(),
        ));

        $fieldset = $form->addFieldset('webforms_captcha', array(
            'legend' => __('reCaptcha Settings')
        ));

        $fieldset->addField('captcha_mode', 'select', array(
            'label' => __('Captcha mode'),
            'title' => __('Captcha mode'),
            'name' => 'captcha_mode',
            'required' => false,
            'note' => __('Default value is set in Forms Settings'),
            'values' => $this->_captchaConfig->toOptionArray(true),
        ));

        $fieldset = $form->addFieldset('webforms_files', array(
            'legend' => __('Files Settings')
        ));

        $fieldset->addField('files_upload_limit', 'text', array(
            'label' => __('Files upload limit'),
            'title' => __('Files upload limit'),
            'name' => 'files_upload_limit',
            'class' => 'validate-number',
            'note' => __('Maximum upload file size in kB'),
        ));

        $fieldset = $form->addFieldset('webforms_images', array(
            'legend' => __('Images Settings')
        ));

        $fieldset->addField('images_upload_limit', 'text', array(
            'label' => __('Images upload limit'),
            'title' => __('Images upload limit'),
            'class' => 'validate-number',
            'name' => 'images_upload_limit',
            'note' => __('Maximum upload image size in kB'),
        ));

//        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence','form_settings_dependence')
//            ->addFieldMap($approve->getHtmlId(), $approve->getName())
//            ->addFieldMap($email_result_approval->getHtmlId(), $email_result_approval->getName())
//            ->addFieldMap($email_result_approved_template->getHtmlId(),$email_result_approved_template->getName())
//            ->addFieldMap($email_result_notapproved_template->getHtmlId(), $email_result_notapproved_template->getName())
//            ->addFieldMap($email_result_completed_template->getHtmlId(), $email_result_completed_template->getName())
//            ->addFieldDependence(
//                $email_result_approval->getName(),
//                $approve->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $email_result_approved_template->getName(),
//                $email_result_approval->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $email_result_notapproved_template->getName(),
//                $email_result_approval->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $email_result_completed_template->getName(),
//                $email_result_approval->getName(),
//                1
//            )
//        );

        $this->_eventManager->dispatch('adminhtml_webforms_form_edit_tab_settings_prepare_form', ['form' => $form]);

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
        return __('General Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Settings');
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