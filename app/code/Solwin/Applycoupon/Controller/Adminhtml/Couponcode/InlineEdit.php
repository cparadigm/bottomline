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

abstract class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * JSON Factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Couponcode Factory
     * 
     * @var \Solwin\Applycoupon\Model\CouponcodeFactory
     */
    protected $_couponcodeFactory;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Solwin\Applycoupon\Model\CouponcodeFactory $couponcodeFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_jsonFactory       = $jsonFactory;
        $this->_couponcodeFactory = $couponcodeFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $couponcodeId) {
            /** @var \Solwin\Applycoupon\Model\Couponcode $couponcode */
            $couponcode = $this->_couponcodeFactory
                    ->create()
                    ->load($couponcodeId);
            try {
                $couponcodeData = $postItems[$couponcodeId];
                $couponcode->addData($couponcodeData);
                $couponcode->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithCouponcodeId(
                        $couponcode, 
                        $e->getMessage()
                        );
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithCouponcodeId(
                        $couponcode, 
                        $e->getMessage()
                        );
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithCouponcodeId(
                    $couponcode,
                    __('Something went wrong while saving the Couponcode.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Couponcode id to error message
     *
     * @param \Solwin\Applycoupon\Model\Couponcode $couponcode
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCouponcodeId(
        \Solwin\Applycoupon\Model\Couponcode $couponcode, 
        $errorText
    ) {
        return '[Couponcode ID: ' . $couponcode->getId() . '] ' . $errorText;
    }
}