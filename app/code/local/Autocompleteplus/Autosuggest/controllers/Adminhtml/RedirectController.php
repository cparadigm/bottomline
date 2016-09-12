<?php

class Autocompleteplus_Autosuggest_Adminhtml_RedirectController extends Mage_Adminhtml_Controller_Action
{
  public function   gotoAction(){
    //
    $helper=Mage::helper('autocompleteplus_autosuggest');

    $kwys=$helper->getBothKeys();
    
    if(!isset($kwys['uuid']) || !isset($kwys['authkey'])){
       $url='https://magento.instantsearchplus.com/login';
    }else{
       $url='http://magento.instantsearchplus.com/ma_dashboard?site_id='.$kwys['uuid'].'&authentication_key='.$kwys['authkey'];
    }
    
    header("Location: ".$url);
    
    die;
  }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/autocompleteplus');
    }
}