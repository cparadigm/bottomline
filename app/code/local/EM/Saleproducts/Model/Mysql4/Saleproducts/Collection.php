<?php

class EM_Saleproducts_Model_Mysql4_Saleproducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('saleproducts/saleproducts');
    }
}