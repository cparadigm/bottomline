<?php

class EM_Dynamicproducts_Model_Dynamicproducts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dynamicproducts/dynamicproducts');
    }
}