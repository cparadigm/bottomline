<?php
/**
 * @package
 * @author Stefan richter (richter@aromicon.com)
 * @license aromicon gmbh 2013
 */
class Aromicon_Gua_Block_Gua extends Mage_Core_Block_Template
{
    protected function _getAccountId()
    {
        return Mage::getStoreConfig('aromicon_gua/general/account_id');
    }

    protected function _isAnonymizeIp()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/general/anonymize_ip') ? 'true' : 'false';
    }

    public function isActive()
    {
        if(Mage::getStoreConfigFlag('aromicon_gua/general/enable')
            && Mage::getStoreConfig('aromicon_gua/general/add_to') == $this->getParentBlock()->getNameInLayout()){
                return true;
        }
        return false;
    }

    public function _isEcommerce()
    {
        Mage::log($this->getRequest()->getPathInfo());
        if(Mage::getStoreConfigFlag('aromicon_gua/ecommerce/enable')
            && $this->getRequest()->getPathInfo() == '/checkout/onepage/success/'){
                return true;
        }
        return false;
    }

    public function getOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        return Mage::getModel('sales/order')->load($orderId);
    }

    public function getTransactionIdField()
    {
        return Mage::getStoreConfig('aromicon_gua/ecommerce/transaction_id') != false ? Mage::getStoreConfig('aromicon_gua/ecommerce/transaction_id') : 'entity_id';
    }




}