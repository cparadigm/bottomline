<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_View extends Mage_Core_Block_Template {

    public function _beforeToHtml() {
        parent::_beforeToHtml();
    }

    public function getGiftVoucher() {
        if (!$this->hasData('gift_voucher')) {
            $this->setData('gift_voucher', Mage::getModel('giftvoucher/giftvoucher')->load($this->getRequest()->getParam('id'))
            );
        }
        return $this->getData('gift_voucher');
    }

    public function getGiftVouchers() {
        if (!$this->hasData('gift_vouchers')) {
            $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
            if(!is_array($giftvoucherIds)){
                $giftvoucherIds=  explode(',', $giftvoucherIds);
            }
            $giftvouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()
                    ->addFieldToFilter('giftvoucher_id', array(
                'in' => $giftvoucherIds,
            ));
            $this->setData('gift_vouchers', $giftvouchers);
        }
        return $this->getData('gift_vouchers');
    }

    public function getGiftcardTemplate($template_id) {
        $templates = Mage::getModel('giftvoucher/gifttemplate')->load($template_id);
        return $templates;
    }

}
