<?php

class EM_Recentreviewproducts_Model_Recentreviewproducts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('recentreviewproducts/recentreviewproducts');
    }
}