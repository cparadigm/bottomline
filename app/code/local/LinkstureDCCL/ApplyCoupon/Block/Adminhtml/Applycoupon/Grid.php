<?php

class LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  
   public function __construct()
   {

      parent::__construct();
       $this->setId('id');
       $this->setDefaultSort('id');
       $this->setDefaultDir('ASC');
       $this->setSaveParametersInSession(true);

   }
   protected function _prepareCollection()
   {
      $collection = Mage::getModel('applycoupon/applycoupon')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();

    }
   protected function _prepareColumns()
   {
      $this->addColumn('id',
        array(
              'header' => 'ID',
              'align' =>'right',
              'width' => '50px',
              'index' => 'id',
             ));
      $this->addColumn('rule_name',
        array(
              'header' => 'Rule Name',
              'align' =>'left',
              'index' => 'rule_name',
            ));
      $this->addColumn('coupon_code',
        array(
              'header' => 'Coupon Code',
              'align' =>'left',
              'index' => 'coupon_code',
             ));
      $this->addColumn('websites',
        array(
              'header' => 'Websites',
              'align' =>'left',
              'index' => 'websites',
              'renderer'  => 'LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Renderer_Website'
             ));
      $this->addColumn('redirect_url', 
        array(
              'header' => 'Redirect URL',
              'width' => '200px',
              'align' =>'left',
              'renderer' => 'applycoupon/adminhtml_widget_grid_column_renderer_inline',
              'index' => 'redirect_url',
            ));
      $this->addColumn('link_with_redirection',
        array(
              'header' => 'Link With Redirection',
              'width' => '400px',
              'align' =>'left',
              'index' => 'link_with_redirection',
              'renderer'  => 'LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Renderer_linkwithredirection'
             ));
      $this->addColumn('link_without_redirection',
        array(
              'header' => 'Link Without Redirection',
              'width' => '400px',
              'align' =>'left',
              'index' => 'link_without_redirection',
              'renderer'  => 'LinkstureDCCL_ApplyCoupon_Block_Adminhtml_Applycoupon_Renderer_linkwithoutredirection'
            ));
      $this->addColumn('views', 
        array(
              'header' => 'No. of Views',
              'align' =>'left',
              'index' => 'views',
             ));
      $this->addColumn('status', array(
            'header' =>  'Status',
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));
      $this->addColumn('action',
        array(
              'header' => 'Delete',
              'width' => '50px',
              'type' => 'action',
              'actions' => array(
                  array(
                      'caption' =>  'Delete',
                      'url' => array(
                          'base' => '/applycoupon/delete',
                      ),
                      'field' => 'id',
                      'confirm' => Mage::helper('applycoupon')->__('Are you sure?')
                  ),
              ),
              'filter' => false,
              'sortable' => false,
              'index' => 'id',
            ));
      
         return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('applycoupon');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('applycoupon')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('applycoupon')->__('Are you sure?')
        ));
        return $this;
    }
}