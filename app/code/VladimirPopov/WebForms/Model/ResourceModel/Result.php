<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel;

/**
 * Result resource model
 *
 */
class Result extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Name of scope for error messages
     *
     * @var string
     */
    protected $_messagesScope = 'webforms/session';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;
    
    protected $_fieldFactory;

    protected $_localeResolver;

    protected $_eventManager;

    protected $_random;
    
    protected $_formFactory;

    protected $_fileStorage;

    protected $_messageFactoryCollection;

    protected $_customerFactory;

    protected $storeManager;

    protected $_fileCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Math\Random $random,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorage,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\StoreManager $storeManager,
        \VladimirPopov\WebForms\Model\ResourceModel\Message\CollectionFactory $messageFactoryCollection,
        $resourcePrefix = null
    )
    {
        $this->_date = $date;
        $this->_fieldFactory = $fieldFactory;
        $this->_localeResolver = $localeResolver;
        $this->_eventManager = $eventManager;
        $this->_random = $random;
        $this->_formFactory = $formFactory;
        $this->_fileStorage = $fileStorage;
        $this->_messageFactoryCollection = $messageFactoryCollection;
        $this->_customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->_fileCollectionFactory = $fileCollectionFactory;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * Initialize resource model
     * Get tablename from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webforms_results', 'id');
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

    public function getLocale()
    {
        return $this->_localeResolver->getLocale();
    }
    
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedTime()) {
            $object->setCreatedTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        if (count($object->getData('field')) > 0) {
            foreach ($object->getData('field') as $field_id => $value) {
                $field = $this->_fieldFactory->create()->load($field_id);

                // assign customer ID if email found
                if ($field->getType() == 'email' && $field->getValue('assign_customer_id_by_email') && !$object->getCustomerId()) {
                    $customer = $this->_customerFactory->create();
                    $customer->setWebsiteId($this->storeManager->getStore($object->getStoreId())->getWebsiteId())->loadByEmail($value);
                    if ($customer->getId()) {
                        $object->setCustomerId($customer->getId());
                    }
                }
            }
        }

        parent::_beforeSave($object);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        //insert field values
        if (count($object->getData('field')) > 0) {
            foreach ($object->getData('field') as $field_id => $value) {
                if (is_array($value)) {
                    $value = implode("\n", $value);
                }
                $field = $this->_fieldFactory->create()->load($field_id);
                if (strstr($field->getType(), 'date') && strlen($value) > 0) {
                    $date = new \Zend_Date();
                    $date->setDate($value, $field->getDateFormat(), $this->getLocale());
                    if ($field->getType() == 'datetime')
                        $date->setTime($value, $field->getDateFormat(), $this->getLocale());
                    $value = date($field->getDbDateFormat(), $date->getTimestamp());
                }
                if ($field->getType() == 'select/contact' && is_numeric($value)) {
                    $value = $field->getContactValueById($value);
                }

                if($value == $field->getHint()){
                    $value = '';
                }

                // create key
                $key = "";
                if ($field->getType() == 'file' || $field->getType() == 'image') {
                    $key = $this->_random->getRandomString(6);
                    if ($object->getData('key_' . $field_id))
                        $key = $object->getData('key_' . $field_id);
                }
                $object->setData('key_' . $field_id, $key);

                $select = $this->getConnection()->select()
                    ->from($this->getTable('webforms_results_values'))
                    ->where('result_id = ?', $object->getId())
                    ->where('field_id = ?', $field_id);

                $result_value = $this->getConnection()->fetchAll($select);

                if (!empty($result_value[0])) {
                    $this->getConnection()->update($this->getTable('webforms_results_values'), array(
                        "value" => $value,
                        "key" => $key
                    ),
                        "id = " . $result_value[0]['id']
                    );

                } else {
                    $this->getConnection()->insert($this->getTable('webforms_results_values'), array(
                        "result_id" => $object->getId(),
                        "field_id" => $field_id,
                        "value" => $value,
                        "key" => $key
                    ));
                }

                // update object
                $object->setData('field_'.$field_id, $value);
                $object->setData('key_'.$field_id, $key);
            }
        }

        $this->_eventManager->dispatch('webforms_result_save', array('result' => $object));

        return parent::_afterSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $webform = $this->_formFactory->create()->load($object->getData('webform_id'));

        $select = $this->getConnection()->select()
            ->from($this->getTable('webforms_results_values'))
            ->where('result_id = ?', $object->getId());
        $values = $this->getConnection()->fetchAll($select);

        foreach ($values as $val) {
            $object->setData('field_' . $val['field_id'], $val['value']);
            $object->setData('key_' . $val['field_id'], $val['key']);
        }

        $object->setData('ip', long2ip($object->getCustomerIp()));

        $this->_eventManager->dispatch('webforms_result_load', array('webform' => $webform, 'result' => $object));

        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        //delete values
        $this->getConnection()->delete($this->getTable('webforms_results_values'),
            'result_id = ' . $object->getId()
        );

        //clear messages
        $messages = $this->_messageFactoryCollection->create()->addFilter('result_id', $object->getId());
        foreach ($messages as $message) $message->delete();

        //delete files
        $files = $this->_fileCollectionFactory->create()->addFilter('result_id', $object->getId());
        /** @var \VladimirPopov\WebForms\Model\File $file */
        foreach ($files as $file) {
            $file->delete();
        }

        $this->_eventManager->dispatch('webforms_result_delete', array('result' => $object));

        return parent::_beforeDelete($object);
    }

    // this function helps delete folder recursively
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function getSummaryRatings($webform_id, $store_id)
    {
        $adapter = $this->getConnection();

        $sumColumn = new \Zend_Db_Expr("SUM(results_values.value)");
        $countColumn = new \Zend_Db_Expr("COUNT(*)");

        $select = $adapter->select()
            ->from(array('results_values' => $this->getTable('webforms_results_values')),
                array(
                    'sum' => $sumColumn,
                    'count' => $countColumn,
                    'field_id'
                ))
            ->join(array('fields' => $this->getTable('webforms_fields')),
                'results_values.field_id = fields.id',
                array())
            ->join(array('results' => $this->getTable('webforms_results')),
                'results_values.result_id = results.id',
                array())
            ->where('fields.type = "stars"')
            ->where('results.webform_id = ' . $webform_id)
            ->where('results.store_id = ' . $store_id)
            ->where('results.approved = 1')
            ->group('results_values.field_id');
        return $adapter->fetchAll($select);
    }
}
