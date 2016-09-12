<?php
class Magik_Onestepcheckout_Block_Onestep extends Mage_Checkout_Block_Onepage_Billing {

    public function getAddressesHtmlSelect($type)
    {
      if ($this->isCustomerLoggedIn()) {
            $options = array();
            foreach ($this->getCustomer()->getAddresses() as $address) {
                $options[] = array(
                    'value' => $address->getId(),
                    'label' => $address->format('oneline')
                );
            }
	    if ($type=='billing') {
                    $address = $this->getCustomer()->getPrimaryBillingAddress();
            } else {
                    $address = $this->getCustomer()->getPrimaryShippingAddress();
	    }
	    if ($address) {
                     $addressId = $address->getId();
            }
	    $select = $this->getLayout()->createBlock('core/html_select')
                ->setName($type.'_address_id')
                ->setId($type.'-address-select')
                ->setClass('mgkosc-address-select')
                ->setExtraParams('onchange="'.$type.'.newAddress(!this.value)"')
                ->setValue($addressId)
                ->setOptions($options);
	    $select->addOption('', Mage::helper('checkout')->__('New Address'));
	    return $select->getHtml();
      }
        return '';
        
    }
    
    public function getmgkCountryHtmlSelect($type)
    {
        $countryId = $this->getAddress()->getCountryId();
        if (is_null($countryId)) {
            $countryId = Mage::getStoreConfig('mgkonestepcheckout_section/mgkopcgeneral_group/mgkopccountry');
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
            $select->setExtraParams('onchange="if(window.shipping)shipping.setSameAsBilling(false);"');
        }

        return $select->getHtml();
    }

    public function getmgkBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }
    
    public function getmgkShipingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

   
}