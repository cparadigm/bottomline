<?php

// these functions were causing an error after upgrading to 1.9.3.1 when registering
// a new account, however, it was never being used before the upgrade when registering.
// commenting these out in the config.xml file for the event observers.


// include library
include($_SERVER['DOCUMENT_ROOT'] . "/CDH.php");

class Boardroom_Newsletter_Model_Observer extends Mage_Core_Model_Abstract {

    public function updateSendMarketingParam($observer) {
        $sendMarketing = Mage::app()->getFrontController()->getRequest()->getParam('send_marketing');
        $customer = $observer->getCustomer();
        $customer->setData('send_marketing',(int)$sendMarketing);
        $customer->save();
	$this->updateCDH($customer);
	syslog(LOG_INFO, 'updateSendMarketingParam');
    }

    public function updateSendMarketingPost($observer) {
        $customer = $observer->getCustomer();
	syslog(LOG_INFO, 'updateSendMarketingPost');

	$sendMarketingHidden = Mage::app()->getFrontController()->getRequest()->getPost('send_marketing_hidden');
        $account = Mage::app()->getFrontController()->getRequest()->getPost('account');
        if ($sendMarketingHidden) {
            $customer->setData('send_marketing',((int)Mage::app()->getFrontController()->getRequest()->getPost('send_marketing')==1?1:0));
        } else if ($account) {
            $customer->setData('send_marketing',(int)$account['send_marketing']);
        }

	$this->updateCDH($customer);

    }

    public function updateCDH($customer) {
	$esb_url = $_SERVER["ESB_URL"];
	$cdh = new CDH($esb_url);
	$sub_array = array( 'MRKHS'=>$customer->getData('send_marketing')==0?'false':'true');
        $sub_status = $cdh->set_subscriptions($customer->getEmail(), $customer->getId(), $customer->getFirstname(), '', $customer->getLastname(), $sub_array, $session_id) ;
	syslog(LOG_INFO, 'updateCDH:' . $sub_status);
	}


}
