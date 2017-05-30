<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Customer\Tab;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use VladimirPopov\WebForms\Model;

class Results extends \Magento\Backend\Block\Widget\Grid\Extended implements TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    protected $_resultsCollection;

    protected $_resultFactory;

    protected $_resultCollectionFactory;

    protected $_formFactory;

    protected $_formConfig;

    protected $_customerFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        Model\FormFactory $formFactory,
        Model\ResultFactory $resultFactory,
        Model\ResourceModel\Result\CollectionFactory $resultCollectionFactory,
        Model\Config\Form $formConfig,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_formFactory = $formFactory;
        $this->_resultFactory = $resultFactory;
        $this->_resultCollectionFactory = $resultCollectionFactory;
        $this->_formConfig = $formConfig;
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('customer_tab_results');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        $this->setAfter('tags');
        $this->setEmptyText(__('No Results Found'));

    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    public function getTabLabel()
    {
        return __('Web-forms');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Web-forms');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    protected function _prepareCollection()
    {
        $collection = $this->_resultCollectionFactory->create()
            ->addFilter('customer_id', $this->getCustomerId())
            ->setLoadValues(true);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getGridUrl()
    {
        return $this->getUrl('webforms_admin/adminhtml_customer/results', array('id' => $this->getCustomerId()));
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';

        switch ($row->getApproved()) {
            case \VladimirPopov\WebForms\Model\Result::STATUS_PENDING:
                $class = 'grid-severity-minor';
                break;
            case \VladimirPopov\WebForms\Model\Result::STATUS_APPROVED:
                $class = 'grid-severity-notice';
                break;
            case \VladimirPopov\WebForms\Model\Result::STATUS_NOTAPPROVED:
                $class = 'grid-severity-critical';
                break;
        }

        $cell = '<span class="' . $class . '"><span>' . $value . '</span></span>';
        return $cell;
    }

    protected function _prepareColumns()
    {
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

        $this->addcolumn('form', array(
            'header' => __('Web-form'),
            'index' => 'webform_id',
            'type' => 'options',
            'options' => $this->_formConfig->toGridOptionArray(),
        ));

        $this->addColumn('subject', array(
            'header' => __('Subject'),
            'filter' => false,
            'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Customer\Tab\Renderer\Subject'
        ));

        $this->addColumn('approved', array(
            'header' => __('Approved'),
            'index' => 'approved',
            'type' => 'options',
            'width' => '140',
            'options' => $this->_resultFactory->create()->getApprovalStatuses(),
            'frame_callback' => array($this, 'decorateStatus')
        ));

        $this->addColumn('created_time', array(
            'header' => __('Date Created'),
            'index' => 'created_time',
            'type' => 'datetime',
            'width' => '200'
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('results');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => __('Delete'),
            'url' => $this->getUrl('webforms/result/massDelete', array('customer_id' => $this->getCustomerId())),
            'confirm' => __('Are you sure to delete selected results?'),
        ));

        $this->getMassactionBlock()->addItem('email', array(
            'label' => __('Send by e-mail'),
            'url' => $this->getUrl('webforms/results/massEmail', array('customer_id' => $this->getCustomerId())),
            'confirm' => __('Send selected results to specified e-mail address?'),
            'additional' => array(
                'recipient' => array(
                    'name' => 'recipient_email',
                    'type' => 'text',
                    'label' => __('Recipient e-mail'),
                    'value' => $this->getRecipientEmail(),
                )
            )
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => __('Update status'),
            'url' => $this->getUrl('webforms/result/massStatus', array('customer_id' => $this->getCustomerId())),
            'additional' => array(
                'status' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $this->_resultFactory->create()->getApprovalStatuses()
                )
            )
        ));

        return $this;
    }

    public function getRecipientEmail()
    {
        return $this->_storeManager->getStore($this->_customerFactory->create()->load($this->getCustomerId())->getStoreId())->getWebsite()->getConfig('webforms/email/email');
    }

}