<?php

class EM_Recentreviewproducts_Model_Mysql4_Recentreviewproducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('recentreviewproducts/recentreviewproducts');
    }
}