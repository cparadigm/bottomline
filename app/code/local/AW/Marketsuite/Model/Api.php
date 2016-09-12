<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Marketsuite
 * @version    2.1.1
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Marketsuite_Model_Api
{
    /**
     * Check $object via MSS rule
     *
     * @param Mage_Sales_Model_Order | Mage_Customer_Model_Customer | Mage_Sales_Model_Quote $object
     * @param int                                                                            $ruleId
     *
     * @return boolean
     */
    public function checkRule($object, $ruleId)
    {
        $ruleModel = $this->getRule($ruleId);
        if (!$ruleModel->getId()) {
            return false;
        }
        if (!$ruleModel->getIsActive()) {
            return false;
        }
        return $ruleModel->validate($object);
    }

    public function getRule($ruleId)
    {
        return Mage::getModel('marketsuite/filter')->load($ruleId);
    }

    /**
     * Return customers collection which satisfy MSS rule requirements
     *
     * @param int $ruleId
     *
     * @return Mage_Customer_Model_Entity_Customer_Collection
     */
    public function exportCustomers($ruleId)
    {
        return Mage::getModel('marketsuite/filter')->load($ruleId)->exportCustomers();
    }

    /**
     * Return orders collection which satisfy MSS rule requirements
     *
     * @param int $ruleId
     *
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    public function exportOrders($ruleId = null)
    {
        return Mage::getModel('marketsuite/filter')->load($ruleId)->exportOrders();
    }

    /**
     * Return active MSS rules as collection
     *
     * @return AW_Marketsuite_Model_Resource_Filter_Collection
     */
    public function getRuleCollection()
    {
        return Mage::getResourceModel('marketsuite/filter_collection')->addIsActiveFilter();
    }

    /**
     * Check is MSS rule (filter) active
     *
     * @param $ruleId
     * @return bool
     */
    public function isRuleActive($ruleId) {
        $rule = Mage::getModel('marketsuite/filter')->load($ruleId);
        if (
            $rule !== null
            && $rule->getIsActive()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get rule IDs for specified customer from index table
     *
     * @param $customerId
     * @return array of rule ids
     */
    public function getRuleIndexByCustomerId($customerId) {
        $index = Mage::getResourceModel('marketsuite/index_customer');
        $return = $index->getRulesArrayByCustomerId($customerId);
        if (!$return) {
            $return = array();
        }
        return $return;
    }

    /**
     * Get customer IDs for specified rule from index table
     *
     * @param $ruleId
     * @return array of customer ids
     */
    public function getCustomerIndexByRuleId($ruleId) {
        $index = Mage::getResourceModel('marketsuite/index_customer');
        $return = $index->getCustomersArrayByRuleId($ruleId);
        if (!$return) {
            $return = array();
        }
        return $return;
    }
}
