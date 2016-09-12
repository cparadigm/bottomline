<?php

class Magik_Autocomplete_Model_Autocomplete extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('autocomplete/autocomplete');
    }
}