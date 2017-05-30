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

use Magento\Framework\Controller\Result\JsonFactory;

class Edit extends \Solwin\Applycoupon\Controller\Adminhtml\Couponcode
{
    
    /**
     * Page factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     * 
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct(
                $couponcodeFactory,
                $registry,
                $context
                );
    }

    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
                ->isAllowed('Solwin_Applycoupon::couponcode');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|
     * \Magento\Backend\Model\View\Result\Redirect|
     * \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('couponcode_id');
        /** @var \Solwin\Applycoupon\Model\Couponcode $couponcode */
        $couponcode = $this->_initCouponcode();
        /** @var \Magento\Backend\Model\View\Result\Page|
         * \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Solwin_Applycoupon::couponcode');
        $resultPage->getConfig()->getTitle()->set(__('Couponcodes'));
        if ($id) {
            $couponcode->load($id);
            if (!$couponcode->getId()) {
                $this->messageManager
                        ->addError(__('This Couponcode no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'solwin_applycoupon/*/edit',
                    [
                        'couponcode_id' => $couponcode->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $couponcode->getId() 
                ? $couponcode->getRule_name() 
                : __('New Couponcode');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_session
                ->getData(
                        'solwin_applycoupon_couponcode_data', 
                        true
                        );
        if (!empty($data)) {
            $couponcode->setData($data);
        }
        return $resultPage;
    }
}