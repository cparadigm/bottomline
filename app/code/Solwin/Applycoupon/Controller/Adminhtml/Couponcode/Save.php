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

class Save extends \Solwin\Applycoupon\Controller\Adminhtml\Couponcode
{

    /**
     * constructor
     * 
     * @param \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct(
                $couponcodeFactory,
                $registry,
                $context
                );
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('couponcode');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $couponcode = $this->_initCouponcode();
            $couponcode->setData($data);
            $this->_eventManager->dispatch(
                'solwin_applycoupon_couponcode_prepare_save',
                [
                    'couponcode' => $couponcode,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $couponcode->save();
                $this->messageManager
                        ->addSuccess(__('The Couponcode has been saved.'));
                $this->_session
                        ->setSolwinApplycouponCouponcodeData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'solwin_applycoupon/*/edit',
                        [
                            'couponcode_id' => $couponcode->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('solwin_applycoupon/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went '
                        . 'wrong while saving the Couponcode.'));
            }
            $this->_getSession()->setSolwinApplycouponCouponcodeData($data);
            $resultRedirect->setPath(
                'solwin_applycoupon/*/edit',
                [
                    'couponcode_id' => $couponcode->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('solwin_applycoupon/*/');
        return $resultRedirect;
    }
}