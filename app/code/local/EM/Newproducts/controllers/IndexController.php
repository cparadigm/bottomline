<?php
class EM_Newproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/newproducts?id=15 
    	 *  or
    	 * http://site.com/newproducts/id/15 	
    	 */
    	/* 
		$newproducts_id = $this->getRequest()->getParam('id');

  		if($newproducts_id != null && $newproducts_id != '')	{
			$newproducts = Mage::getModel('newproducts/newproducts')->load($newproducts_id)->getData();
		} else {
			$newproducts = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($newproducts == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$newproductsTable = $resource->getTableName('newproducts');
			
			$select = $read->select()
			   ->from($newproductsTable,array('newproducts_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$newproducts = $read->fetchRow($select);
		}
		Mage::register('newproducts', $newproducts);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}