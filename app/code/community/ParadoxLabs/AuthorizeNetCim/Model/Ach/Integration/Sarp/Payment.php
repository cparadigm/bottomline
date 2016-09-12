<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 * 
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 * 
 * Want to customize or need help with your store?
 *  Phone: 717-431-3330
 *  Email: sales@paradoxlabs.com
 *
 * @category	ParadoxLabs
 * @package		AuthorizeNetCim
 * @author		Ryan Hoerr <magento@paradoxlabs.com>
 * @license		http://store.paradoxlabs.com/license.html
 */

/**
 * Derived from SARP model: AW_Sarp_Model_Payment_Method_Authorizenet
 */
class ParadoxLabs_AuthorizeNetCim_Model_Ach_Integration_Sarp_Payment extends ParadoxLabs_AuthorizeNetCim_Model_Integration_Sarp_Payment
{
	const PAYMENT_METHOD_CODE					= 'authnetcim_ach';
	
	const XML_PATH_AUTHORIZENET_API_LOGIN_ID	= 'payment/authnetcim_ach/login';
	const XML_PATH_AUTHORIZENET_TEST_MODE		= 'payment/authnetcim_ach/test';
	const XML_PATH_AUTHORIZENET_DEBUG			= 'payment/authnetcim_ach/debug';
	const XML_PATH_AUTHORIZENET_TRANSACTION_KEY	= 'payment/authnetcim_ach/trans_key';
	const XML_PATH_AUTHORIZENET_PAYMENT_ACTION	= 'payment/authnetcim_ach/payment_action';
	const XML_PATH_AUTHORIZENET_ORDER_STATUS	= 'payment/authnetcim_ach/order_status';
	const XML_PATH_AUTHORIZENET_SOAP_TEST		= 'payment/authnetcim_ach/test';
	
	const WEB_SERVICE_MODEL						= 'sarp/web_service_client_authnetcim';
}
