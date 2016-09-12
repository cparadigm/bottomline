<?php

class EM_Saleproducts_Model_Mysql4_Saleproducts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the saleproducts_id refers to the key field in your database table.
        $this->_init('saleproducts/saleproducts', 'saleproducts_id');
    }
}