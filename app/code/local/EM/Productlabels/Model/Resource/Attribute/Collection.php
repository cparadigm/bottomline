<?php
class EM_Productlabels_Model_Resource_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
	/**
     * Resource model initialization
     *
     */
    protected function _construct()
    {
        $this->_init('productlabels/attribute', 'eav/entity_attribute');
    }
}