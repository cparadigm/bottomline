<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Edit_Tab_History extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('historyGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('giftvoucher/history')
                ->getCollection()
                ->addFieldToFilter('giftvoucher_id', $this->getGiftvoucher());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('created_at', array(
            'header' => Mage::helper('giftvoucher')->__('Created at'),
            'align' => 'left',
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '160px',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('giftvoucher')->__('Action'),
            'align' => 'left',
            'index' => 'action',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/actions')->getOptionArray(),
        ));

        $this->addColumn('amount', array(
            'header' => Mage::helper('giftvoucher')->__('Value'),
            'align' => 'left',
            'index' => 'amount',
            'type' => 'currency',
            'currency' => 'currency',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('giftvoucher')->__('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/status')->getOptionArray(),
        ));

        $this->addColumn('order_increment_id', array(
            'header' => Mage::helper('giftvoucher')->__('Order'),
            'align' => 'left',
            'index' => 'order_increment_id',
            'renderer' => 'giftvoucher/adminhtml_giftvoucher_renderer_order',
        ));

        $this->addColumn('comments', array(
            'header' => Mage::helper('giftvoucher')->__('Comments'),
            'align' => 'left',
            'index' => 'comments',
        ));

        $this->addColumn('extra_content', array(
            'header' => Mage::helper('giftvoucher')->__('Action Created by'),
            'align' => 'left',
            'index' => 'extra_content',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return false;
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/historygrid', array(
                    '_current' => true,
                    'id' => $this->getGiftvoucher(),
        ));
    }

}