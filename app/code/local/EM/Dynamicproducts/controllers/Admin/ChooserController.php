<?php
class EM_Dynamicproducts_Admin_ChooserController extends Mage_Adminhtml_Controller_Action
{
    public function chooserAction()
    {
        $this->getResponse()->setBody(
            $this->_getCategoryTreeBlock()->toHtml()
        );
    }

   
    public function categoriesJsonAction()
    {
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if ($category->getId()) {
                Mage::register('category', $category);
                Mage::register('current_category', $category);
            }
            $this->getResponse()->setBody(
                $this->_getCategoryTreeBlock()->getTreeJson($category)
            );
        }
    }
    
    protected function _getCategoryTreeBlock()
    {
        return $this->getLayout()->createBlock('dynamicproducts/chooser', '', array(
            'id' => $this->getRequest()->getParam('uniq_id'),
            'use_massaction' => false,//$this->getRequest()->getParam('use_massaction', false)
        ));
    }
}