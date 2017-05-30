<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Field;

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
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'field_id';
        $this->_blockGroup = 'VladimirPopov_WebForms';
        $this->_controller = 'adminhtml_field';

        parent::_construct();

        if ($this->_isAllowedAction('VladimirPopov_WebForms::form'.$this->_coreRegistry->registry('webforms_form')->getId())) {

            $this->buttonList->update('save', 'label', __('Save Field'));

            if (strstr($this->_coreRegistry->registry('webforms_field')->getType(), 'select')) {
                $this->buttonList->add('logic', array(
                    'label' => __('Add Logic'),
                    'onclick' => 'setLocation(\'' . $this->getAddLogicUrl() . '\')',
                ));
            }

            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('VladimirPopov_WebForms::form_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Field'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('webforms_form')->getId()) {
            return __("Edit Field '%1'", $this->escapeHtml($this->_coreRegistry->registry('webforms_field')->getName()));
        } else {
            return __('New Field');
        }
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
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    public function getAddLogicUrl()
    {
        return $this->getUrl('*/logic/new', array('field_id' => $this->_coreRegistry->registry('webforms_field')->getId()));
    }
    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/form/edit',['id' => $this->_coreRegistry->registry('webforms_form')->getId(), 'active_tab' => 'fields_section']);
    }
}