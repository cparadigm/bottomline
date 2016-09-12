<?php
class Magik_Onestepcheckout_Block_Onepage_Link extends Mage_Core_Block_Template
{
    public function isPossibleOnestepcheckout()
    {
        return $this->helper('onestepcheckout')->canOnestepcheckoutEnabled();
    }

    public function checkEnable()
    {
        return Mage::getSingleton('checkout/session')->getQuote()->validateMinimumAmount();
    }

    public function getOnestpecheckoutUrl()
    {
    	$url	= $this->getUrl('onestepcheckout', array('_secure' => true));
        return $url;
    }
}
