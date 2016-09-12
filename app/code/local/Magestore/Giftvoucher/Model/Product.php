<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Giftvoucher
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Giftvoucher Product Model
 * 
 * @category    Magestore
 * @package     Magestore_Giftvoucher
 * @author      Magestore Developer
 */

class Magestore_Giftvoucher_Model_Product extends Mage_Rule_Model_Rule
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('giftvoucher/product');
    }

    public function getConditionsInstance()
    {
        return Mage::getModel('salesrule/rule_condition_combine');
    }

    public function getActionsInstance()
    {
        return Mage::getModel('salesrule/rule_condition_product_combine');
    }

    public function loadPost(array $rule)
    {
        $arr = $this->_convertFlatToRecursive($rule);
        if (isset($arr['conditions'])) {
            $this->getConditions()->setConditions(array())->loadArray($arr['conditions'][1]);
        }
        if (isset($arr['actions'])) {
            $this->getActions()->setActions(array())->loadArray($arr['actions'][1], 'actions');
        }
        return $this;
    }

    public function loadByProduct($product)
    {
        if (is_object($product)) {
            if ($product->getId()) {
                return $this->load($product->getId(), 'product_id');
            }
            return $this;
        }
        if ($product) {
            return $this->load($product, 'product_id');
        }
        return $this;
    }

}
