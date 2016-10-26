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
 * @copyright  Copyright (c) 2012 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
 */

class TBT_Bss_Model_Core_Email_Template_Filter extends Mage_Core_Model_Email_Template_Filter
{
    /**
     * Default design area for emulation
     */
    const DEFAULT_DESIGN_AREA = 'frontend';

    /**
     * Configuration of desing package for template
     *
     * @var Varien_Object
     */
    protected $_designConfig;

    /**
     * Initial environment information
     *
     * @var Varien_Object|null
     */
    protected $_initialEnvironmentInfo = null;

    public function process($content)
    {
        $this->_applyDesignConfig();

        try {
            $processedResult = $this->filter($content);
        } catch (Exception $e)   {
            $this->_cancelDesignConfig();
            throw $e;
        }
        $this->_cancelDesignConfig();

        return $processedResult;
    }

    /**
     * Initialize design information for email template and subject processing
     *
     * @param   array $config
     * @return  TBT_Bss_Model_Core_Email_Template_Filter
     */
    public function setDesignConfig(array $config)
    {
        $this->getDesignConfig()->setData($config);
        return $this;
    }

    /**
     * Get design configuration data
     *
     * @return Varien_Object
     */
    public function getDesignConfig()
    {
        if(is_null($this->_designConfig)) {
            $this->_designConfig = new Varien_Object();
        }

        return $this->_designConfig;
    }

    /**
     * Applying of design config
     *
     * @return TBT_Bss_Model_Core_Email_Template_Filter
     */
    protected function _applyDesignConfig()
    {
        $designConfig = $this->getDesignConfig();
        $design = Mage::getDesign();
        if ($designConfig) {

            $designConfig
                ->setOldStore($design->getStore())
                ->setOldArea($design->getArea());


            if ($area = $designConfig->getArea()) {
                $design->setArea($area);
            }

            $store = $designConfig->getStore();

            if ($store) {
                $storeId = is_object($store) ? $store->getId() : $store;
                // Current store needs to be changed right before locale change and after design change
                Mage::app()->setCurrentStore($storeId);

                $design->setStore($store);
                $design->setTheme('');
                $design->setPackageName('');

                Mage::app()->getLocale()->emulate($storeId);
            }
        }

        return $this;
    }

    /**
     * Revert design settings to previous
     *
     * @return TBT_Bss_Model_Core_Email_Template_Filter
     */
    protected function _cancelDesignConfig()
    {
        $designConfig = $this->getDesignConfig();
        $design = Mage::getDesign();
        if ($designConfig) {
            if ($area = $designConfig->getOldArea()) {
                $design->setArea($area);
            }

            if ($store = $designConfig->getOldStore()) {
                // Current store needs to be changed right before locale change and after design change
                Mage::app()->setCurrentStore($store->getId());
                $design->setStore($store);
                $design->setTheme('');
                $design->setPackageName('');
            }
        }

        Mage::app()->getLocale()->revert();

        return $this;
    }
}