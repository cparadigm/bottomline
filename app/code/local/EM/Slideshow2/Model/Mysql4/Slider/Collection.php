<?php

class EM_Slideshow2_Model_Mysql4_Slider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow2/slider');
    }
}