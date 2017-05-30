<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

class AbstractModel extends \Magento\Framework\Model\AbstractModel
{
    protected $_storeFactory;

    public function __construct(
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_storeFactory = $storeFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\AbstractResource');
    }

    public function getEntityType()
    {
        return $this->getResource()->getEntityType();
    }

    public function setStoreId($store_id)
    {
        $this->getResource()->setStoreId($store_id);

        return $this;
    }

    public function getStoreId()
    {
        return $this->getResource()->getStoreId();
    }

    public function saveStoreData($store_id, $data)
    {
        unset($data['id']);
        $object = $this->_storeFactory->create()
            ->search($store_id, $this->getEntityType(), $this->getId())
            ->setStoreId($store_id)
            ->setEntityType($this->getEntityType())
            ->setEntityId($this->getId())
            ->setStoreData(serialize($data))
            ->save();

        return $this;
    }

    public function updateStoreData($store_id, $data)
    {
        $object = $this->_storeFactory->create()->search($store_id, $this->getEntityType(), $this->getId());
        $store_data = $data;

        if ($object->getId()) {
            $store_data = $object->getStoreData();
            foreach ($data as $key => $val) {
                $store_data[$key] = $val;
            }
        }

        return $this->saveStoreData($store_id, $store_data);
    }
}
