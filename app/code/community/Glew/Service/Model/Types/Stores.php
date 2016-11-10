<?php

class Glew_Service_Model_Types_Stores
{
    public $stores = array();
    private $pageNum;

    public function load($pageSize, $pageNum)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;

        $stores = Mage::app()->getStores();
        $stores = $helper->paginate($stores, $pageNum, $pageSize);
        foreach ($stores as $store) {
            $model = Mage::getModel('glew/types_store')->parse($store);
            if ($model) {
                $this->stores[] = $model;
            }
        }

        return $this;
    }
}
