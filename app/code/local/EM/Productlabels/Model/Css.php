<?php
class EM_Productlabels_Model_Css extends Mage_Catalog_Model_Abstract
{
	/**
	* Maps to the array key from Setup.php::getDefaultEntities()
	*/
    const ENTITY = 'productlabels_css';
	/**
     * Model css prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'productlabels';

    /**
     * Name of the css object
     *
     * @var string
     */
    protected $_eventObject = 'css';
	/**
     * Initialize css model
     */
    function _construct()
    {
        $this->_init('productlabels/css');
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