<?php

class Glew_Service_Model_Types_Categories
{
    public $categories = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        $rootCategoryId = $helper->getStore()->getRootCategoryId();
        $rootpath = Mage::getModel('catalog/category')
            ->setStoreId($helper->getStore()->getStoreId())
            ->load($rootCategoryId)
            ->getPath();

        if ($id) {
            $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('entity_id', $id);
        } elseif ($startDate && $endDate) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));

            $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $categories = Mage::getModel('catalog/category')->getCollection();
        }
        $categories->addAttributeToFilter('path', array('like' => $rootpath.'/'.'%'));
        $categories->setOrder('created_at', $sortDir);
        $categories->setCurPage($pageNum);
        $categories->setPageSize($pageSize);

        if ($categories->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($categories as $category) {
            $model = Mage::getModel('glew/types_category')->parse($category);
            if ($model) {
                $this->categories[] = $model;
            }
        }

        return $this;
    }
}
