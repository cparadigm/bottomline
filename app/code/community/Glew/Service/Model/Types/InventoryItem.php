<?php

class Glew_Service_Model_Types_InventoryItem
{
    public function parse($product)
    {
        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
        $this->id = $stock->getItemId();
        $this->product_id = $product->getId();
        $this->qty = $stock->getQty();
        $this->price = $product->getPrice();
        $this->cost = $product->getCost();

        return $this;
    }
}
