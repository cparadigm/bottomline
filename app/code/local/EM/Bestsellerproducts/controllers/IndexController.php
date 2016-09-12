<?php
class EM_Bestsellerproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/bestsellerproducts?id=15 
    	 *  or
    	 * http://site.com/bestsellerproducts/id/15 	
    	 */
    	/* 
		$bestsellerproducts_id = $this->getRequest()->getParam('id');

  		if($bestsellerproducts_id != null && $bestsellerproducts_id != '')	{
			$bestsellerproducts = Mage::getModel('bestsellerproducts/bestsellerproducts')->load($bestsellerproducts_id)->getData();
		} else {
			$bestsellerproducts = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($bestsellerproducts == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$bestsellerproductsTable = $resource->getTableName('bestsellerproducts');
			
			$select = $read->select()
			   ->from($bestsellerproductsTable,array('bestsellerproducts_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$bestsellerproducts = $read->fetchRow($select);
		}
		Mage::register('bestsellerproducts', $bestsellerproducts);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}