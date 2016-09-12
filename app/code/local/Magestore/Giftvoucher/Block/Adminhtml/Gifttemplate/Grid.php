<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('gifttemplateGrid');
        $this->setDefaultSort('giftcard_template_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('giftvoucher/gifttemplate')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('giftcard_template_id', array(
            'header' => Mage::helper('giftvoucher')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'giftcard_template_id',
        ));
        $this->addColumn('template_name', array(
            'header' => Mage::helper('giftvoucher')->__('Template Name'),
            'align' => 'left',
            'index' => 'template_name',
        ));
        $this->addColumn('design_pattern', array(
            'header' => Mage::helper('giftvoucher')->__('Template Design'),
            'align' => 'left',
            'index' => 'design_pattern',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/designpattern')->getOptionArray(),
            'width' => '80px',
        ));
        $this->addColumn('caption', array(
            'header' => Mage::helper('giftvoucher')->__('Title'),
            'align' => 'left',
            'index' => 'caption',
        ));

        $this->addColumn('style_color', array(
            'header' => Mage::helper('giftvoucher')->__('Style Color'),
            'align' => 'left',
            'index' => 'style_color',
//            'renderer' => 'giftvoucher/adminhtml_gifttemplate_background',
        ));
        $this->addColumn('text_color', array(
            'header' => Mage::helper('giftvoucher')->__('Text Color'),
            'align' => 'left',
            'index' => 'text_color',
//            'renderer' => 'giftvoucher/adminhtml_gifttemplate_background',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('giftvoucher')->__('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/statusgifttemplate')->getOptionArray(),
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('giftvoucher')->__('Action'),
            'width' => '70px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('giftvoucher')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('giftvoucher')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('giftvoucher')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('giftcard_template_id');
        $this->getMassactionBlock()->setFormFieldName('gifttemplate');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('giftvoucher')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('giftvoucher')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('giftvoucher/statusgifttemplate')->getOptionHash();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('giftvoucher')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('giftvoucher')->__('Status'),
                    'values' => $statuses
                )
            )
        ));



        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
