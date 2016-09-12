<?php
class EM_Productlabels_Block_Css extends Mage_Core_Block_Template
{
    public function  _toHtml() {
        $css = $this->getCss();
		if($css)
			echo "<style type='text/css'>$css</style>";
        parent::_toHtml();
    }
  
    public function getCss()
    {
        $store = Mage::app()->getStore()->getId();
        $css = Mage::getModel('productlabels/css')->getCollection()
                ->setStoreId($store)
				->addAttributeToSelect('content')
				->getFirstItem();
        return $css->getContent();
    }
}