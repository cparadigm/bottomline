<?php

class Magik_Autocomplete_Model_Mysql4_Autocomplete extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the autocomplete_id refers to the key field in your database table.
        $this->_init('autocomplete/autocomplete', 'autocomplete_id');
    }
}