<?php
class EM_Productlabels_Block_Productlabels extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getProductlabels()     
     { 
        if (!$this->hasData('productlabels')) {
            $this->setData('productlabels', Mage::registry('productlabels'));
        }
        return $this->getData('productlabels');
        
    }

    public function addObject($_object)
    {
        $this->setData('object',$_object);
        return $this;
    }

    public function getObject()
    {
        return $this->getData('object');
    }
}