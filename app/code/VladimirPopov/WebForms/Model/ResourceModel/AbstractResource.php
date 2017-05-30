<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel;

/**
 * AbstractResource resource model
 *
 */
class AbstractResource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_store_id;

    protected $_storeFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_date = $date;
        $this->_storeFactory = $storeFactory;
    }

    protected function _construct()
    {
    }

    public function getEntityType(){return false;}

    public function setStoreId($store_id){
        $this->_store_id = $store_id;
    }

    public function getStoreId(){
        return $this->_store_id;
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if($this->getStoreId()){
            $store = $this->_storeFactory->create()->search($this->getStoreId(),$this->getEntityType(),$object->getId());

            $object->setStoreData($store->getStoreData());

            if($store->getStoreData())
                foreach($store->getStoreData() as $key=>$val){
                    $object->setData($key,$val);
                }
        }

        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_storeFactory->create()->deleteAllStoreData($this->getEntityType(),$object->getId());
        return parent::_beforeDelete($object);
    }
}
