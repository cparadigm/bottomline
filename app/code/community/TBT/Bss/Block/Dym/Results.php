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

/**
 *
 * @category   TBT
 * @package    TBT_Bss
 * @author     Holger Brandt IT Solutions <info@brandt-solutions.de>
 */
class TBT_Bss_Block_Dym_Results  extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        parent::_construct();
        $this->setCacheLifetime(86400);
    }

    protected function _prepareLayout()
    {
        $this->setCacheKey($this->_genCacheKey());
        return parent::_prepareLayout();
    }

    /**
     * @nelkaake -a 5/11/10:
     * @return string cache key
     */
    protected function _genCacheKey()
    {
        $key = "bss_result_ajax_". $this->getSearchQuery();
           $key = strtolower(str_replace(' ', '_', $key));
           $key = preg_replace('/[^a-z0-9_]/', '', $key);

        return $key;
    }

    public function getSearchQuery()
    {
        return $this->getRequest()->getParam('q');
    }

    /**
     * This function expects that a product collection was registered some time before this
     * function is called
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getSuggestedProducts()
    {
        return Mage::registry('suggestions');
    }

    /**
     * This function expects that a product collection was registered some time before this
     * function is called
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getSuggestedPhrase()
    {
        if(!$this->hasData('suggested_phrase')) {
            $query = $this->getSearchQuery();
            $suggester = Mage::getModel('bss/dym');
            $new_search_query = $suggester->getSuggestedPhrase($query);
            $this->setSuggestedPhrase($new_search_query);
        }

        return $this->getData('suggested_phrase');
    }
    /**
     * Returns the total numebr of products to show based on the config settings
     *
     * @return int
     */
    public function getNumProdToShow()
    {
        $num_results_to_show = (int)Mage::getStoreConfig('bss/dym/num_matches');
        return $num_results_to_show;
    }

    /**
     * We have to do a special product count for some reason.
     *
     * @return int
     */
    public function getProductCount()
    {
        $products = $this->getSuggestedProducts();
        $total_products = 0;
        foreach($products as $p) {
            if(!$p->getName()) continue;
            $total_products++;
        }

        return $total_products;
    }

    public function getNewQueryUrl()
    {
        $suggested_phrase = $this->getSuggestedPhrase();
        $suggested_phrase_enc = urlencode($suggested_phrase);

        return $this->getUrl('catalogsearch/result/' ) . "?q=" . $suggested_phrase_enc;
    }

}
