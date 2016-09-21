<?php

class Glew_Service_Model_Types_OrderItem
{
    public function parse($orderItem)
    {
        $this->order_item_id = $orderItem->getId();
        $this->order_id = $orderItem->getOrderId();
        $this->created_at = $orderItem->getCreatedAt();
        $this->updated_at = $orderItem->getUpdatedAt();
        $this->weight = $orderItem->getWeight();
        $this->sku = $orderItem->getSku();
        $this->product_id = $orderItem->getProductId();
        $this->name = $orderItem->getName();
        $this->description = $orderItem->getDescription();
        $this->visibility = '';
        $this->brand = '';
        $this->website_names = '';
        $this->store = $orderItem->getOrder()->getStore()->getCode();

        $this->qty_ordered = (int) $orderItem->getQtyOrdered();
        $this->qty_refunded = (int) $orderItem->getQtyRefunded();
        $this->qty_shipped = (int) $orderItem->getQtyShipped();
        $this->qty_backordered = (int) $orderItem->getQtyBackordered();

        $this->price = round($orderItem->getPrice(), 2);
        $this->original_price = round($orderItem->getOriginalPrice(), 2);
        $this->cost = round($orderItem->getCost(), 2);
        $this->row_total = round($orderItem->getRowTotal(), 2);
        $this->tax_percent = round($orderItem->getTaxPercent(), 2);
        $this->tax_amount = round($orderItem->getTaxAmount(), 2);
        $this->discount_percent = round($orderItem->getDiscountPercent(), 2);
        $this->discount_amount = round($orderItem->getDiscountAmount(), 2);

        $this->weight = round($orderItem->getWeight(), 2);
        $this->row_weight = round($orderItem->getRowWeight(), 2);
        $this->additional_data = $orderItem->getAdditionalData();

        return $this;
    }

    private function _getSelectAttributeLabel($attrCode, $value)
    {
        try {
            $api = Mage::getSingleton('catalog/product_attribute_api');
            $info = $api->info($attrCode);
            if ($info) {
                if (isset($info['options'])) {
                    foreach ($info['options'] as $option) {
                        $label = str_ireplace(' ', '', $option['label']);
                        if ($option['value'] == $value) {
                            return $label;
                        }
                    }
                }
            }

            return '';
        } catch (Exception $ex) {
            $helper = Mage::helper('glew');
            $helper->ex($ex, 'process');
        }
    }

    protected function _getProductCategories($product)
    {
        $maxlevel = 0;
        foreach ($product->getCategoryIds() as $k => $categoryId) {
            $category = Mage::getModel('catalog/category')->
            load($categoryId);
            $level = $category->getLevel();

            if ($category->getLevel() > $maxlevel) {
                while ($level >= 2) {
                    $categoryK = 'category'.($level - 1);
                    if ($level <= 4) {
                        $this->$categoryK = $category->getName();
                    }
                    $category = Mage::getModel('catalog/category')->load($category->parent_id);
                    $level = $category->getLevel();
                }
            }
        }
    }
}
