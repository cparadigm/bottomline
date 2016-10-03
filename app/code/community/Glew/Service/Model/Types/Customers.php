<?php

class Glew_Service_Model_Types_Customers
{
    public $customers = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        if ($id) {
            $collection = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToFilter('entity_id', $id);
        } elseif ($startDate && $endDate) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));

            $collection = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $collection = Mage::getModel('customer/customer')->getCollection();
        }
        /*$collection->addAttributeToFilter('store_id', ['in' => [$helper->getStore()->getStoreId(), Mage::getModel('cor
e/store')->load('admin', 'code')->getId()]]);*/
        $collection->addAttributeToFilter('store_id', $helper->getStore()->getStoreId());
        $collection->setOrder('created_at', $sortDir);
        $collection->setCurPage($pageNum);
        $collection->setPageSize($pageSize);

        if ($collection->getLastPageNumber() < $pageNum) {
            return $this;
        }
        foreach ($collection as $customer) {
            $customer = Mage::getModel('customer/customer')->load($customer->getId());
            if ($customer && $customer->getId()) {
                $model = Mage::getModel('glew/types_customer')->parse($customer);
                if ($model) {
                    $this->customers[] = $model;
                }
            }
        }

        return $this;
    }
}
