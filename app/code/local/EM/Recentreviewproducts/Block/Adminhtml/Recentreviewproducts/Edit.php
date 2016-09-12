<?php

class EM_Recentreviewproducts_Block_Adminhtml_Recentreviewproducts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'recentreviewproducts';
        $this->_controller = 'adminhtml_recentreviewproducts';
        
        $this->_updateButton('save', 'label', Mage::helper('recentreviewproducts')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('recentreviewproducts')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('recentreviewproducts_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'recentreviewproducts_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'recentreviewproducts_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('recentreviewproducts_data') && Mage::registry('recentreviewproducts_data')->getId() ) {
            return Mage::helper('recentreviewproducts')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('recentreviewproducts_data')->getTitle()));
        } else {
            return Mage::helper('recentreviewproducts')->__('Add Item');
        }
    }
}