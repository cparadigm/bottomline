<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result;

use Magento\Store\Model\Store;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \VladimirPopov\WebForms\Model\ResultFactory
     */
    protected $_resultFactory;

    protected $_coreRegistry;

    protected $_customerFactory;

    protected $_fieldFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \VladimirPopov\WebForms\Model\FormFactory $resultFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \VladimirPopov\WebForms\Model\ResultFactory $resultFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        array $data = []
    )
    {
        $this->_resultFactory = $resultFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_customerFactory = $customerFactory;
        $this->_fieldFactory = $fieldFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('resultGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $store = $this->getRequest()->getParam('store');
        $modelForm = $this->_coreRegistry->registry('webforms_form');
        $collection = $this->_resultFactory->create()->getCollection()->setLoadValues(true)->addFilter('webform_id',$modelForm->getId());

        if ($store)
            $collection->addFilter('store_id', $store);

        if ($this->_isExport) {
            $Ids = (array)$this->getRequest()->getParam('internal_results');
            if (count($Ids) == 1 && !empty($Ids[0])) $Ids = explode(',', $Ids[0]);
            if (count($Ids)) {
                $collection->addFieldToFilter('id', array('in' => $Ids));
            }
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    protected function _filterCustomerCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        while (strstr($value, "  ")) {
            $value = str_replace("  ", " ", $value);
        }
        $customers_array = array();
        $name = explode(" ", $value);
        $firstname = $name[0];
        $lastname = $name[count($name) - 1];
        $customers = $this->_customerFactory->create()->getCollection()
            ->addAttributeToFilter('firstname', $firstname);
        if (count($name) == 2)
            $customers->addAttributeToFilter('lastname', $lastname);
        foreach ($customers as $customer) {
            $customers_array[] = $customer->getId();
        }
        $collection->addFieldToFilter('customer_id', array('in' => $customers_array));
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addFilter('store_id', $value);
    }

    protected function _filterFieldCondition($collection, $column)
    {
        $field_id = str_replace('field_', '', $column->getIndex());
        $value = $column->getFilter()->getValue();
        if (!is_array($value)) $value = trim($value);
        if ($field_id && $value)
            $collection->addFieldFilter($field_id, $value);
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        $approve_url = $this->getUrl('*/*/setStatus', array('id' => $row->getId(), 'status' => \VladimirPopov\WebForms\Model\Result::STATUS_APPROVED));
        $reject_url = $this->getUrl('*/*/setStatus', array('id' => $row->getId(), 'status' => \VladimirPopov\WebForms\Model\Result::STATUS_NOTAPPROVED));
        $complete_url = $this->getUrl('*/*/setStatus', array('id' => $row->getId(), 'status' => \VladimirPopov\WebForms\Model\Result::STATUS_COMPLETED));

        $class = 'grid-status ';
        $progress = '<span class="grid-button-action request-progress" style="display: none;"><span>'.__('Sending...').'</span></span>';
        $button_approve_style = "";
        $button_complete_style = "";
        $button_reject_style = "";


        if ($isExport) return $value;
        switch ($row->getApproved()) {
            case \VladimirPopov\WebForms\Model\Result::STATUS_PENDING:
                $class .= 'pending';
                $button_complete_style = "display:none";
                break;
            case \VladimirPopov\WebForms\Model\Result::STATUS_APPROVED:
                $button_approve_style = "display:none";
                $class .= 'approved';
                break;
            case \VladimirPopov\WebForms\Model\Result::STATUS_COMPLETED:
                $button_approve_style = "display:none";
                $button_complete_style = "display:none";
                $button_reject_style = "display:none";
                $class .= 'completed';
                break;
            case \VladimirPopov\WebForms\Model\Result::STATUS_NOTAPPROVED:
                $button_reject_style = "display:none";
                $button_complete_style = "display:none";
                $class .= 'notapproved';
                break;
        }
        $button_approve = '<a href="javascript:void(0)" style="'.$button_approve_style.'" onclick="setResultStatus(this, \'' . $approve_url . '\'); return false" class="grid-button-action approve"><span>'.__('Approve').'</span></a>';
        $button_complete = '<a href="javascript:void(0)" style="'.$button_complete_style.'" onclick="setResultStatus(this, \'' . $complete_url . '\'); return false" class="grid-button-action complete"><span>'.__('Complete').'</span></a>';
        $button_reject = '<a href="javascript:void(0)" style="'.$button_reject_style.'" onclick="if(confirm(\''.__('Are you sure you want to disapprove the result?').'\'))setResultStatus(this, \'' . $reject_url . '\'); return false" class="grid-button-action reject"><span>'.__('Reject').'</span></a>';

        $cell = '<div class="' . $class . '">' . $value . '</div>';
        $cell .= $button_reject;
        $cell .= $button_approve;
        $cell .= $button_complete;
        $cell .= $progress;
        return $cell;
    }

    protected function _prepareColumns()
    {
        $modelForm = $this->_coreRegistry->registry('webforms_form');
        $renderer = 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\Id';
        if ($this->_isExport) {
            $renderer = false;
        }
        $this->addColumn('id', array(
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
            'renderer' => $renderer
        ));

        $fields = $this->_fieldFactory->create()
            ->setStoreId($this->getRequest()->getParam('store'))
            ->getCollection()
            ->addFilter('webform_id', $modelForm->getId());
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

                    if ($field->getType() == 'image' || $field->getType() == 'file'){
                        $config['renderer'] = 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\File';
                    }

                    if (strstr($field->getType(), 'select')) {
                        $config['type'] = 'options';
                        $config['options'] = $field->getSelectOptions();
                    }

                    if ($field->getType() == 'subscribe') {
                        $config['type'] = 'options';
                        $config['renderer'] = false;
                        $config['options'] = array(
                            0 => __('No'),
                            1 => __('Yes'),
                        );
                    }

                    if ($field->getType() == 'number' || $field->getType() == 'stars') {
                        $config['type'] = 'number';
                        $config['align'] = 'right';
                    }
                    if ($field->getType() == 'date') {
                        $config['type'] = 'date';
                    }
                    if ($field->getType() == 'datetime') {
                        $config['type'] = 'datetime';
                    }
                    if ($field->getType() == 'country') {
                        $config['type'] = 'country';
                        $config['renderer'] = false;
                    }
                    if ($field->getType() == 'textarea' || $field->getType() == 'wysiwyg') {
                        $config['width'] = '300px';
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

        if (!$this->_storeManager->isSingleStoreMode()) {
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
        }

        if ($modelForm->getApprove()) {
            $this->addColumn('approved', array(
                'header' => __('Status'),
                'index' => 'approved',
                'type' => 'options',
                'options' => $this->_resultFactory->create()->getApprovalStatuses(),
                'frame_callback' => array($this, 'decorateStatus')
            ));
        }

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

        $this->addColumn('action',
            array(
                'header' => __('Action'),
                'width' => '60',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer\Action',
                'is_system' => true,
            ));

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('Excel XML'));

        $this->_eventManager->dispatch('webforms_block_adminhtml_results_grid_prepare_columns', array('grid' => $this));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $modelForm = $this->_coreRegistry->registry('webforms_form');
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('results');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('*/*/massDelete', ['_current' => true]),
                'confirm' => __('Are you sure?')
            ]
        );

        if($modelForm->getApprove()) {
            $statuses = $this->_resultFactory->create()->getApprovalStatuses();

            $this->getMassactionBlock()->addItem(
                'status',
                [
                    'label' => __('Change status'),
                    'url' => $this->getUrl('*/*/massStatus', ['_current' => true]),
                    'additional' => [
                        'visibility' => [
                            'name' => 'status',
                            'type' => 'select',
                            'class' => 'required-entry',
                            'label' => __('Status'),
                            'options' => $statuses
                        ]
                    ]
                ]
            );
        }

        $this->getMassactionBlock()->addItem('email', array(
            'label' => __('Send by e-mail'),
            'url' => $this->getUrl('*/*/massEmail', array('_current' => true)),
            'confirm' => __('Send selected results to specified e-mail address?'),
            'additional' => array(
                'recipient' => array(
                    'name' => 'recipient_email',
                    'type' => 'text',
                    'class' => 'required-entry validate-email',
                    'label' => __('Recipient e-mail'),
                    'value' => $this->getRecipientEmail(),
                )
            )
        ));
        
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * @param \VladimirPopov\WebForms\Model\Result|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/reply',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId() ,'webform_id' => $this->getRequest()->getParam('webform_id')]
        );
    }

    protected function getRecipientEmail()
    {
        $modelForm = $this->_coreRegistry->registry('webforms_form');

        $email = $modelForm->getEmailSettings();

        return $email['email'];
    }
}
