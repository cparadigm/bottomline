<?php

class EM_Bestsellerproducts_Model_Mysql4_Bestsellerproducts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the bestsellerproducts_id refers to the key field in your database table.
        $this->_init('bestsellerproducts/bestsellerproducts', 'bestsellerproducts_id');
    }
}