<?php
class EM_Saleproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/saleproducts?id=15 
    	 *  or
    	 * http://site.com/saleproducts/id/15 	
    	 */
    	/* 
		$saleproducts_id = $this->getRequest()->getParam('id');

  		if($saleproducts_id != null && $saleproducts_id != '')	{
			$saleproducts = Mage::getModel('saleproducts/saleproducts')->load($saleproducts_id)->getData();
		} else {
			$saleproducts = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($saleproducts == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$saleproductsTable = $resource->getTableName('saleproducts');
			
			$select = $read->select()
			   ->from($saleproductsTable,array('saleproducts_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$saleproducts = $read->fetchRow($select);
		}
		Mage::register('saleproducts', $saleproducts);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}