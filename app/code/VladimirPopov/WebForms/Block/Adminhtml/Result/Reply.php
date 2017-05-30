<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result;

class Reply
    extends \Magento\Backend\Block\Widget\Form\Container
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

    public function _construct()
    {
        $this->_controller = 'adminhtml_result_reply';
        $this->_blockGroup = 'VladimirPopov_WebForms';

        $this->_headerText = __('Selected Result(s)');

        parent::_construct();

        $this->buttonList->remove('delete');

        $this->buttonList->update('save', 'label', __('Save Reply'));
        $this->buttonList->update('save', 'class', '');

        $Ids = $this->getRequest()->getParam('id');

        if (!is_array($Ids)) {
            $Ids = array($Ids);
        }

        if (count($Ids) == 1) {
            $this->buttonList->add('edit', array
            (
                'label' => __('Edit Result'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/edit', array('id' => $Ids[0])) . '\')',
            ));

            $this->buttonList->add('print', array
            (
                'label' => __('Print'),
                'class' => '',
                'onclick' => $this->getPrintAction($Ids[0]),
            ));
        }

        $this->buttonList->add('reply', array(
            'label' => __('Save Reply And E-mail'),
            'class' => 'primary',
            'onclick' => 'saveAndEmail()'
        ), -100);

        $this->_formScripts[] = "
			function saveAndEmail(){
				$('email').value = true;
				jQuery('#edit_form').form().submit();
			}
		";

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

    public function getPrintAction($id)
    {
        if(@class_exists('mPDF')) {
            return 'setLocation(\'' . $this->getUrl('*/*/printAction', array('id' => $id)) . '\')';
        }
        return 'alert(\''.__('Printing is disabled. Please install mPDF library. Run command: composer require mpdf/mpdf').'\')';
    }

    public function getBackUrl()
    {
        if($this->getRequest()->getParam('customer_id'))
            return $this->getUrl('customer/index/edit', ['id' => $this->getRequest()->getParam('customer_id')]);
        return $this->getUrl('*/*/', ['webform_id' => $this->_coreRegistry->registry('webforms_form')->getId()]);
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/message/save', ['_current' => true]);
    }

}
