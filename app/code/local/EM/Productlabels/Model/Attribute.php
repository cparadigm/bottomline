<?php
class EM_Productlabels_Model_Attribute extends Mage_Catalog_Model_Resource_Eav_Attribute
{
	const SCOPE_STORE                           = 0;
    const SCOPE_GLOBAL                          = 1;
    const SCOPE_WEBSITE                         = 2;

    const MODULE_NAME                           = 'EM_Productlabels';
    const ENTITY                                = 'productlabels_eav_attribute';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix                     = 'productlabels_entity_attribute';
    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject                     = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels                   = null;

	/**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('productlabels/attribute');
    }
	
	/**
     * Return is attribute global
     *
     * @return integer
     */
    public function getIsGlobal()
    {
        return $this->_getData('is_global');
    }

    /**
     * Retrieve attribute is global scope flag
     *
     * @return bool
     */
    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    /**
     * Retrieve attribute is website scope website
     *
     * @return bool
     */
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }

    /**
     * Retrieve attribute is store scope flag
     *
     * @return bool
     */
    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }

    /**
     * Retrieve store id
     *
     * @return int
     */
    public function getStoreId()
    {
        $dataObject = $this->getDataObject();
        if ($dataObject) {
            return $dataObject->getStoreId();
        }
        return $this->getData('store_id');
    }
}