<?php
class EM_Productlabels_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/productlabels?id=15 
    	 *  or
    	 * http://site.com/productlabels/id/15 	
    	 */
    	/* 
		$productlabels_id = $this->getRequest()->getParam('id');

  		if($productlabels_id != null && $productlabels_id != '')	{
			$productlabels = Mage::getModel('productlabels/productlabels')->load($productlabels_id)->getData();
		} else {
			$productlabels = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($productlabels == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$productlabelsTable = $resource->getTableName('productlabels');
			
			$select = $read->select()
			   ->from($productlabelsTable,array('productlabels_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$productlabels = $read->fetchRow($select);
		}
		Mage::register('productlabels', $productlabels);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}