<?php
class Boardroom_Orderdetailreports_Block_Orderdetailreports extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getMymodule() {
        if (!$this->hasData('orderdetailreports')) {
            $this->setData('orderdetailreports', Mage::registry('orderdetailreports'));
        }
        return $this->getData('orderdetailreports');
    }
}