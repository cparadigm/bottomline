<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */


class Amasty_Rules_Model_Banners extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('amrules/banners');
    }

    /**
     * @param $id
     * @return Mage_Core_Model_Abstract
     */
    public function loadByRuleId($id)
    {
        return $this->load($id, 'rule_id');
    }
}