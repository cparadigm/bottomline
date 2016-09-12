<?php

class EM_Slideshow2_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('sliderGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('slideshow2/slider')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
          'header'    => Mage::helper('slideshow2')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('slideshow2')->__('Slideshow name'),
          'align'     =>'left',
          'index'     => 'name',
      ));

	  
      $this->addColumn('slider_type', array(
			'header'    => Mage::helper('slideshow2')->__('Type slideshow'),
			'width'     => '150px',
			'index'     => 'slider_type',
      ));
	 

      $this->addColumn('status', array(
          'header'    => Mage::helper('slideshow2')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              0 => 'Disabled',
          ),
      ));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('slideshow2');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('slideshow2')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('slideshow2')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('slideshow2/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('slideshow2')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('slideshow2')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}