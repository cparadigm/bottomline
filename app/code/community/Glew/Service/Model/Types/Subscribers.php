<?php

class Glew_Service_Model_Types_Subscribers
{
    public $subscribers = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        if ($id) {
            $subscribers = Mage::getModel('newsletter/subscriber')->getCollection()
                ->addFieldToFilter('main_table.subscriber_id', $id);
        } else {
            $subscribers = Mage::getModel('newsletter/subscriber')->getCollection();
        }
        $subscribers->addFilter('store_id', 'store_id = '.$helper->getStore()->getStoreId(), 'string');
        $subscribers->setOrder('subscriber_id', $sortDir);
        $subscribers->setCurPage($pageNum);
        $subscribers->setPageSize($pageSize);

        if ($subscribers->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($subscribers as $subscriber) {
            $model = Mage::getModel('glew/types_subscriber')->parse($subscriber);
            if ($model) {
                $this->subscribers[] = $model;
            }
        }

        return $this;
    }
}
