<?php

class Boardroom_Pcd_Model_Observer extends Mage_Core_Model_Abstract {

    public function afterAddressSave($observer) {
        if (!Mage::app()->getFrontController()->getRequest()->getParam('login')) {
            $defaultBillingId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
            $currentAddress = $observer->getCustomerAddress();
            if ($defaultBillingId==$currentAddress->getId()) {
                Mage::helper('boardroom_pcd/pcd')->updateAddress($currentAddress);
            }
        }
    }

}