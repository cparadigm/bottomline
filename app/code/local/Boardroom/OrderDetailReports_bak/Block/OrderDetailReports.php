<?php
class Boardroom_OrderDetailReports_Block_OrderDetailReports extends Mage_Core_Block_Template {

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