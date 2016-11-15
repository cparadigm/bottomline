<?php
class Icube_Flagorder_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function sendNpOrderEmail($orderId) {
		$order = Mage::getModel("sales/order")->load($orderId);
        
        // send email
        $store = $order->getStore();
        $template_id = 'np_order_email';

        $customer   = Mage::getModel('customer/customer')->load($order->getCustomerId());
        $customerName = $customer->getName();

        //Getting the Sender Name & Email.
        $adminName = Mage::getStoreConfig('trans_email/ident_general/name');
        $adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');


        // try to load from admin - transactional email template
        $emailTemplate  = Mage::getModel('core/email_template')->loadByCode('NP Order Notification');
        if(!$emailTemplate->getId()) {
    		// Load our template by template_id
	    	$emailTemplate  = Mage::getModel('core/email_template')->loadDefault($template_id);
	    	$emailTemplate->setTemplateSubject('NP Order Notification');
	    }

        $emailTemplate->setSenderName($adminName);
        $emailTemplate->setSenderEmail($adminEmail);

        $emailTemplateVariables = array(
                    'store'         => $store,
                    'order'         => $order,
                    'customer'      => $customer,
                    'store_name'    => $order->getStoreName(),
                    'store_url' 	=> Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)
                    );

        $emailTemplate->send($adminEmail, $adminName, $emailTemplateVariables);

        return;

	}

}
	 