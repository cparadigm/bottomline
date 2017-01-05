<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, Holger Brandt IT Solutions not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by Holger Brandt IT Solutions, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time Holger Brandt IT Solutions spent during the support process.
 * Holger Brandt IT Solutions does not guarantee compatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

class TBT_Bss_Model_CatalogSearch_Fulltext extends Mage_CatalogSearch_Model_Fulltext
{

    /**
     * Prepare results for query
     *
     * @param Mage_CatalogSearch_Model_Query $query
     * @return Mage_CatalogSearch_Model_Fulltext
     */
    public function prepareResult($query = null)
    {

        if (!$query instanceof Mage_CatalogSearch_Model_Query) {
            $query = Mage::helper('catalogsearch')->getQuery();
        }
        $queryText = Mage::helper('catalogsearch')->getQueryText();

        //@nelkaake Don't deal with it if it's empty because it should never be emtpy (the interface should prevent empty searches).
        if(empty($queryText) || $queryText == "") {
            return parent::prepareResult($query);
        }

        if ($query->getSynonymFor()) {
            $queryText = $query->getSynonymFor();
        }
        $queryText = $this->getBestSynonym($queryText);

        $this->getResource()->prepareResult($this, $queryText, $query);
        return $this;
    }

    /**
     * @nelkaake Added on Saturday July 17, 2010:
     * @return string
     */
    public function getBestSynonym($queryText) {
        $queryText = Mage::getModel('bss/catalogSearch_query_cleaner')->getCleanedQuery($queryText);
        return $queryText;

    }

    /**
     * @deprecated
     *
     * @param mixed $storeId
     * @param mixed $productIds
     * @return string
     */
    public function rebuildPhoneticIndexes($storeId=null, $productIds=null) {

        return $this;
    }


    /**
     * Regenerate all Stores index
     *
     * Examples:
     * (null, null) => Regenerate index for all stores
     * (1, null)    => Regenerate index for store Id=1
     * (1, 2)       => Regenerate index for product Id=2 and its store view Id=1
     * (null, 2)    => Regenerate index for all store views of product Id=2
     *
     * @param int $storeId Store View Id
     * @param int $productId Product Entity Id
     * @return Mage_CatalogSearch_Model_Fulltext
     */
    public function rebuildIndex($storeId = null, $productId = null)
    {
        parent::rebuildIndex($storeId, $productId);
        //@nelkaake -a 16/11/10: Only for Mage 1.4 and up
        // do we still need this? causes indexes to be built 2 times in some cases
//        $this->_getIndexer()->rebuildIndex($storeId, $productId);
        return $this;
    }


    /**
     * Retrieve Fulltext Search instance
     *
     * @return TBT_Bss_Model_Fulltext
     */
    protected function _getIndexer()
    {
        return Mage::getSingleton('bss/fulltext');
    }
}