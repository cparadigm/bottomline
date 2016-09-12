<?php

class Magik_Autocomplete_Block_Adminhtml_Autocomplete_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'autocomplete';
        $this->_controller = 'adminhtml_autocomplete';
        
        $this->_updateButton('save', 'label', Mage::helper('autocomplete')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('autocomplete')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('autocomplete_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'autocomplete_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'autocomplete_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('autocomplete_data') && Mage::registry('autocomplete_data')->getId() ) {
            return Mage::helper('autocomplete')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('autocomplete_data')->getTitle()));
        } else {
            return Mage::helper('autocomplete')->__('Add Item');
        }
    }
}