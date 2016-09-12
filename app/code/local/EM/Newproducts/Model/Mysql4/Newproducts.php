<?php

class EM_Newproducts_Model_Mysql4_Newproducts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the newproducts_id refers to the key field in your database table.
        $this->_init('newproducts/newproducts', 'newproducts_id');
    }
}