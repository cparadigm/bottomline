<?php

class Glew_Service_Model_Types_RefundItems
{
    public $refundItems = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        $resource = Mage::getSingleton('core/resource');
        $salesFlatCredMemItem = $resource->getTableName('sales_flat_creditmemo_item');
        if ($startDate && $endDate && !$id) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));

            $refunds = Mage::getResourceModel('sales/order_creditmemo_collection')
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $refunds = Mage::getResourceModel('sales/order_creditmemo_collection');
        }
        $refunds->addAttributeToFilter('main_table.store_id', $helper->getStore()->getStoreId());
        $refunds->getSelect()->join(array('credit_item' => $salesFlatCredMemItem), 'credit_item.parent_id = main_table.entity_id', array('*'));
        if ($id ) {
            $refunds->addAttributeToFilter('credit_item.entity_id', $id);
        }
        $refunds->setOrder('created_at', $sortDir);
        $refunds->setCurPage($pageNum);
        $refunds->setPageSize($pageSize);

        if ($refunds->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($refunds as $refund) {
            $model = Mage::getModel('glew/types_refundItem')->parse($refund);
            if ($model) {
                $this->refundItems[] = $model;
            }
        }

        return $this;
    }
}
