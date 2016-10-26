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
class TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext extends Mage_CatalogSearch_Model_Mysql4_Fulltext
{

    const TYPE_LIKE     = Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE;
    const TYPE_COMBINE  = Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE;
    const TYPE_FULLTEXT = Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_FULLTEXT;

    /**
     * Array of where clause related to query text
     */
    protected $_queryTextWhere = array();

    /**
     * Sql binds
     */
    protected $_bind = array();

    /**
     * relavance column list
     */
    protected $_relavanceCols = array();
    protected $_isPrior16     = false;
    protected $_joinBssIndex  = false;

    /**
     * Prepare results for query
     *
     * @param Mage_CatalogSearch_Model_Fulltext $object
     * @param string $queryText
     * @param Mage_CatalogSearch_Model_Query $query
     *
     * @return TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext
     */
    public function prepareResult($object, $queryText, $query)
    {
        if (!Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.6.0.0')) {
            $this->_isPrior16 = true;
        }

        if (!$query->getIsProcessed()) {
            $searchType = $object->getSearchType($query->getStoreId());
            $this->setField("s.data_index");

            if ($this->isTagMatchEnabled()) {
                $this->setField("bss_index.tag", "tag");
                $this->_joinBssIndex = true;
            }

            if ($this->isCategoryMatchEnabled()) {
                $this->setField("bss_index.categories", "categories");
                $this->_joinBssIndex = true;
            }

            $stringHelper = Mage::helper('core/string');
            $words = $stringHelper->splitWords($queryText, true, $query->getMaxQueryWords());

            if ($searchType == self::TYPE_LIKE || $searchType == self::TYPE_COMBINE) {
               $this->setQueryTextWhere($this->getLikeCondition($words));
            }

            if ($searchType == self::TYPE_FULLTEXT || $searchType == self::TYPE_COMBINE) {
                $this->setQueryTextWhere($this->getFulltextCondition($queryText, $query));
            }

            if ($this->_isPrior16) {
                $this->insertSearchDataPrior16($query);
            } else {
                $this->insertSearchData($query);
            }

            $query->setIsProcessed(1);
        }

        return $this;
    }

    /**
     * Set conditions related to  query text
     *
     * @param String $condition
     * @return TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext
     */
    protected function setQueryTextWhere($condition = null)
    {
       if (!empty($condition)) {
           $this->_queryTextWhere[] = $condition;
       }

       return $this;
    }

    protected function getQueryTextWhere()
    {
       return $this->_queryTextWhere;
    }

    /**
     * Set Fields related to query text, but differentiate between field types (product, category, tag), so that we can
     * apply OR/AND search leaving out category and tag matches
     *
     * @param String $$val
     * @return TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext
     */
    protected function setField($val, $fieldType = null)
    {
      if (!empty($val)) {
          $this->_like[$fieldType] = $val;
      }

      return $this;
    }

    protected function getFields()
    {
       return $this->_like;
    }

    protected function getBindParams()
    {
      return $this->_bind;
    }

    /**
     * Generate condition for fulltext mode
     *
     * @param String $queryText
     * @param Mage_CatalogSearch_Model_Query $query
     * @return String fulltext condition
     */
    protected function getFulltextCondition($queryText, $query)
    {
          if ($this->_isPrior16) {
              $this->_bind[":query"] = $queryText;
          } else {
              $preparedTerms = Mage::getResourceHelper('catalogsearch')->prepareTerms($queryText, $query->getMaxQueryWords());
              $this->_bind[':query'] = implode(' ', $preparedTerms[0]);
          }

          $fields = $this->getFields();
          $fulltextwhereCond = array();
          $fulltextCond = "";

          foreach ($fields as $field) {
              $fulltextwhereCond[] = 'MATCH (' . $field . ') AGAINST (:query IN BOOLEAN MODE)';
          }

          $this->_relavanceCols = array_merge($this->_relavanceCols, $fulltextwhereCond);

          if ($fulltextwhereCond){
              $fulltextCond = '(' . join(" OR ", $fulltextwhereCond) . ')';
          }

          return $fulltextCond;
    }

    /**
     * Generate condition for like mode
     *
     * @param Array $words
     * @return String fulltext condition
     */
    protected function getLikeCondition($words)
    {
       $fields = $this->getFields();
       $likeI = 0;
       $likeCond = "";

       if ($this->_isPrior16) {
          foreach ($words as $word) {
              foreach ($fields as $fieldType => $field) {
                  $like[$fieldType][] = $field . ' LIKE :likew' . $likeI;
                  $this->_bind[':likew' . $likeI] = '%' . $word . '%';
                  $likeI ++;
              }
          }
       } else {
          $helper = Mage::getResourceHelper('core');

          foreach ($words as $word) {
                  foreach($fields as $fieldType => $field) {
                      $like[$fieldType][] = $helper->getCILike($field, $word, array('position' => 'any'));
                  }
              }
       }

       if ($like) {
           $searchMode = Mage::getStoreConfig(TBT_Bss_Model_Fulltext::XML_PATH_BSS_SEARCH_MODE);
           $join = ($searchMode == 1) ? ' OR ' : ' AND ';
           $likeCond = '(' . join($join, $like[""]);
           unset($like[""]);

           foreach ($like as $fieldType => $field) {
              $fieldTypeLike = null;
              if (!is_null($fieldType)) {
                $fieldTypeLike = join($join, $like[$fieldType]);
              }
              $likeCond .= ' OR ' . $fieldTypeLike;
           }

           $likeCond .= ')';
       }

       return $likeCond;
    }

    /**
     * Insert search data, when version < 1.6
     *
     * @param Mage_CatalogSearch_Model_Query $query
     * @return Self
     */
    protected function insertSearchDataPrior16($query)
    {
       $addtionalJoin = "";
       $relevance = 0;

       if ($this->_joinBssIndex) {
           $addtionalJoin = "LEFT JOIN `{$this->getBssIndexTable()}` AS `bss_index` ON bss_index.product_id = e.entity_id ";
       }

       $queryTextWhere = implode(' OR ', $this->getQueryTextWhere());

       if ($this->_relavanceCols) {
          $relevance = implode(' + ', $this->_relavanceCols);
      }

      if (!Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.4.2.0')) {
            $sqlString = "
                INSERT INTO `{$this->getTable('catalogsearch/result')}` (
                   SELECT '%d', `s`.`product_id`, %s
                       FROM  `{$this->getMainTable()}` AS `s`
                       INNER JOIN `{$this->getTable('catalog/product')}` AS `e` ON `e`.`entity_id`=`s`.`product_id`
                       %s
                       WHERE ( %s )
                             AND `s`.`store_id`='%d'
                  ) ON DUPLICATE KEY UPDATE `relevance` = VALUES(`relevance`)
                ";
      } else {
           $sqlString = "
               INSERT INTO `{$this->getTable('catalogsearch/result')}` (
                   SELECT STRAIGHT_JOIN '%d', `s`.`product_id`, %s
                       FROM  `{$this->getMainTable()}` AS `s`
                       INNER JOIN `{$this->getTable('catalog/product')}` AS `e` ON `e`.`entity_id`=`s`.`product_id`
                       %s
                       WHERE ( %s )
                             AND `s`.`store_id`='%d'
                  ) ON DUPLICATE KEY UPDATE `relevance` = VALUES(`relevance`)
              ";
      }

      $sql = sprintf($sqlString, $query->getId(), $relevance, $addtionalJoin, $queryTextWhere, $query->getStoreId());
      $this->_getWriteAdapter()->query($sql, $this->getBindParams());

      return $this;
    }

    /**
     * Insert search data, when version >= 1.6
     *
     * @param Mage_CatalogSearch_Model_Query $query
     * @return Self
     */
    protected function insertSearchData($query)
    {
        $adapter = $this->_getWriteAdapter();
        $relevance = 0;
        $queryTextWhere = implode(' OR ', $this->getQueryTextWhere());

        $fields = array(
          'query_id' => new Zend_Db_Expr($query->getId()),
          'product_id',
        );

        $select = $adapter->select()
            ->from(array("s" => $this->getMainTable()), $fields)
            ->joinInner(array('e' => $this->getTable('catalog/product')), 'e.entity_id = s.product_id', array());

        if ($this->_joinBssIndex) {
            $select->joinLeft(array('bss_index' => $this->getBssIndexTable()), 'bss_index.product_id = s.product_id', array());
        }

        if ($this->_relavanceCols) {
            $relevance = implode(' + ', $this->_relavanceCols);
        }

        $select->columns(array('relevance' => new Zend_Db_Expr($relevance)));
        $select->where($queryTextWhere);
        $sql = $adapter->insertFromSelect($select, $this->getTable('catalogsearch/result'), array(), Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE);

        $adapter->query($sql, $this->getBindParams());

        return $this;
    }

    public function isTagMatchEnabled()
    {
        return Mage::helper('bss/config')->isTagMatchEnabled();
    }

    public function isCategoryMatchEnabled()
    {
        return Mage::helper('bss/config')->isCategoryMatchEnabled();
    }

    public function getBssIndexTable()
    {
        return Mage::getConfig()->getTablePrefix()."bss_index";
    }
}
