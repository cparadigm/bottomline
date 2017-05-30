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

class Delete extends \Solwin\Applycoupon\Controller\Adminhtml\Couponcode
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('couponcode_id');
        if ($id) {
            $ruleName = "";
            try {
                /** @var \Solwin\Applycoupon\Model\Couponcode $couponcode */
                $couponcode = $this->_couponcodeFactory->create();
                $couponcode->load($id);
                $ruleName = $couponcode->getRule_name();
                $couponcode->delete();
                $this->messageManager
                        ->addSuccess(__('The Couponcode has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_solwin_applycoupon_couponcode_on_delete',
                    ['rule_name' => $ruleName, 'status' => 'success']
                );
                $resultRedirect->setPath('solwin_applycoupon/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_solwin_applycoupon_couponcode_on_delete',
                    ['rule_name' => $ruleName, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath(
                        'solwin_applycoupon/*/edit', ['couponcode_id' => $id]
                        );
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager
                ->addError(__('Couponcode to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('solwin_applycoupon/*/');
        return $resultRedirect;
    }
}