<?php

class LinkstureDCCL_ApplyCoupon_Adminhtml_ApplycouponController extends Mage_Adminhtml_Controller_Action
{
  
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('applycoupon/set_time')
                ->_addBreadcrumb('applycoupon Manager','applycoupon Manager');
       return $this;
     }
    public function indexAction()
    {
      $this->_initAction();
      $this->renderLayout();
    }
      
    public function updateTitleAction()
    {
        $fieldId = (int) $this->getRequest()->getParam('id');
        $title = $this->getRequest()->getParam('title');
        if ($fieldId) {
            $model = Mage::getModel('applycoupon/applycoupon')->load($fieldId);
            if ($title) {
              $couponcode=$model->getCouponCode();

              echo $base_url = Mage::app()->getWebsite($model->getWebsites())->getDefaultStore()->getBaseUrl();
              
              $link_with_redirection = $base_url.'applycoupon/index/?code='.$couponcode.'&return_url='.$title;
            }else{
              $link_with_redirection = "First Specify Redirect Url." ;
            }
            $model->setRedirectUrl($title);
            $model->setLinkWithRedirection($link_with_redirection);
            $model->save();
        }
    }
    public function deleteAction()
        {
            if($this->getRequest()->getParam('id') > 0)
            {
              try
              {
                 $obj = Mage::getModel('applycoupon/applycoupon');
                 $result = $obj->load($this->getRequest()->getParam('id'));
                 $obj->delete();
                  if($result)
                  {
                      Mage::getSingleton('core/session')->addSuccess("Delete Successfully");
                  }
                  else
                  {
                      Mage::getSingleton('core/session')->addError("Error in Delete data.");
                  }
                  $this->_redirect('*/*/');
               }
               catch (Exception $e)
                {
                         Mage::getSingleton('adminhtml/session')
                              ->addError($e->getMessage());
                         $this->_redirect('*/*/');
                }
           }
          $this->_redirect('*/*/');
     }

    public function massDeleteAction() {
        $couponIds = $this->getRequest()->getParam('applycoupon');
        if (!is_array($couponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($couponIds as $couponId) {
                    $model = Mage::getModel('applycoupon/applycoupon')->load($couponId);
                    $_helper = Mage::helper('applycoupon');
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($couponIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('applycoupon/applycoupon');  
    }
}