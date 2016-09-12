<?php
class EM_Productlabels_Block_Adminhtml_Element_Fieldsetcss extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
{
    /*public function getScopeLabel()
    {
        $html = '';
        $attribute_code = $this->getElement()->getName();
        $attribute = Mage::getModel('productlabels/attribute')->getCollection()
                ->addFieldToFilter('name_attribute',$attribute_code)->setPageSize(1)
                ->getFirstItem();
        $this->setData('attribute',$attribute);
        if (!$attribute || Mage::app()->isSingleStoreMode()) {
            return $html;
        }
        if ($attribute->isScopeGlobal()) {
            $html.= '[GLOBAL]';
        }
        elseif ($attribute->isScopeWebsite()) {
            $html.= '[WEBSITE]';
        }
        elseif ($attribute->isScopeStore()) {
            $html.= '[STORE VIEW]';
        }

        return $html;
    }*/

    /*public function getAttribute()
    {
        return $this->getData('attribute');
    }*/

    public function getAttributeCode()
    {
        return $this->getAttribute()->getNameAttribute();
    }

    /*public function getDataObject()
    {
        return Mage::registry('productlabels_data');
    }*/

    public function usedDefault()
    {
        $store = $this->getRequest()->getParam('store');
        if(!$store)
            return false;
        
        return Mage::registry('productlabels_css_data')->getStore() == 0;
    }

    


}

?>
