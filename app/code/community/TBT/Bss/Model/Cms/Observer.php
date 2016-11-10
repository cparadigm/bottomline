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

class TBT_Bss_Model_Cms_Observer extends Varien_Object
{
    /**
     * Prepare query for save results
     *
     * @see Mage_CatalogSearch_Model_Query::prepare()
     * @param Varien_Event_Observer $observer
     *
     * @return TBT_Bss_Model_Cms_Observer
     */
    public function prepareQuery(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        if (! $event) {
            return $this;
        }

        $query = $event->getCatalogsearchQuery();

        if (! $query) {
            return $this;
        }

        if (! $query->getId()) {
            $query->setIsCmsProcessed(0);
        }

        return $this;
    }

}