<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form;

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
        $this->_objectId = 'form_id';
        $this->_blockGroup = 'VladimirPopov_WebForms';
        $this->_controller = 'adminhtml_form';

        parent::_construct();

        $this->buttonList->remove('reset');

        if ($this->_isAllowedAction('VladimirPopov_WebForms::manage_forms')) {
            $this->buttonList->update('save', 'label', __('Save Form'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => $this->_coreRegistry->registry('webforms_form')->getId() ? 'save' : 'primary',
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

        if ($this->_coreRegistry->registry('webforms_form')->getId()) {
            $this->buttonList->add('add_fieldset', array
            (
                'label' => __('Add Fieldset'),
                'class' => 'add',
                'onclick' => 'setLocation(\'' . $this->getAddFieldsetUrl() . '\')',
            ));

            $this->buttonList->add('add_field', array
            (
                'label' => __('Add Field'),
                'class' => 'add',
                'onclick' => 'setLocation(\'' . $this->getAddFieldUrl() . '\')',
            ));

            $this->buttonList->add('duplicate', array
            (
                'label' => __('Duplicate'),
                'class' => 'add',
                'onclick' => 'setLocation(\'' . $this->getDuplicateUrl() . '\')',
            ));

            $this->buttonList->add('delete', array
            (
                'label' => __('Delete'),
                'class' => 'delete',
                'onclick' => 'deleteConfirm(\'' . __('Are you sure you want to delete the entire form and associated data?') . '\', \'' . $this->getDeleteUrl() . '\')',
            ), -1);

        } else {
            $this->buttonList->remove('save');
        }


        if (!$this->_isAllowedAction('VladimirPopov_WebForms::form_delete')) {
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
            return __("Edit Form '%1'", $this->escapeHtml($this->_coreRegistry->registry('webforms_form')->getName()));
        } else {
            return __('New Form');
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

    public function getDuplicateUrl()
    {
        return $this->getUrl('*/*/duplicate', array('id' => $this->_coreRegistry->registry('webforms_form')->getId()));
    }

    public function getAddFieldUrl()
    {
        return $this->getUrl('*/field/new', array('webform_id' => $this->_coreRegistry->registry('webforms_form')->getId()));
    }

    public function getAddFieldsetUrl()
    {
        return $this->getUrl('*/fieldset/new', array('webform_id' => $this->_coreRegistry->registry('webforms_form')->getId()));
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
}