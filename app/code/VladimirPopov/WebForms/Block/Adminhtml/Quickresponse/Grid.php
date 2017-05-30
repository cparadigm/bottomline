<?php
/**
 * Adminhtml form grid block
 */
namespace VladimirPopov\WebForms\Block\Adminhtml\Quickresponse;

use Magento\Store\Model\Store;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \VladimirPopov\WebForms\Model\QuickresponseFactory
     */
    protected $_quickresponseCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \VladimirPopov\WebForms\Model\ResourceModel\Quickresponse\CollectionFactory $quickresponseCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_quickresponseCollectionFactory = $quickresponseCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('quickresponseGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
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
        $collection = $this->_quickresponseCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',array(
            'header' => __('ID'),
            'align'	=> 'right',
            'width'	=> '50px',
            'index'	=> 'id'
        ));

        $this->addColumn('title',array(
            'header' => __('Title'),
            'index'	=> 'title'
        ));

        $this->addColumn('created_time', array(
            'header'    => __('Date Created'),
            'index'     => 'created_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => __('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('quickresponses');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('webforms/quickresponse/massDelete', ['_current' => true]),
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
     * @param \VladimirPopov\WebForms\Model\Quickresponse|\Magento\Framework\DataObject $row
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
