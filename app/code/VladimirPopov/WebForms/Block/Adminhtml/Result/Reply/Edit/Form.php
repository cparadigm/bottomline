<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Reply\Edit;

class Form
    extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_messageCollectionFactory;

    protected $_quickresponseConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \VladimirPopov\WebForms\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \VladimirPopov\WebForms\Model\Config\Quickresponse $quickresponseConfig,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_messageCollectionFactory = $messageCollectionFactory;
        $this->_quickresponseConfig = $quickresponseConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $modelForm = $this->_coreRegistry->registry('webforms_form');

        $form = $this->_formFactory->create(array(
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/message/save', ['_current' => true]),
                'method' => 'post',
            ]
        ));

        $form->setFieldNameSuffix('message');

        $form->addField('reply_results', 'note', array(
            'text' => $this->getLayout()->createBlock('VladimirPopov\WebForms\Block\Adminhtml\Result\Reply\Results', 'reply_results')->toHtml()
        ));

        $Ids = $this->getRequest()->getParam('id');

        if (!is_array($Ids)) {
            $Ids = array($Ids);
        }

        $form->addField('result_id', 'hidden', array(
            'name' => 'result_id',
            'value' => serialize($Ids),
        ));

        $form->addField('webform_id', 'hidden', array(
            'name' => 'webform_id',
            'value' => $modelForm->getId(),
        ));

        $form->addField('email', 'hidden', array(
            'name' => 'email'
        ));

        // message block
        $message = $form->addFieldset('reply_fieldset', array(
            'legend' => __('Reply')
        ));

        $quickresponse_options = $this->_quickresponseConfig->toOptionArray();

        if (count($quickresponse_options))
            $message->addField('quick_response', 'select', array(
                'label' => __('Quick response'),
                'name' => 'quick_response',
                'style' => '',
                'class' => 'order-disabled',
                'values' => array_merge(array(array('label' => '...', 'value' => '')), $quickresponse_options),
                'after_element_html' => '<button class="scalable" id="quickresponse_button" type="button"><span>' . __('Load') . '</span></button>'
            ));

        $editor_type = 'editor';
        $config = $this->_wysiwygConfig->getConfig();

        $message->addField('message', $editor_type, array(
            'label' => __('Message'),
            'title' => __('Message'),
            'style' => 'width:700px; height:300px;',
            'name' => 'message',
            'required' => true,
            'config' => $config
        ));

        if (count($Ids) == 1) {
            $history = $this->_messageCollectionFactory->create()->addFilter('result_id', $Ids[0])->load();
            if (count($history)) {
                $form->addField('reply_history', 'note', array(
                        "text" => ' <div class="admin__page-section-item-title"><span class="title">' .
                            __('Messages History') .
                            '</span></div><div class="fieldset"><div class="hor-scroll">' .
                            $this->getLayout()->createBlock('VladimirPopov\WebForms\Block\Adminhtml\Result\Reply\History')->toHtml() .
                            '</div></div>'
                    )
                );
            }
        }

        $form->setUseContainer(true);

        $this->setForm($form);

    }
}
