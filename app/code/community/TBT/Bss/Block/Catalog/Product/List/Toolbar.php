<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

class TBT_Bss_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{

    /**
     * Init Toolbar
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }
    /**
     * Retrieve current order field
     *
     * @return string
     */
    public function getCurrentOrder()
    {
        $orders = $this->getAvailableOrders();
        $order = $this->getRequest()->getParam($this->getOrderVarName());
        if ($order && isset($orders[$order])) {
            Mage::getSingleton('catalog/session')->setSortOrder($order);
        }
        else {
            $order = Mage::getSingleton('catalog/session')->getSortOrder();
        }

        // validate session value
        if (!isset($orders[$order])) {
            $order = $this->_orderField;
        }

        // validate has order value
        if (!isset($orders[$order])) {
            $keys = array_keys($orders);
            $order = $keys[0];
        }

        return $order;
    }

    /**
     * Retrieve current direction
     *
     * @return string
     */
    public function getCurrentDirection()
       {
        //@nelkaake Tuesday April 27, 2010 :
        $directions = array('desc', 'asc');
        $dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()));
        if ($dir && in_array($dir, $directions)) {
            Mage::getSingleton('catalog/session')->setSortDirection($dir);
        }
        else {
            $dir = Mage::getSingleton('catalog/session')->getSortDirection();
            //@nelkaake Tuesday April 27, 2010 :
            if($this->getCurrentOrder() == 'relevance') {
                $dir = empty($dir) ? 'desc' : $dir;
            }
        }

        // validate direction
        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }

        return $dir;
    }

    /**
     * Set default Order field
     *
     * @param string $field
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function setDefaultOrder($field)
    {
        if (isset($this->_availableOrder[$field])) {
            $this->_orderField = $field;
        }
        return $this;
    }

    /**
     * Set default sort direction
     *
     * @param string $dir
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function setDefaultDirection($dir)
    {
        if (in_array(strtolower($dir), array('asc', 'desc'))) {
            $this->_direction = strtolower($dir);
        }
        return $this;
    }

    /**
     * Retrieve Pager URL
     *
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function getOrderUrl($order, $direction)
    {
        $arrow = 0;

        if (is_null($order)) {

            $order = $this->getCurrentOrder() ? $this->getCurrentOrder() : $this->_availableOrder[0];
            $arrow = 1;

        }

        $get_arrow = $this->getRequest()->getParam("arrow");

        if ($arrow == 1) {

            return $this->getPagerUrl(array(
                        $this->getOrderVarName() => $order,
                        $this->getDirectionVarName() => $direction,
                        $this->getPageVarName() => null,
                        "arrow" => 1
                    ));

        }

        if ( $get_arrow != 1 && $order == "relevance") {
            $direction = "desc";
        }

        return $this->getPagerUrl(array(
            $this->getOrderVarName()=>$order,
            $this->getDirectionVarName()=>$direction,
            $this->getPageVarName() => null
        ));

    }
}
