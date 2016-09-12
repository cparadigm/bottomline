<?php

class Magik_Onestepcheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_agree = null;

    public function canOnestepcheckoutEnabled()
    {
        return (bool)Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkextenable');
    }

    public function isGuestCheckoutAllowed()
    {
        return Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkallowguest');
    }

    public function isShippingAddressAllowed()
    {
    	return Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkshiptodiffadd');
    }

   /* public function getRequiredAgreementIds()
    {
        if (is_null($this->_agree))
        {
            if (Mage::getStoreConfigFlag('mgkonestepcheckout_section/mgkopcterms_group/mgktermsenable'))
            {
                $this->_agree = Mage::getModel('checkout/agreement')->getCollection()
                    		->addStoreFilter(Mage::app()->getStore()->getId())
				->addFieldToFilter('is_active', 1)
				->getAllIds();
            }
            else
            	$this->_agree = array();
        }
        return $this->_agree;
    }
    */
    public function isSubscribeNewAllowed()
    {
        if (!Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcterms_group/mgknewssub'))
            return false;

        $cust_sess = Mage::getSingleton('customer/session');
        if (!$cust_sess->isLoggedIn() && !Mage::getStoreConfig('newsletter/subscription/allow_guest_subscribe'))
            return false;

		$subscribed	= $this->getIsSubscribed();
		if($subscribed)
			return false;
		else
			return true;
    }
    
    public function getIsSubscribed()
    {
        $cust_sess = Mage::getSingleton('customer/session');
        if (!$cust_sess->isLoggedIn())
            return false;

        return Mage::getModel('newsletter/subscriber')->getCollection()
            	->useOnlySubscribed()
            	->addStoreFilter(Mage::app()->getStore()->getId())
		->addFieldToFilter('subscriber_email', $cust_sess->getCustomer()->getEmail())
		->getAllIds();
    }
/*new */
    public function getcheckoutPagetitle()
    {
        return Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkopcpagetitle');
    }
    public function getcheckoutPagedescp()
    {
        return Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkopcpagedescp');
    }
    public function getmgkNamePrefixOptions()
    {
        $options=Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcfield_group/mgkopcprefixopt');
	$options = trim($options);
        if (empty($options)) {
            return false;
        }
        $result = array();
        $options = explode(';', $options);
        foreach ($options as $value) {
            $value = $this->escapeHtml(trim($value));
            $result[$value] = $value;
        }
        return $result;
    }
    public function getmgkNameSuffixOptions()
    {
        $options=Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcfield_group/mgkopcsuffixopt');
	$options = trim($options);
        if (empty($options)) {
            return false;
        }
        $result = array();
        $options = explode(';', $options);
        foreach ($options as $value) {
            $value = $this->escapeHtml(trim($value));
            $result[$value] = $value;
        }
        return $result;
    }
  

}