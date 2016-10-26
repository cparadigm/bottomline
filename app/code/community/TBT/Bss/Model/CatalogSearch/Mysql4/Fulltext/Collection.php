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
class TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection extends Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
{
    protected $search_query;
    protected $join_map = array();

    /**
     *
     * @var array(TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract)
     */
    protected $_rel_alg = array();

    protected function _construct()
    {
        parent::_construct();
        try {
            //@nelkaake -a 16/11/10: NOTE THE CAPTIAL "S"!
            $this->_rel_alg = Mage::getSingleton('bss/catalogSearch_algorithm_loader')->getRelevanceAlgorithms();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function getSearchQuery()
    {
        return $this->search_query;
    }

    public function getQuery()
    {
        return $this->getSearchQuery();
    }

    /**
     * Recalls whether or not this collection has joined a particular table.
     *
     * @param string $table_alias
     * @return boolean
     */
    public function hasJoined($table_alias)
    {
        return isset($this->join_map [$table_alias]);
    }

    /**
     * Remembers that this collection has joined a particular table.
     *
     * @param string $table_alias
     * @return $this
     */
    public function rememberJoin($table_alias)
    {
        $this->join_map [$table_alias] = true;
        return $this;
    }

    /**
     * Add search query filter
     *
     * @param   Mage_CatalogSearch_Model_Query $query
     * @return  Mage_CatalogSearch_Model_Mysql4_Search_Collection
     */
    public function addSearchFilter($query)
    {
        try {
            // if query is a synonym for other query use the appropriate query text
            if ($queryText = $this->_getQuery()->getSynonymFor()) {
                $query = Mage::getModel('bss/catalogSearch_query_cleaner')->replaceBasicSpecialChars($queryText);
            } else {
                $query = Mage::getModel('bss/catalogSearch_query_cleaner')->replaceBasicSpecialChars($query);
            }

            $this->search_query = $query;

            //return parent::addSearchFilter($query);
            $has_joined_inner = false;  //@nelkaake Added on Thursday June 17, 2010: used for failsafe procedure
            Mage::getSingleton('catalogsearch/fulltext')->prepareResult();

            $this->rememberJoin('search_result');
            $this->getSelect()->joinInner(
                array('search_result' => $this->getTable('catalogsearch/result')),
                $this->getConnection()->quoteInto(
                    'search_result.product_id=e.entity_id AND search_result.query_id=?',
                    $this->_getQuery()->getId()
                ),
                array() //@nelkaake WDCA : changed to relevance 2
            );

            $has_joined_inner = true; //@nelkaake Added on Thursday June 17, 2010: used for failsafe procedure

            $rel_likes = array();            // this will list all LIKE statements
            $alg_rel_likes = array();       // this will MAP all LIKE statements

            //@nelkaake Go through each algorithm and gather the required SQL
            foreach ($this->_rel_alg as $code => &$alg) {
                if (!isset($alg_rel_likes[$code])) {
                    $alg_rel_likes[$code] = array();
                }
                $alg->appendRelevenceScore( $this, $alg_rel_likes[$code]);
                $rel_likes = array_merge($rel_likes, $alg_rel_likes[$code]);
            }

            $rel_added_statement = implode(" + ", $rel_likes);
            //@nelkaake 5/11/10: Join the product name and add the new relevance field to the list.
            if (sizeof($rel_likes) > 0) {
                $this->getSelect()->columns(array( 'relevance' => "MAX(  ({$rel_added_statement}) + search_result.relevance  )"));
                $this->_appendProfilerColumns($alg_rel_likes);
            }
        } catch (Exception $e) {
             //@nelkaake Added on Wednesday June 2, 2010: If we have any errors always revert back to the old way
             Mage::helper('bss')->log("Got an error trying to run so reverting back to old search method:");
             Mage::helper('bss')->log((string)$e);
             //@nelkaake Added on Thursday June 17, 2010: failsafe
             if (!$has_joined_inner) {
                return parent::addSearchFilter($query);
             }
        }

        return $this;
    }


    /**
     * Add to the select statement the individual algorithm LIKE statement rankings
     * @param unknown_type $alg_rel_likes
     */
    protected function _appendProfilerColumns($alg_rel_likes)
    {
        if (!Mage::getStoreConfigFlag('bss/dev/profiler') || !Mage::helper('core')->isDevAllowed()) {
            return $this;
        }

        foreach ($alg_rel_likes as $code => $rel_likes) {
           foreach ($rel_likes as $rel_like) {
               $this->getSelect()->columns(array( "rel_{$code}" => "({$rel_like})"));
           }
        }

        if ($this->hasJoined("product_name")) {
            $this->getSelect()->columns(array( 'name' => "(product_name.value)"));
        }

        if ($this->hasJoined("cat_name")) {
            $this->getSelect()->columns(array('cat_name' => "(bss_index.categories)"));
        }

        if ($this->hasJoined("tag_name")) {
            $this->getSelect()->columns(array('tags' => "(bss_index.tag)"));
        }

        return $this;
    }

    /**
     * Get SQL for get record count
     *
     * @nelkaake We need to reset the GROUP clause which Magento doesn't do by default.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->reset(Zend_Db_Select::GROUP);

        $countSelect->columns('COUNT(DISTINCT e.entity_id)');
        $countSelect->resetJoinLeft();

        return $countSelect;
    }

    /**
     * Processing collection items after loading
     * Adding url rewrites, minimal prices, final prices, tax percents
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function _beforeLoad()
    {
        if (Mage::getStoreConfigFlag('bss/dev/profiler') && Mage::helper('core')->isDevAllowed()) {
            Mage::register('bss_profiled_search_results', $this->getResource()->getReadConnection()->fetchAll($this->getSelect()));
        }

        return parent::_beforeLoad();
    }

    /**
     * Set Order field
     *
     * @param string $attribute
     * @param string $dir
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
     */
    public function setOrder($attribute, $dir='desc')
    {
        if ($attribute == 'relevance') {
            $this->getSelect()->order("relevance {$dir}");
        } else {
            parent::setOrder($attribute, $dir);
        }

        return $this;
    }

    protected function _prepareStatisticsData()
    {
        $select          = clone $this->getSelect();
        $priceExpression = $this->getPriceExpression($select) . ' ' . $this->getAdditionalPriceExpression($select);
        $sqlEndPart      = ') * ' . $this->getCurrencyRate() . ', 2)';
        $select          = $this->_getSelectCountSql($select, false);
        $select->columns('ROUND(MAX(' . $priceExpression . $sqlEndPart);
        $select->columns('ROUND(MIN(' . $priceExpression . $sqlEndPart);
        $select->columns($this->getConnection()->getStandardDeviationSql('ROUND((' . $priceExpression . $sqlEndPart));
        $select->where($this->getPriceExpression($select) . ' IS NOT NULL');

        //Fix for BSS-96
        $select->reset(Zend_Db_Select::GROUP);

        $row                           = $this->getConnection()->fetchRow($select, $this->_bindParams, Zend_Db::FETCH_NUM);
        $this->_pricesCount            = (int)$row[0];
        $this->_maxPrice               = (float)$row[1];
        $this->_minPrice               = (float)$row[2];
        $this->_priceStandardDeviation = (float)$row[3];

        return $this;
    }
}
