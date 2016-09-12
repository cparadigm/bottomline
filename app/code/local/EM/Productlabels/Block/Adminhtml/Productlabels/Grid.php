<?php
class EM_Productlabels_Block_Adminhtml_Productlabels_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('productlabelsGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

   
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('productlabels/productlabels')->getCollection()->setStoreId($this->getRequest()->getParam('store',0));
      $collection->addAttributeToSelect('*');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
          'header'    => Mage::helper('productlabels')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('productlabels')->__('Title'),
          'align'     =>'left',
          'index'     => 'name',
      ));

	

      $this->addColumn('status', array(
          'header'    => Mage::helper('productlabels')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          //'filter_index' => 'enabletbl.value',
          'options'   => array(
              1 => 'Enabled',
              0 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('productlabels')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('productlabels')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('productlabels')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('productlabels')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('productlabels_id');
        $this->getMassactionBlock()->setFormFieldName('productlabels');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('productlabels')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('productlabels')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('productlabels/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('productlabels')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true,'store' => $this->getRequest()->getParam('store',0))),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('productlabels')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id'    => $row->getId(),
                                             'store' => $this->getRequest()->getParam('store',0))
                          );
  }

}