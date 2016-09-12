<?php

class EM_Recentreviewproducts_Model_Mysql4_Recentreviewproducts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the recentreviewproducts_id refers to the key field in your database table.
        $this->_init('recentreviewproducts/recentreviewproducts', 'recentreviewproducts_id');
    }
}