<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel;

/**
 * Form resource model
 *
 */
class Form extends AbstractResource
{
    const ENTITY_TYPE = 'form';

    public function getEntityType()
    {
        return self::ENTITY_TYPE;
    }

    /**
     * Name of scope for error messages
     *
     * @var string
     */
    protected $_messagesScope = 'webforms/session';

    protected $_fieldCollectionFactory;

    protected $_fieldsetCollectionFactory;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory $fieldCollectionFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\Fieldset\CollectionFactory $fieldsetCollectionFactory,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        $this->_fieldCollectionFactory = $fieldCollectionFactory;
        $this->_fieldsetCollectionFactory = $fieldsetCollectionFactory;
        parent::__construct($context, $date, $storeFactory, $dateTime, $connectionName);
    }

    /**
     * Initialize resource model
     * Get tablename from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webforms', 'id');
    }

    /**
     * Set error messages scope
     *
     * @param string $scope
     * @return void
     */
    public function setMessagesScope($scope)
    {
        $this->_messagesScope = $scope;
    }

    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object){
        //delete fields
        $fields = $this->_fieldCollectionFactory->create()->addFilter('webform_id',$object->getId());
        foreach($fields as $field){
            $field->delete();
        }
        //delete fieldsets
        $fieldsets = $this->_fieldsetCollectionFactory->create()->addFilter('webform_id',$object->getId());
        foreach($fieldsets as $fieldset){
            $fieldset->delete();
        }

        return parent::_beforeDelete($object);
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setData('access_groups_serialized', serialize($object->getData('access_groups')));
        $object->setData('dashboard_groups_serialized', serialize($object->getData('dashboard_groups')));

        if ($object->isObjectNew() && !$object->hasCreatedTime()) {
            $object->setCreatedTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setData('access_groups', unserialize($object->getData('access_groups_serialized')));
        $object->setData('dashboard_groups', unserialize($object->getData('dashboard_groups_serialized')));

        return parent::_afterLoad($object);
    }
}
