<?php

class EM_Dynamicproducts_Model_Mysql4_Dynamicproducts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the dynamicproducts_id refers to the key field in your database table.
        $this->_init('dynamicproducts/dynamicproducts', 'dynamicproducts_id');
    }
}