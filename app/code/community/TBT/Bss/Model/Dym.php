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

/**
 *
 * @category   TBT
 * @author     WDCA Team <contact@wdca.ca>
 */
class TBT_Bss_Model_Dym extends Mage_Core_Model_Abstract
{
    public function _construct() {
        $this->_init('bss/dym');
        parent::_construct();
    }

    /**
     *
     * @param string $query
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getSuggestedProducts($query) {
        //@nelkaake -a 16/11/10: match up our algorithms
        $result_ids = array();
        $result_ids = $this->getResource()->findBySku($query);
        $result_ids += $this->getResource()->findByMergedName($query);
        $result_ids += $this->getResource()->findBySoundex($query);

        //@nelkaake -a 16/11/10: Create a resource collection
        if(empty($result_ids)) {
            $results = Mage::getResourceModel('catalog/product_collection');
        } else {
            $results = Mage::getModel('catalog/product')
                ->getCollection()
                ->addIdFilter($result_ids)
                ->addAttributeToSelect('name');
        }

        $num_results_to_show = (int)Mage::getStoreConfig('bss/dym/num_matches');
        $results->setPageSize($num_results_to_show)->setCurPage(1);

        //@nelkaake -a 16/11/10: Run the visibility assurance decorators
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($results);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($results);

        //@nelkaake -a 16/11/10: Order by the most closest match in the collection.
        foreach($result_ids as $rid) {
            $results->getSelect()->order("(e.entity_id = {$rid}) DESC");
        }

        return $results;
    }


    /**
     *
     * @param string $query
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getSuggestedPhrase($query) {
        //@nelkaake -a 16/11/10: match up our algorithms
        $new_search_phrase = $this->getResource()->findPhraseBySoundex($query);

        return $new_search_phrase;
    }


}