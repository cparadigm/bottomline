<?php

class Glew_Service_Model_Types_Orders
{
    public $orders = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;

        if ($id) {
            $collection = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('main_table.increment_id', $id);
        } elseif ($startDate && $endDate) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));
            $collection = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $collection = Mage::getModel('sales/order')->getCollection();
        }
        $collection->addAttributeToFilter('main_table.store_id', $helper->getStore()->getStoreId());
        $collection->addAttributeToSort('created_at', $sortDir);
        $collection->setCurPage($pageNum);
        $collection->setPageSize($pageSize);

        if ($collection->getLastPageNumber() < $pageNum) {
            return $this;
        }
        foreach ($collection as $order) {
            if ($order && $order->getId()) {
                $model = Mage::getModel('glew/types_order')->parse($order);
                if ($model) {
                    $this->orders[] = $model;
                }
            }
        }

        return $this;
    }
}
