<?php

/**
 * Solwin Infotech
 * Solwin Discount Coupon Code Link Extension
 *
 * @category   Solwin
 * @package    Solwin_Applycoupon
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */

namespace Solwin\Applycoupon\Controller\Adminhtml\Couponcode;

use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

class Mailcouponcode extends \Magento\Framework\App\Action\Action {

    const XML_PATH_EMAIL_TEMPLATE = 'applycouponsection/emailopt/email_template';
    const XML_PATH_EMAIL_SENDER = 'applycouponsection/emailopt/emailsender';

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {
        $name = $this->getRequest()->getPost('name');
        $email = $this->getRequest()->getPost('email');
        $subject = $this->getRequest()->getPost('subject');
        $comment = $this->getRequest()->getPost('comment');        
        $data = ['name' => $name, 'email' => $email, 'subject' => $subject, 'comment' => $comment];
        
        try {

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($data);
            
            $error = false;

            if (!\Zend_Validate::is(trim($name), 'NotEmpty')) {
                $error = true;
            }

            if (!\Zend_Validate::is(trim($email), 'NotEmpty')) {
                $error = true;
            }

            if (!\Zend_Validate::is(trim($subject), 'NotEmpty')) {
                $error = true;
            }

            if (!\Zend_Validate::is(trim($comment), 'NotEmpty')) {
                $error = true;
            }

            if ($error) {
                throw new \Exception();
            }

            // send mail to recipients
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder->setTemplateIdentifier(
                            $this->_scopeConfig
                            ->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope)
                    )->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                                'store' => $this->_storeManager->getStore()->getId(),
                            ]
                    )->setTemplateVars(['data' => $postObject])
                    ->setFrom($this->_scopeConfig
                            ->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                    ->addTo($email)
                    ->getTransport();

            $transport->sendMessage();
            $this->_inlineTranslation->resume();
            echo 'success';
        } catch (\Exception $e) {
            $this->_inlineTranslation->resume();
            echo 'error';
        }
    }

}
