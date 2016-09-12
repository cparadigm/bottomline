<?php
class EM_Recentreviewproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/recentreviewproducts?id=15 
    	 *  or
    	 * http://site.com/recentreviewproducts/id/15 	
    	 */
    	/* 
		$recentreviewproducts_id = $this->getRequest()->getParam('id');

  		if($recentreviewproducts_id != null && $recentreviewproducts_id != '')	{
			$recentreviewproducts = Mage::getModel('recentreviewproducts/recentreviewproducts')->load($recentreviewproducts_id)->getData();
		} else {
			$recentreviewproducts = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($recentreviewproducts == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$recentreviewproductsTable = $resource->getTableName('recentreviewproducts');
			
			$select = $read->select()
			   ->from($recentreviewproductsTable,array('recentreviewproducts_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$recentreviewproducts = $read->fetchRow($select);
		}
		Mage::register('recentreviewproducts', $recentreviewproducts);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}