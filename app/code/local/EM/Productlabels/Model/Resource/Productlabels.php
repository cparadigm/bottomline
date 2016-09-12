<?php
class EM_Productlabels_Model_Resource_Productlabels extends EM_Productlabels_Model_Resource_Abstract
{
	/**
     * Resource initialization
     */
    public function __construct()
    {
        $this->setType(EM_Productlabels_Model_Productlabels::ENTITY);
        $this->setConnection('productlabels_read', 'productlabels_write');
    }
	
	/**
     * Process post data before save
     *
     * @param Varien_Object $object
     * @return EM_Productlabels_Model_Resource_Productlabels
     */
    protected function _beforeSave(Varien_Object $object)
    {
        if($object->getId()){
			// Remove Old Image
			$oldImage = $object->getImage();
			if(is_array($oldImage)){
				if(isset($oldImage['delete'])){
					$path = Mage::getBaseDir('media').DS.'em_productlabels'.DS.'image'.DS;
					$nameImage = $oldImage['value'];
					
					/* Remove primary image */				
					if(is_file($path.$nameImage))
						  unlink($path.$nameImage);
				}
			}
			
			// Remove Old Background
			$oldImage = $object->getBackground();
			if(is_array($oldImage)){
				if(isset($oldImage['delete'])){
					$path = Mage::getBaseDir('media').DS.'em_productlabels'.DS.'background'.DS;
					$nameImage = $oldImage['value'];
					
					/* Remove primary image */				
					if(is_file($path.$nameImage))
						  unlink($path.$nameImage);
				}
			}
		}

        return parent::_beforeSave($object);
    }
	
	/**
     * Save data related with productlabels
     *
     * @param Varien_Object $productlabels
     * @return EM_Productlabels_Model_Resource_Productlabels
     */
    protected function _afterSave(Varien_Object $productlabels)
    {
        return parent::_afterSave($productlabels);
    }
}
?>