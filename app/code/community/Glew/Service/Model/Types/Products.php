<?php

class Glew_Service_Model_Types_Products
{
    public $products = array();
    private $productAttributes = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $startDate = null, $endDate = null, $sortDir, $filterBy, $id)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;
        $this->_getProductAttribtues();
        if( $id ) {
            $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', $id);
        } elseif ($startDate && $endDate) {
            $from = date('Y-m-d 00:00:00', strtotime($startDate));
            $to = date('Y-m-d 23:59:59', strtotime($endDate));

            $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
                ->addAttributeToFilter($filterBy, array('from' => $from, 'to' => $to));
        } else {
            $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        }
        $products->setVisibility(null);
        $products->setStoreId($helper->getStore()->getStoreId());
        $products->setOrder('updated_at', $sortDir);
        $products->setCurPage($pageNum);
        $products->setPageSize($pageSize);

        if ($products->getLastPageNumber() < $pageNum) {
            return $this;
        }

        foreach ($products as $product) {
            $productId = $product->getId();
            $model = Mage::getModel('glew/types_product')->parse($productId, $this->productAttributes);
            if ($model) {
                $model->cross_sell_products = $this->_getCrossSellProducts($product);
                $model->up_sell_products = $this->_getUpSellProducts($product);
                $model->related_products = $this->_getRelatedProducts($product);
                $this->products[] = $model;
            }
        }

        return $this;
    }

    protected function _getProductAttribtues()
    {
        if (!$this->productAttributes) {
            $attributes = Mage::getResourceModel('catalog/product_attribute_collection')->getItems();
            foreach ($attributes as $attribute) {
                if (!$attribute) {
                    continue;
                }
                $this->productAttributes[$attribute->getData('attribute_code')] = $attribute->usesSource();
            }
        }
    }

    protected function _getCrossSellProducts($product)
    {
        $productArray = array();
        $collection = $product->getCrossSellProductCollection();
        if ($collection) {
            foreach ($collection as $item) {
                $productArray[] = $item->getId();
            }
        }

        return $productArray;
    }

    protected function _getUpSellProducts($product)
    {
        $productArray = array();
        $collection = $product->getUpSellProductCollection();
        if ($collection) {
            foreach ($collection as $item) {
                $productArray[] = $item->getId();
            }
        }

        return $productArray;
    }

    protected function _getRelatedProducts($product)
    {
        $productArray = array();
        $collection = $product->getRelatedProductCollection();
        if ($collection) {
            foreach ($collection as $item) {
                $productArray[] = $item->getId();
            }
        }

        return $productArray;
    }
}
