<?php
class EM_Recentreviewproducts_Block_Recentreviewproducts extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getRecentreviewproducts()     
    { 
        if (!$this->hasData('recentreviewproducts')) {
            $this->setData('recentreviewproducts', Mage::registry('recentreviewproducts'));
        }
        return $this->getData('recentreviewproducts');        
    }
}