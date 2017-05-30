<?php

namespace VladimirPopov\WebForms\Block\Adminhtml\Quickresponse;

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
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'quickresponse_id';
        $this->_blockGroup = 'VladimirPopov_WebForms';
        $this->_controller = 'adminhtml_quickresponse';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Quick Response'));
        $this->buttonList->update('delete', 'label', __('Delete Quick Response'));

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

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('webforms_quickresponse')->getId()) {
            return __("Quickresponse # %1 | %2", $this->_coreRegistry->registry('webforms_quickresponse')->getId(),$this->_localeDate->formatDate($this->_coreRegistry->registry('webforms_quickresponse')->getCreatedTime(), \IntlDateFormatter::MEDIUM,true));
        } else {
            return __('New Quick Response');
        }
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}