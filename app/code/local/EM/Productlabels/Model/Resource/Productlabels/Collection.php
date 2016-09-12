<?php
class EM_Productlabels_Model_Resource_Productlabels_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	/**
     * Productlabels limitation filters
     * Allowed filters
     *  store_id                int;
     *
     * @var array
     */
    protected $_productLimitationFilters     = array();
	
	/**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('productlabels/productlabels');
    }
	
	/**
     * Add store availability filter. Include availability product
     * for store website
     *
     * @param mixed $store
     * @return EM_Blog_Model_Resource_Post_Collection
     */
    public function addStoreFilter($store = null)
    {
        if ($store === null) {
            $store = $this->getStoreId();
        }
        $store = Mage::app()->getStore($store);

        if (!$store->isAdmin()) {
            $this->setStoreId($store);
            $this->_productLimitationFilters['store_id'] = $store->getId();
            $this->_applyProductLimitations();
        }

        return $this;
    }
}
?>