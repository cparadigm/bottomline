<?php

class EM_Bestsellerproducts_Model_Mysql4_Bestsellerproducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bestsellerproducts/bestsellerproducts');
    }
}