<?php

class Glew_Service_Model_Types_AbandonedCarts
{
    public $carts = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        if($id) {
            $collection = Mage::getResourceModel('reports/quote_collection')
                ->addFieldToFilter('main_table.entity_id', $id);
        } elseif ($startDate && $endDate) {
            $filter = array(
                'datetime' => 1,
                'locale' => 'en_US',
                'from' => new Zend_Date(strtotime($startDate), Zend_Date::TIMESTAMP),
                'to' => new Zend_Date(strtotime($endDate . ' + 1 day -1 second'), Zend_Date::TIMESTAMP),
            );

            $collection = Mage::getResourceModel('reports/quote_collection')
                ->addFieldToFilter('main_table.'.$filterBy, $filter);
        } else {
            $collection = Mage::getResourceModel('reports/quote_collection');
        }
        $collection->addFieldToFilter('main_table.store_id', $helper->getStore()->getStoreId());
        $collection->prepareForAbandonedReport(array($helper->getStore()->getWebsiteId()));
        $collection->setOrder('created_at', $sortDir);
        $collection->setCurPage($pageNum);
        $collection->setPageSize($pageSize);

        if ($collection->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($collection as $cart) {
            if ($cart) {
                $model = Mage::getModel('glew/types_abandonedCart')->parse($cart);
                if ($model) {
                    $this->carts[] = $model;
                }
            }
        }

        return $this;
    }
}
