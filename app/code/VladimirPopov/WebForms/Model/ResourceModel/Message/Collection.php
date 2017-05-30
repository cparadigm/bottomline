<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel\Message;

/**
 * Message collection
 *
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Store table name
     *
     * @var string
     */
    protected $_storeTable;

    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('VladimirPopov\WebForms\Model\Message', 'VladimirPopov\WebForms\Model\ResourceModel\Message');
    }

    /**
     * Returns select count sql
     *
     * @return string
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();
        $countSelect = clone $this->getSelect();

        $countSelect->reset(\Zend_Db_Select::HAVING);

        return $select;
    }

}
