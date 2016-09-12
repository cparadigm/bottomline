<?php

class EM_Saleproducts_Model_Saleproducts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('saleproducts/saleproducts');
    }
}