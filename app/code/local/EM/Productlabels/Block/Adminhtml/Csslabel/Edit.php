<?php
class EM_Productlabels_Block_Adminhtml_Csslabel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('em_productlabels/form/containercss.phtml');
        $this->_objectId = Mage::registry('productlabels_css_data')->getId();
        $this->_blockGroup = 'productlabels';
        $this->_controller = 'adminhtml_csslabel';
        $this->_removeButton('back');
        $this->_removeButton('reset');
        $this->_removeButton('delete');
        $this->_addButton('cancel', array(
            'label'     => Mage::helper('adminhtml')->__('Cancel'),
            'onclick'   => 'setLocation(\'' . $this->getCancelUrl() . '\')',
            'class'     => 'back',
        ), -1);
        $this->_updateButton('save', 'label', Mage::helper('productlabels')->__('Save Css'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('productlabels_css_data') && Mage::registry('productlabels_css_data')->getId() ) {
            return Mage::helper('productlabels')->__("Edit Css");
        } else {
            return Mage::helper('productlabels')->__('Add Item');
        }
    }

     /**
     * Get URL for cancel button
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getUrl('*/productlabels',array('store' => $this->getRequest()->getParam('store',0)));
    }

	public function getFormActionUrl(){
		return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
	}
}