<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Reply;

class Results extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_resultCollectionFactory;

    protected $_fieldCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \VladimirPopov\WebForms\Model\ResourceModel\Result\CollectionFactory $resultCollectionFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory $fieldCollectionFactory,
        array $data = []
    )
    {
        $this->_resultCollectionFactory = $resultCollectionFactory;
        $this->_fieldCollectionFactory = $fieldCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    public function _construct()
    {
        $this->setFilterVisibility(false);
        parent::_construct();
        $this->setId('webforms_reply_grid_' . $this->getRequest()->getParam('webform_id'));
    }

    protected function _prepareCollection()
    {
        $Ids = $this->getRequest()->getParam('id');

        if (!is_array($Ids)) {
            $Ids = array($Ids);
        }

        $collection = $this->_resultCollectionFactory->create()
            ->setLoadValues(true)
            ->addFieldToFilter('id', $Ids)
            ->addOrder('id', 'desc');

        $this->setCollection($collection);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
            'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\Id'
        ));

        $fields = $this->_fieldCollectionFactory->create()
            ->setStoreId($this->getRequest()->getParam('store'))
            ->addFilter('webform_id', $this->getRequest()->getParam('webform_id'));
        $fields->getSelect()->order('position asc');

        $maxlength = $this->_scopeConfig->getValue('webforms/results/fieldname_display_limit');
        foreach ($fields as $field) {
            if ($field->getType() != 'html') {
                $field_name = $field->getName();
                if ($field->getResultLabel()) {
                    $field_name = $field->getResultLabel();
                }
                if (strlen($field_name) > $maxlength && $maxlength > 0) {
                    if (function_exists('mb_substr')) {
                        $field_name = mb_substr($field_name, 0, $maxlength) . '...';
                    } else {
                        $field_name = substr($field_name, 0, $maxlength) . '...';
                    }
                }
                $config = array(
                    'header' => $field_name,
                    'index' => 'field_' . $field->getId(),
                    'sortable' => false,
                    'filter_condition_callback' => array($this, '_filterFieldCondition'),
                    'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\Value'
                );
                if ($this->_isExport) {
                    $config['renderer'] = false;
                } else {
                    if ($field->getType() == 'image') {
                        $config['filter'] = false;
                        $config['width'] = $this->_scopeConfig->getValue('webforms/images/grid_thumbnail_width') . 'px';
                    }

                    if ($field->getType() == 'image' || $field->getType() == 'file') {
                        $config['renderer'] = 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\File';
                    }

                    if (strstr($field->getType(), 'select')) {
                        $config['type'] = 'options';
                        $config['options'] = $field->getSelectOptions();
                    }
                    if ($field->getType() == 'number' || $field->getType() == 'stars') {
                        $config['type'] = 'number';
                    }
                    if ($field->getType() == 'date') {
                        $config['type'] = 'date';
                    }
                    if ($field->getType() == 'datetime') {
                        $config['type'] = 'datetime';
                    }
                    if ($field->getType() == 'subscribe') {
                        $config['type'] = 'options';
                        $config['renderer'] = false;
                        $config['options'] = array(
                            0 => __('No'),
                            1 => __('Yes'),
                        );
                    }
                }
                $config = new \Magento\Framework\DataObject($config);
                $this->_eventManager->dispatch('webforms_block_adminhtml_results_grid_prepare_columns_config', array('field' => $field, 'config' => $config));

                $this->addColumn('field_' . $field->getId(), $config->getData());
            }
        }
        $config = array(
            'header' => __('Customer'),
            'align' => 'left',
            'index' => 'customer_id',
            'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\Customer',
            'filter_condition_callback' => array($this, '_filterCustomerCondition'),
            'sortable' => false
        );
        if ($this->_isExport) {
            $config['renderer'] = false;
        }
        $this->addColumn('customer_id', $config);

        $this->addColumn('store_id', array(
            'header' => __('Store View'),
            'index' => 'store_id',
            'type' => 'store',
            'store_all' => true,
            'store_view' => true,
            'sortable' => false,
            'filter' => false,
            'filter_condition_callback' => array($this, '_filterStoreCondition'),
        ));

        $this->addColumn('ip', array(
            'header' => __('IP'),
            'index' => 'ip',
            'sortable' => false,
            'filter' => false,
        ));

        $this->addColumn('created_time', array(
            'header' => __('Date Created'),
            'index' => 'created_time',
            'type' => 'datetime',
        ));

        return parent::_prepareColumns();
    }
}