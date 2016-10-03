<?php

class Glew_Service_Model_Types_ProductAlerts
{
    public $alerts = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        if ($id) {
            $alerts = Mage::getModel('productalert/stock')->getCollection()
                ->addFilter('alert_stock_id', $id);
        } elseif ($startDate && $endDate) {
            $condition = "add_date BETWEEN '".date('Y-m-d 00:00:00', strtotime($startDate))."' AND '".date('Y-m-d 23:59:59', strtotime($endDate))."'";
            $alerts = Mage::getModel('productalert/stock')->getCollection()
                ->addFilter('add_date', $condition, 'string');
        } else {
            $alerts = Mage::getModel('productalert/stock')->getCollection();
        }
        $alerts->addFilter('website_id', 'website_id = '.$helper->getStore()->getWebsiteId(), 'string');
        $alerts->setOrder('add_date', $sortDir);
        $alerts->setCurPage($pageNum);
        $alerts->setPageSize($pageSize);

        if ($alerts->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($alerts as $alert) {
            $model = Mage::getModel('glew/types_productAlert')->parse($alert);
            if ($model) {
                $this->alerts[] = $model;
            }
        }

        return $this;
    }
}
