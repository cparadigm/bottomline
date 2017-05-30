<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Store extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    /**
     * Store cache tag
     */
    const CACHE_TAG = 'webforms_store';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_store';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'webforms_store';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\Store');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData('id');
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    public function search($store_id, $entity_type, $entity_id){

        $read = $this->getResource()->getConnection();

        $select = $read->select()
            ->from($this->getResource()->getMainTable(),array('id'))
            ->where('store_id=?',$store_id)
            ->where('entity_type=?',$entity_type)
            ->where('entity_id=?',$entity_id);

        $data = $read->fetchRow($select);

        if($data['id']){
            $this->load($data['id']);
        }
        return $this;
    }

    public function deleteAllStoreData($entity_type, $entity_id)
    {
        $read = $this->getResource()->getConnection();

        $select = $read->select()
            ->from($this->getResource()->getMainTable(),array('id'))
            ->where('entity_type=?',$entity_type)
            ->where('entity_id=?',$entity_id);

        while($data = $read->fetchRow($select)){
            $this->setId($data['id'])->delete();
        };

    }

    public function getAllStores($entity_type, $entity_id){
        $read = $this->getResource()->getReadConnection();

        $select = $read->select()
            ->from($this->getResource()->getMainTable(),array('id'))
            ->where('entity_type=?',$entity_type)
            ->where('entity_id=?',$entity_id);

        $data = $read->fetchRow($select);

        if($data['id']){
            $this->load($data['id']);
        }
        return $this;
    }
}
