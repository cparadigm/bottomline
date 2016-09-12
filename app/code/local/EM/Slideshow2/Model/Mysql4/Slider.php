<?php

class EM_Slideshow2_Model_Mysql4_Slider extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the id refers to the key field in your database table.
        $this->_init('slideshow2/slider', 'id');
    }
}