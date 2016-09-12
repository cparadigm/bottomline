<?php
class EM_Dynamicproducts_Block_Chooser extends Mage_Adminhtml_Block_Catalog_Category_Widget_Chooser
{
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getBaseUrl().'dynamicproducts/admin_chooser/chooser/uniq_id/'.$uniqId.'/use_massaction/false';
        //$sourceUrl = $this->getUrl('*/catalogmenuwidget_chooser/chooser', array('uniq_id' => $uniqId, 'use_massaction' => false));
        //$sourceUrl = str_replace('admin/','',$sourceUrl);
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);

        if ($element->getValue()) {
            //$value = explode('/', $element->getValue());
            $value = $element->getValue();
            $categoryId = isset($value) ? $value : false;
            if ($categoryId) {
                $label = Mage::getSingleton('catalog/category')->load($categoryId)->getName();
                $chooser->setLabel($label);
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }
  
    public function getNodeClickListener()
    {
        if ($this->getData('node_click_listener')) {
            return $this->getData('node_click_listener');
        }
        if ($this->getUseMassaction()) {
            $js = '
                function (node, e) {
                    if (node.ui.toggleCheck) {
                        node.ui.toggleCheck(true);
                    }
                }
            ';
        } else {
		
            $chooserJsObject = $this->getId();
            $js = '
                function (node, e) {
                    '.$chooserJsObject.'.setElementValue(node.attributes.id);
                    '.$chooserJsObject.'.setElementLabel(node.text);
                    '.$chooserJsObject.'.close();
                }
            ';
        }
        return $js;
    }
    
    public function getLoadTreeUrl($expanded=null)
    {
        //return $this->getBaseUrl().'/catalogmenuwidget'
        return $this->getUrl('*/admin_chooser/categoriesJson', array(
            '_current'=>true,
            'uniq_id' => $this->getId(),
            'use_massaction' => $this->getUseMassaction()
        ));
    }
}
?>