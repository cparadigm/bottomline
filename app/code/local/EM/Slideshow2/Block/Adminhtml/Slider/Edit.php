<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'slideshow2';
        $this->_controller = 'adminhtml_slider';
        
        //$this->_updateButton('save', 'label', Mage::helper('slideshow2')->__('Save slideshow'));
		$this->_removeButton('save');
		if(Mage::getSingleton('admin/session')->isAllowed('cms/slideshow2/save')){
			$this->_addButton('save', array(
				'label'     => Mage::helper('slideshow2')->__('Save slideshow'),
				'onclick'   => 'saveEdit()',
				'class'     => 'save',
			), -100);
			
			
			
			$this->_addButton('saveandcontinue', array(
				'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
				'onclick'   => 'saveAndContinueEdit()',
				'class'     => 'save',
			), -100);
		}	

		if(Mage::getSingleton('admin/session')->isAllowed('cms/slideshow2/delete')){
			$this->_updateButton('delete', 'label', Mage::helper('slideshow2')->__('Delete slideshow'));	
		} else 
			$this->_removeButton('delete');
		

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('slideshow2_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'slideshow2_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'slideshow2_content');
                }
            }

            function saveAndContinueEdit(){
				var frm	=	jQuery('#edit_form');
				var disabled = frm.find(':input:disabled').removeAttr('disabled');
                editForm.submit($('edit_form').action+'back/edit/');
            }
			
			function saveEdit(){
				var frm	=	jQuery('#edit_form');
				var disabled = frm.find(':input:disabled').removeAttr('disabled');
                editForm.submit($('edit_form').action);
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('slideshow2_data') && Mage::registry('slideshow2_data')->getId() ) {
            return Mage::helper('slideshow2')->__("Edit slideshow '%s'", $this->htmlEscape(Mage::registry('slideshow2_data')->getName()));
        } else {
            return Mage::helper('slideshow2')->__('Add slideshow');
        }
    }
}