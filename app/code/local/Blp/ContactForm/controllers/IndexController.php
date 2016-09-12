<?php

class Blp_ContactForm_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		//Get current layout state
		$this->loadLayout();

		$block = $this->getLayout()->createBlock(
		'Mage_Core_Block_Template',
		'blp.contactform',
		array(
			'template' => 'blpforms/contact.phtml'
			)
		);

		$this->getLayout()->getBlock('content')->append($block);

		$this->_initLayoutMessages('core/session');

		$this->renderLayout();
	}

	public function sendemailAction()
	{
		//Fetch submited params
		$params = $this->getRequest()->getParams();

		if ($params['g-recaptcha-response']=='') {
			Mage::getSingleton('core/session')->addError('Unable to send email. Please contact bottomlineproductions.com by phone.');
			$this->_redirect('contact-us');
		} else {
			$mail = new Zend_Mail();
			//$mail->setFrom($params['email']);
			$mail->setBodyHtml('<table cellpadding="2" cellspacing="4" border="0">
			<tr><td>First Name</td><td>'.$params['first-name'].'</td></tr>
			<tr><td>Last Name</td><td>'.$params['last-name'].'</td></tr>
			<tr><td>Street Address</td><td>'.$params['street-address'].'</td></tr>
			<tr><td>Street Address2</td><td>'.$params['street-address2'].'</td></tr>
			<tr><td>City</td><td>'.$params['city'].'</td></tr>
			<tr><td>State</td><td>'.$params['state'].'</td></tr>
			<tr><td>Zip Code</td><td>'.$params['zip-code'].'</td></tr>
			
			<tr><td>Email</td><td>'.$params['email'].'</td></tr>
			<tr><td>Phone</td><td>'.$params['telephone'].'</td></tr>
				
			<tr><td>Order No.</td><td>'.$params['order-number'].'</td></tr>
			<tr><td>Comments</td><td>'.$params['comment'].'</td></tr>				
			</table>
			');
		
		
			$recipient = $mail->addTo('customerservice@bottomlinepublications.com');
			$recipient = $mail->addBCC('gemberlingb@gmail.com');
			$recipient = $mail->addBCC('joeromello@gmail.com');
			$mail->setSubject('Main Contact Form: ' . $params['subject']);

			try { 
				$mail->send(); 
				Mage::getSingleton('core/session')->addSuccess('Thank you!  Your inquiry has been submitted to our customer service team. We will do our best to answer your question as soon as possible.');
			}
			catch (Exception $_oException) {
				Mage::getSingleton('core/session')->addError('Unable to send email. Please contact bottomlineproductions.com by phone.');
			}

			$this->_redirect('contact-us');
		}
	}
}
