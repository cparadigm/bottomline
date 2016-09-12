<?php
class EM_Productlabels_Block_Adminhtml_Productlabels extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_productlabels';
    $this->_blockGroup = 'productlabels';
    $this->_headerText = Mage::helper('productlabels')->__('Product labels Manager');
    $this->_addButtonLabel = Mage::helper('productlabels')->__('Add New Label');
    parent::__construct();
    $this->setTemplate('em_productlabels/grid/container.phtml');
    $this->_addButton('css', array(
        'label'     => $this->__('CSS Editor'),
        'onclick'   => 'setLocation(\'' . $this->getCssUrl() .'\')',
        'class'     => 'css',
    ));
  }

  public function _prepareLayout()
  {
        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
            );
        }
        parent::_prepareLayout();
  }

  public function getCssUrl()
    {
        return $this->getUrl('adminhtml/csslabel/edit');
    }
}