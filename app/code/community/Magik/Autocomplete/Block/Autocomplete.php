<?php
class Magik_Autocomplete_Block_Autocomplete extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAutocomplete()     
     { 
        if (!$this->hasData('autocomplete')) {
            $this->setData('autocomplete', Mage::registry('autocomplete'));
        }
        return $this->getData('autocomplete');
        
    }
}