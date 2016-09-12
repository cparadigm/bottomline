<?php
require_once "Mage/CatalogSearch/controllers/ResultController.php";

class Magik_Autocomplete_ResultController extends Mage_CatalogSearch_ResultController
{
   /**
     * Display search result
     */
    public function indexAction()
    {
        $query = Mage::helper('catalogsearch')->getQuery();

        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());
	
        if ($query->getQueryText()) {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }
		
                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else { 
                    echo $query->prepare();
                }
            }
	   
            Mage::helper('catalogsearch')->checkNotes();
	    $url=$this->getCall($query->getQueryText());
	
	    if($url!='')	
	    {	
		$this->_redirectUrl($url);
	    }
	    else {	 		
            $this->loadLayout();
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();	
	    }	
            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
        else {
            $this->_redirectReferer();
        }
    }

	public function getCall($name)
	{
		$product_name = $name; //product name
		$_product = Mage::getModel('catalog/product')->loadByAttribute('name', $name);
		
		if(is_object($_product))
		{			
			if($this->isConfigurable($_product)!='')
				return 	$this->isConfigurable($_product);
			else
				return $_product->getProductUrl();
		}
			
	}
   	public function isConfigurable($_product)
	{
		$configurable_product = Mage::getModel('catalog/product_type_configurable');
            	$parentIdArray = $configurable_product->getParentIdsByChild($_product->getId());		
		if(count($parentIdArray)>0) 
	        {  
			$father = Mage::getModel('catalog/product')->load($parentIdArray[0]); 
			$type_p = $father->getTypeId(); 
			if($type_p == 'configurable') 
			{   
				return $father->getProductUrl();
			}
		}
	}	

}
