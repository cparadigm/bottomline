<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('giftvoucher_form', array('legend' => Mage::helper('giftvoucher')->__('Create Product Settings')));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();
        $fieldset->addField('attribute_set_id', 'select', array(
            'label' => Mage::helper('giftvoucher')->__('Attribute Set'),
            'required' => true,
            'name' => 'attribute_set_id',
            'values' => $sets,
        ));
        $url = $this->getUrl('adminhtml/catalog_product/new', array('type' => 'giftvoucher'));
        $js = '<script type="text/javascript">
            //<![CDATA[
            function setAttributeGiftProduct(){
                    var set_id = $(\'attribute_set_id\').options[$(\'attribute_set_id\').selectedIndex].value;
                    var url=\'' . $url . '\'+"set/"+set_id;
                    setLocation(url);
            }
            //]]>
        </script>';
        $fieldset->addField('product_type', 'note', array(
            'label' => Mage::helper('giftvoucher')->__('Product Type'),
            'name' => 'product_type',
            'text' => Mage::helper('giftvoucher')->__('Gift Card') .
            '</br><button type="button" class="scalable save" onclick="setAttributeGiftProduct()"><span>' . Mage::helper("giftvoucher")->__("Continue") . '</span></button>' . $js,
        ));


        return parent::_prepareForm();
    }

}