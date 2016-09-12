<?php

class EM_Dynamicproducts_Model_Mysql4_Dynamicproducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dynamicproducts/dynamicproducts');
    }
}