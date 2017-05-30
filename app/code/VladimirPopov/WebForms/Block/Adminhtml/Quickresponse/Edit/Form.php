<?php

namespace VladimirPopov\WebForms\Block\Adminhtml\Quickresponse\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
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
        $this->setId('quickresponse_form');
        $this->setTitle(__('Quickresponse Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('webforms_quickresponse');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setFieldNameSuffix('quickresponse');

        $fieldset = $form->addFieldset('quickresponse_fieldset',array(
            'legend' => __('Quick Response')
        ));

        $fieldset->addField('title','text',array(
            'label' 	=> __('Title'),
            'class' 	=> 'required-entry',
            'required' 	=> true,
            'style' 	=> 'width:700px;',
            'name' 		=> 'title'
        ));

        $fieldset->addField('message', 'editor', array(
            'label'     => __('Message'),
            'title'     => __('Message'),
            'style'     => 'width:700px; height:300px;',
            'name'      => 'message',
            'required'  => true,
            'config'	=> $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()])
        ));
        
        $form->setValues($model->getData());

        $form->addField('id', 'hidden', array
        (
            'name' => 'id',
            'value' => $model->getId(),
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        return parent::_prepareForm();
    }
}