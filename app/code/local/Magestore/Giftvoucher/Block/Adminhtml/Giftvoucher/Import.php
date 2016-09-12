<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Import extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'giftvoucher';
        $this->_controller = 'adminhtml_giftvoucher';
        $this->_mode = 'import';
        $this->_updateButton('save', 'label', Mage::helper('giftvoucher')->__('Import'));
        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_addButton('print', array(
            'label' => Mage::helper('giftvoucher')->__('Import and Print'),
            'onclick' => "importAndPrint()",
            'class' => 'save'
                ), 100);

        $this->_formScripts[] = "
            function importAndPrint(){
             
//             $('edit_form').target = '_blank';
                editForm.submit('" . $this->getUrl('*/*/processImport', array(
                    'print' => 'true',
                )) . "');
               
            }
        ";
    }

    public function getHeaderText() {
        return Mage::helper('giftvoucher')->__('Import Gift Codes');
    }

}