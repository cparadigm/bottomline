<?php

class Magik_Autocomplete_Model_Mysql4_Autocomplete_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('autocomplete/autocomplete');
    }
}