<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Resource_Category extends Mage_Catalog_Model_Resource_Eav_Mysql4_Category
{

    /**
     * Remove all / only dynamic products from category
     *
     * @param  object $category
     * @return \ProxiBlue_DynCatProd_Model_Resource_Category
     */
    public function removeDynamicProducts($category)
    {
        $id = $category->getId();
        $adapter = $this->_getWriteAdapter();
        $cond = array(
            'is_dynamic = 1',
            'category_id=?' => $id
        );
        $adapter->delete($this->_categoryProductTable, $cond);

        return $this;
    }

    /**
     * Mark dynamic products as dynamic in link table
     *
     * @param  object $category
     * @return \ProxiBlue_DynCatProd_Model_Resource_Category
     */
    public function markProductsAsDynamic($category)
    {
        $id = $category->getId();
        $productIds = $category->getDynamicProducts();
        if (is_array($productIds)) {
            $adapter = $this->_getWriteAdapter();
            $where = array(
                'product_id IN(?)' => array_keys($productIds),
                'category_id=?' => $id
            );
            $bind = array('is_dynamic' => 1);
            $adapter->update($this->_categoryProductTable, $bind, $where);
        }

        return $this;
    }

    /**
     * get Current Dynamic products for category
     *
     * @param  object $category
     * @return array  $currentDynamicIds
     */
    public function getCurrentDynamicProducts($category)
    {
        $id = $category->getId();
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()->from($this->_categoryProductTable)
            ->where("is_dynamic = ?", 1)
            ->where("category_id = ?", $id);
        $result = $adapter->fetchAll($select);
        array_walk($result, array($this, 'processCurrentIdsCallback'));
        $flatten = $this->flattenArray($result);

        return $flatten;
    }

    /**
     * get Current products for category
     *
     * @param  object $category
     * @return array  $currentDynamicIds
     */
    public function getCurrentProducts($category)
    {
        $id = $category->getId();
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()->from($this->_categoryProductTable)
                //->where("is_dynamic = ?", 1)
            ->where("category_id = ?", $id);
        $result = $adapter->fetchAll($select);
        array_walk($result, array($this, 'processCurrentIdsCallback'));
        $flatten = $this->flattenArray($result);

        return $flatten;
    }

    public function processCurrentIdsCallback(&$result)
    {
        $result = array($result['product_id'] => $result['position']);
    }

    private function flattenArray($array)
    {
        $return = array();
        foreach ($array as $key => $value) {
            $productId = key($value);
            $return[$productId] = $value[$productId];
        }

        return $return;
    }

}
