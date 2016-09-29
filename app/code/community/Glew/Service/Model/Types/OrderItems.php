<?php

class Glew_Service_Model_Types_OrderItems
{
    public $orderItems = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $store = $helper->getStore();
        $this->pageNum = $pageNum;

        $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'cost');
        if ($id) {
            $collection = Mage::getModel('sales/order_item')->getCollection()
                ->addAttributeToFilter('main_table.item_id', $id);
        } elseif ($startDate && $endDate) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));

            $collection = Mage::getModel('sales/order_item')->getCollection()
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $collection = Mage::getModel('sales/order_item')->getCollection();
        }
        $collection->addAttributeToFilter('main_table.store_id', $helper->getStore()->getStoreId());
        $resource = Mage::getSingleton('core/resource');
        $catProdEntDecTable = $resource->getTableName('catalog_product_entity_decimal');
        $collection->getSelect()->joinLeft(
            array('cost' => $catProdEntDecTable),
            "main_table.product_id = cost.entity_id AND cost.attribute_id = {$attribute->getId()} AND cost.store_id = {$store->getStoreId()}",
            array('cost' => 'value')
        );
        $collection->setOrder('created_at', $sortDir);
        $collection->setCurPage($pageNum);
        $collection->setPageSize($pageSize);
        if ($collection->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($collection as $orderItem) {
            $continue = true;
            if ($orderItem && $orderItem->getId()) {
                if ($orderItem->getParentItemId()) {
                    foreach ($this->orderItems as $key => $oi) {
                        if ($orderItem->getParentItemId() == $this->orderItems[$key]->order_item_id) {
                            $this->orderItems[$key]->product_id = $orderItem->getProductId();
                            $continue = false;
                        }
                    }
                    if (!$continue) {
                        continue;
                    }
                }
                $model = Mage::getModel('glew/types_orderItem')->parse($orderItem);
                if ($model) {
                    $this->orderItems[] = $model;
                }
            }
        }

        return $this;
    }
}
