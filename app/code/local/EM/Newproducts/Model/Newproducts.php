<?php

class EM_Newproducts_Model_Newproducts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('newproducts/newproducts');
    }
}