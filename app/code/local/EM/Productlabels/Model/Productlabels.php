<?php
class EM_Productlabels_Model_Productlabels extends Mage_Catalog_Model_Abstract
{
	/**
	* Maps to the array key from Setup.php::getDefaultEntities()
	*/
    const ENTITY = 'productlabels';
	
	const CACHE_TAG              = 'em_productlabels';
    protected $_cacheTag         = array('em_productlabels','block_html');
	
	/**
     * Model productlabels prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'productlabels';

    /**
     * Name of the productlabels object
     *
     * @var string
     */
    protected $_eventObject = 'productlabels';
	/**
     * Initialize productlabels model
     */
    protected function _construct()
    {
		$this->_cacheTag[] = Mage_Cms_Model_Page::CACHE_TAG;
        $this->_init('productlabels/productlabels');
    }
	
	/**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData('store_id')) {
            return $this->getData('store_id');
        }
        return Mage::app()->getStore()->getId();
    }
	
	/**
     * Retrieve all post attributes
     *
     * @todo Use with Flat Resource
     * @return array
     */
    public function getAttributes($group = array())
    {
        $postAttributes = $this->getResource()
            ->loadAllAttributes($this)
            ->getSortedAttributes();
		$attributes = array();	
		if(count($group)){
			foreach ($postAttributes as $attribute) {
				if (in_array($attribute->getAttributeCode(),$group)) {
					$attributes[] = $attribute;
				}
			}
		}
		else
			$attributes = $postAttributes;
        return $attributes;
    }
}
?>