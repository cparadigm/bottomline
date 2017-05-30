<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class Fields extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Fields');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Fields');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return $this->_coreRegistry->registry('webforms_form')->getId() ? false : true;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * @var \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory
     */
    protected $_fieldCollectionFactory;

    /**
     * @var \VladimirPopov\WebForms\Model\FieldFactory
     */
    protected $_fieldFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory $fieldCollectionFactory
     * @param \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory $fieldCollectionFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_fieldCollectionFactory = $fieldCollectionFactory;
        $this->_fieldFactory = $fieldFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('fields_section');
        $this->setDefaultSort('position');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_fieldCollectionFactory->create()
            ->setStoreId($this->_coreRegistry->registry('webforms_form')->getStoreId())
            ->addFilter('webform_id', $this->_coreRegistry->registry('webforms_form')->getId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        /** @var \VladimirPopov\WebForms\Model\Form $modelForm */
        $modelForm = $this->_coreRegistry->registry('webforms_form');
        $this->addColumn('id', array(
            'header' => __('ID'),
            'width' => 60,
            'index' => 'id'
        ));

        $this->addColumn('name', array(
            'header' => __('Name'),
            'index' => 'name',
        ));

        $this->addColumn('code', array(
            'header' => __('Code'),
            'index' => 'code',
        ));

        $fieldsetsOptions = $modelForm->getFieldsetsOptionsArray();

        $this->addColumn('fieldset_id', array(
            'header' => __('Fieldset'),
            'index' => 'fieldset_id',
            'type' => 'options',
            'options' => $fieldsetsOptions,
        ));

        $fieldTypes =  $this->_fieldFactory->create()->getFieldTypes();

        $this->addColumn('type', array(
            'header' => __('Type'),
            'width' => 150,
            'index' => 'type',
            'type' => 'options',
            'options' => $fieldTypes,
        ));

        $this->addColumn('required', array(
            'header' => __('Required'),
            'width' => 100,
            'index' => 'required',
            'type' => 'options',
            'options' => array("1" => __("Yes"), "0" => __("No")),
        ));

        $this->addColumn('is_active', array(
            'header' => __('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => $this->_fieldFactory->create()->getAvailableStatuses(),
        ));

        $config = array(
            'header' => __('Position'),
            'name' => 'position',
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'align' => 'right',
            'prefix' => 'fields_position',
        );
        if (!$this->getRequest()->getParam('store')) {
            $config['renderer'] = 'VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab\Renderer\Position';
            $config['editable'] = true;
        }

        $this->addColumn('position', $config);

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $modelForm = $this->_coreRegistry->registry('webforms_form');

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setTemplate('VladimirPopov_WebForms::webforms/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('fields');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('*/field/massDelete', ['_current' => true]),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_fieldFactory->create()->getAvailableStatuses();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('*/field/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'label' => __('Status'),
                        'options' => $statuses
                    ]
                ]
            ]
        );

        $fieldsetsOptions = $modelForm->getFieldsetsOptionsArray();
        if(count($fieldsetsOptions)>1) {
            $this->getMassactionBlock()->addItem('fieldset', array(
                'label'=> __('Change fieldset'),
                'url'  => $this->getUrl('*/field/massFieldset', ['_current' => true]),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'fieldset',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Fieldset'),
                        'values' => $fieldsetsOptions
                    )
                )
            ));
        }

        $this->getMassactionBlock()->addItem(
            'duplicate',
            [
                'label' => __('Duplicate'),
                'url' => $this->getUrl('*/field/massDuplicate', ['_current' => true]),
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
        return $this->getUrl('*/field/grid', ['id' => $this->_coreRegistry->registry('webforms_form')->getId()]);
    }

    /**
     * @param \VladimirPopov\WebForms\Model\Field|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/field/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}