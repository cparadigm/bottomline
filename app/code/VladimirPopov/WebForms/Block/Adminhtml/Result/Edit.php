<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'result_id';
        $this->_blockGroup = 'VladimirPopov_WebForms';
        $this->_controller = 'adminhtml_result';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Result'));
        $this->buttonList->update('delete', 'label', __('Delete Result'));

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            -100
        );

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }
        ";

    }

    protected function _toHtml(){
        $js = $this->getLayout()->createBlock('Magento\Framework\View\Element\Template', 'webforms_js', array(
            'data' => [
                'template' => 'VladimirPopov_WebForms::webforms/logic.phtml',
                'result' => $this->_coreRegistry->registry('webforms_result'),
                'form' => $this->_coreRegistry->registry('webforms_form'),
            ]
        ))->toHtml();
        return parent::_toHtml().$js;
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('webforms_result')->getId()) {
            return __("Result # %1 | %2", $this->_coreRegistry->registry('webforms_result')->getId(), $this->_localeDate->formatDate($this->_coreRegistry->registry('webforms_result')->getCreatedTime(), \IntlDateFormatter::MEDIUM, true));
        } else {
            return __('New Result');
        }
    }

    public function getBackUrl()
    {
        if($this->getRequest()->getParam('customer_id'))
            return $this->getUrl('customer/index/edit', ['id' => $this->getRequest()->getParam('customer_id')]);
        return $this->getUrl('*/*/', ['webform_id' => $this->_coreRegistry->registry('webforms_form')->getId()]);
    }
}