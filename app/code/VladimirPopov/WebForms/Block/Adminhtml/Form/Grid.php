<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form;

use Magento\Store\Model\Store;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \VladimirPopov\WebForms\Model\FormFactory
     */
    protected $_formFactory;

    protected $_formCollectionFactory;

    protected $roleLocator;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \VladimirPopov\WebForms\Model\FormFactory $formFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        \Magento\Backend\Model\Authorization\RoleLocator $roleLocator,
        array $data = []
    )
    {
        $this->_formFactory = $formFactory;
        $this->_formCollectionFactory = $formCollectionFactory;
        $this->roleLocator = $roleLocator;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('formGrid');
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
        $collection = $this->_formCollectionFactory->create();
        if (!$this->_authorization->isAllowed('Magento_Backend::all')) {
            $collection->addRoleFilter($this->roleLocator->getAclRoleId());
        }
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', [
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'align' => 'left',
            'index' => 'name',
        ]);

        $this->addColumn('fields', array(
            'header' => __('Fields'),
            'align' => 'right',
            'index' => 'fields',
            'type' => 'number',
            'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fields',
            'sortable' => false,
            'filter' => false
        ));

        $this->addColumn('results', array(
            'header' => __('Results'),
            'align' => 'right',
            'index' => 'results',
            'type' => 'number',
            'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Results',
            'sortable' => false,
            'filter' => false
        ));

        $this->addColumn('is_active', [
            'header' => __('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => $this->_formFactory->create()->getAvailableStatuses(),
        ]);

        $this->addColumn('created_time', [
            'header' => __('Date Created'),
            'index' => 'created_time',
            'type' => 'datetime',
        ]);

        $this->addColumn('update_time', [
            'header' => __('Last Modified'),
            'index' => 'update_time',
            'type' => 'datetime',
        ]);

        $this->addColumn('action',
            array(
                'header' => __('Action'),
                'width' => '100',
                'filter' => false,
                'sortable' => false,
                'renderer' => 'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Action',
                'is_system' => true,
            ));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('webforms/form/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_formFactory->create()->getAvailableStatuses();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('webforms/form/massStatus', ['_current' => true]),
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

        $this->getMassactionBlock()->addItem(
            'duplicate',
            [
                'label' => __('Duplicate'),
                'url' => $this->getUrl('*/*/massDuplicate', ['_current' => true]),
                'confirm' => __('Are you sure?')
            ]
        );

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
     * @param \VladimirPopov\WebForms\Model\Form|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
