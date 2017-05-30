<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel;

/**
 * Field resource model
 *
 */
class Field extends AbstractResource
{
    const ENTITY_TYPE = 'field';

    protected $_fileCollectionFactory;

    protected $_formFactory;


    public function getEntityType(){
        return self::ENTITY_TYPE;
    }

    /**
     * Name of scope for error messages
     *
     * @var string
     */
    protected $_messagesScope = 'webforms/session';

    /**
     * Initialize resource model
     * Get tablename from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webforms_fields', 'id');
    }

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        $this->_fileCollectionFactory = $fileCollectionFactory;
        $this->_formFactory = $formFactory;
        parent::__construct($context, $date, $storeFactory, $dateTime, $connectionName);
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
        if(is_array($object->getValue()))$object->setValue(serialize($object->getValue()));

        if ($object->isObjectNew() && !$object->hasCreatedTime()) {
            $object->setCreatedTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        parent::_afterLoad($object);

        if(!is_array($object->getValue())) {
            $unserialized_value = @unserialize($object->getValue());
            if ($unserialized_value) {
                $object->setValue($unserialized_value);
            } else {
                // support for old value format
                $value = $object->getValue();
                $stars_value = explode("\n", $value);
                if (empty($stars_value[1])) $stars_value[1] = false;
                $value_array = array(
                    'text' => $value,
                    'text_email' => $value,
                    'text_url' => $value,
                    'textarea' => $value,
                    'newsletter' => $value,
                    'stars_init' => $stars_value[1],
                    'stars_max' => $stars_value[0],
                    'options' => $value,
                    'options_radio' => $value,
                    'options_checkbox' => $value,
                    'options_contact' => $value,
                    'allowed_extensions' => $value,
                    'html' => $value,
                    'hidden' => $value,
                );
                $object->setValue($value_array);
            }
        }

        $value = $object->getValue();
        switch($object->getType()){
            case 'url':
                if(!empty($value["text_url"]))
                    $value["text"] = $value["text_url"];
                break;
            case 'email':
                if(!empty($value["text_email"]))
                    $value["text"] = $value["text_email"];
                break;
            case 'select/radio':
                if(!empty($value["options_radio"]))
                    $value["options"] = $value["options_radio"];
                break;
            case 'select/checkbox':
                if(!empty($value["options_checkbox"]))
                    $value["options"] = $value["options_checkbox"];
                break;
            case 'select/contact':
                if(!empty($value["options_contact"]))
                    $value["options"] = $value["options_contact"];
                break;
        }
        if(!empty($value["text"])) {
            $value["text_url"] = $value["text"];
            $value["text_email"] = $value["text"];
        }
        if(!empty($value["options"])) {
            $value["options_radio"] = $value["options"];
            $value["options_checkbox"] = $value["options"];
            $value["options_contact"] = $value["options"];
        }
        $object->setValue($value);

        $store_data = $object->getData('store_data');
        if(!empty($store_data['value']) && is_array($store_data['value'])){
            foreach($store_data['value'] as $key => $value){
                $store_data['value_'.$key] = $value;
            }
        }
        $object->setStoreData($store_data);

        if($object->getHint()) {
            $object->setData("hint_email", $object->getHint());
            $object->setData("hint_url", $object->getHint());
            $object->setData("hint_textarea", $object->getHint());
        }
        return $this;
    }

    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        //delete values
        $this->getConnection()->delete($this->getTable('webforms_results_values'), 'field_id =' . $object->getId());
        $this->getConnection()->delete($this->getTable('webforms_logic'), 'field_id =' . $object->getId());

        //delete files
        $files = $this->_fileCollectionFactory->create()->addFilter('field_id', $object->getId());
        /** @var \VladimirPopov\WebForms\Model\File $file */
        foreach ($files as $file) {
            $file->delete();
        }

        return parent::_beforeDelete($object);
    }

    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        //update logic rules
        $webform = $this->_formFactory->create()->load($object->getData('webform_id'));
        $logic_collection = $webform->getLogic();
        foreach ($logic_collection as $logic_rule){
            $logic_rule->save();
        }

        return parent::_afterDelete($object); // TODO: Change the autogenerated stub
    }

    public function getNextPosition($webformId)
    {
        $sql = new \Zend_Db_Select($this->getConnection());
        $sql
            ->from($this->getMainTable(), 'position')
            ->where('webform_id = ?', $webformId)
            ->order('position DESC');

        $position = intval($this->getConnection()->fetchOne($sql));
        if (!$position) {
            $position = 0;
        }

        return $position + 10;
    }

}
