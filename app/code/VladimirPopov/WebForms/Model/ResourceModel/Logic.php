<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel;

/**
 * Logic resource model
 *
 */
class Logic extends AbstractResource
{
    const ENTITY_TYPE = 'logic';

    public function getEntityType(){
        return self::ENTITY_TYPE;
    }

    /**
     * Name of scope for error messages
     *
     * @var string
     */
    protected $_messagesScope = 'webforms/session';

    protected $_fieldFactory;

    protected $_fieldsetFactory;

    /**
     * Initialize resource model
     * Get tablename from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webforms_logic', 'id');
    }

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \VladimirPopov\WebForms\Model\FieldsetFactory $fieldsetFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        $this->_fieldFactory = $fieldFactory;
        $this->_fieldsetFactory = $fieldsetFactory;

        parent::__construct($context, $date,$storeFactory,$dateTime,$connectionName);
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

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (is_array($object->getData('value'))) $object->setData('value_serialized', serialize($object->getData('value')));
        if (is_array($object->getData('target'))) {
            $targets = $object->getData('target');
            foreach($targets as $i => $t){
                if(strstr($t, 'field_')){
                    $field_id = str_replace('field_','',$t);
                    $field = $this->_fieldFactory->create()->load($field_id);
                    if(!$field->getId()){
                        unset($targets[$i]);
                    }
                }

                if(strstr($t, 'fieldset_')){
                    $fieldset_id = str_replace('fieldset_','',$t);
                    $fieldset = $this->_fieldsetFactory->create()->load($fieldset_id);
                    if(!$fieldset->getId()){
                        unset($targets[$i]);
                    }
                }
            }
            $object->setData('target_serialized', serialize($targets));
        }

        if ($object->isObjectNew() && !$object->hasCreatedTime()) {
            $object->setCreatedTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setData('value', unserialize($object->getData('value_serialized')));
        $object->setData('target', unserialize($object->getData('target_serialized')));

        return parent::_afterLoad($object);
    }
}
