<?php

class Glew_Service_Model_Types_Inventory
{
    public $inventory = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        if ($id) {
            $inventory = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', $id);
        } else {
            $inventory = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        }
        $inventory->setOrder('entity_id', $sortDir);
        $inventory->setCurPage($pageNum);
        $inventory->setPageSize($pageSize);

        if ($inventory->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($inventory as $product) {
            $model = Mage::getModel('glew/types_inventoryItem')->parse($product);
            if ($model) {
                $this->inventory[] = $model;
            }
        }

        return $this;
    }
}
