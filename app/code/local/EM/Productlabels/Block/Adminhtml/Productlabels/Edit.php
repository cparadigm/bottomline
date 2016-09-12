<?php
class EM_Productlabels_Block_Adminhtml_Productlabels_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('em_productlabels/form/container.phtml');
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'productlabels';
        $this->_controller = 'adminhtml_productlabels';
        
        $this->_updateButton('save', 'label', Mage::helper('productlabels')->__('Save Label'));
        $this->_updateButton('delete', 'label', Mage::helper('productlabels')->__('Delete Label'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productlabels_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productlabels_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productlabels_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function deletePicture(id)
            {
                $(id + '-pic').setStyle({'display':'none'});
                $('no' + id).value = 1;
            }
        ";
    }

    /**
     * Return save url for edit form
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/',array('store' => $this->getRequest()->getParam('store',0)));
    }

    public function getHeaderText()
    {
        if( Mage::registry('productlabels_data') && Mage::registry('productlabels_data')->getId() ) {
            return Mage::helper('productlabels')->__("Edit Label '%s'", $this->htmlEscape(Mage::registry('productlabels_data')->getName()));
        } else {
            return Mage::helper('productlabels')->__('Add Label');
        }
    }
	
	
}