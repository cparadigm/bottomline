<?php
class EM_Productlabels_Model_Resource_Abstract extends Mage_Catalog_Model_Resource_Abstract
{
	/**
     * Redeclare attribute model
     *
     * @return string
     */
    protected function _getDefaultAttributeModel()
    {
        return 'productlabels/attribute';
    }
	
	/**
     * Returns default Store ID
     *
     * @return int
     */
    public function getDefaultStoreId()
    {
        return Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
    }
	
	/**
     * Retrieve select object for loading entity attributes values
     * Join attribute store value
     *
     * @param Varien_Object $object
     * @param string $table
     * @return Varien_Db_Select
     */
    protected function _getLoadAttributesSelect($object, $table)
    {
        /**
         * This condition is applicable for all cases when we was work in not single
         * store mode, customize some value per specific store view and than back
         * to single store mode. We should load correct values
         */
        if (Mage::app()->isSingleStoreMode()) {
            $storeId = (int)Mage::app()->getStore(true)->getId();
        } else {
            $storeId = (int)$object->getStoreId();
        }

       
        $storeIds = array($this->getDefaultStoreId());
        if ($storeId != $this->getDefaultStoreId()) {
            $storeIds[] = $storeId;
        }

        $select = $this->_getReadAdapter()->select()
            ->from(array('attr_table' => $table), array())
            ->where("attr_table.{$this->getEntityIdField()} = ?", $object->getId())
            ->where('attr_table.store_id IN (?)', $storeIds);
       
        return $select;
    }
	
	 /**
     * Initialize attribute value for object
     *
     * @param EM_Blog_Model_Abstract $object
     * @param array $valueRow
     * @return EM_Blog_Model_Resource_Abstract
     */
    protected function _setAttributeValue($object, $valueRow)
    {
        $attribute = $this->getAttribute($valueRow['attribute_id']);
        if ($attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $isDefaultStore = $valueRow['store_id'] == $this->getDefaultStoreId();
            if (isset($this->_attributes[$valueRow['attribute_id']])) {
                if ($isDefaultStore) {
                    $object->setAttributeDefaultValue($attributeCode, $valueRow['value']);
                } else {
                    $object->setAttributeDefaultValue(
                        $attributeCode,
                        $this->_attributes[$valueRow['attribute_id']]['value']
                    );
                }
            } else {
                $this->_attributes[$valueRow['attribute_id']] = $valueRow;
            }

            $value   = $valueRow['value'];
            $valueId = $valueRow['value_id'];

            $object->setData($attributeCode, $value);
            if (!$isDefaultStore) {
                $object->setExistsStoreValueFlag($attributeCode);
            }
            if(Mage::getVersion() > '1.6.2.0')
				$attribute->getBackend()->setEntityValueId($object, $valueId);
			else
				$attribute->getBackend()->setValueId($object, $valueId);
        }

        return $this;
    }

    /**
     * Insert or Update attribute data
     *
     * @param EM_Blog_Model_Abstract $object
     * @param Mage_Eav_Model_Entity_Attribute_Abstract $attribute
     * @param mixed $value
     * @return EM_Blog_Model_Resource_Abstract
     */
    protected function _saveAttributeValue($object, $attribute, $value)
    {
        $write   = $this->_getWriteAdapter();
        $storeId = (int)Mage::app()->getStore($object->getStoreId())->getId();
        $table   = $attribute->getBackend()->getTable();

        /**
         * If we work in single store mode all values should be saved just
         * for default store id
         * In this case we clear all not default values
         */
        if (Mage::app()->isSingleStoreMode()) {
            $storeId = $this->getDefaultStoreId();
            $write->delete($table, array(
                'attribute_id = ?' => $attribute->getAttributeId(),
                'entity_id = ?'    => $object->getEntityId(),
                'store_id <> ?'    => $storeId
            ));
        }

        $data = new Varien_Object(array(
            'entity_type_id'    => $attribute->getEntityTypeId(),
            'attribute_id'      => $attribute->getAttributeId(),
            'store_id'          => $storeId,
            'entity_id'         => $object->getEntityId(),
            'value'             => $this->_prepareValueForSave($value, $attribute)
        ));
        $bind = $this->_prepareDataForTable($data, $table);

        if ($attribute->isScopeStore()) {
            /**
             * Update attribute value for store
             */
            $this->_attributeValuesToSave[$table][] = $bind;
        } else if ($attribute->isScopeWebsite() && $storeId != $this->getDefaultStoreId()) {
            /**
             * Update attribute value for website
             */
            $storeIds = Mage::app()->getStore($storeId)->getWebsite()->getStoreIds(true);
            foreach ($storeIds as $storeId) {
                $bind['store_id'] = (int)$storeId;
                $this->_attributeValuesToSave[$table][] = $bind;
            }
        } else {
            /**
             * Update global attribute value
             */
            $bind['store_id'] = $this->getDefaultStoreId();
            $this->_attributeValuesToSave[$table][] = $bind;
        }

        return $this;
    }
	
	
}