<?php
class Magik_Autocomplete_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/autocomplete?id=15 
    	 *  or
    	 * http://site.com/autocomplete/id/15 	
    	 */
    	/* 
		$autocomplete_id = $this->getRequest()->getParam('id');

  		if($autocomplete_id != null && $autocomplete_id != '')	{
			$autocomplete = Mage::getModel('autocomplete/autocomplete')->load($autocomplete_id)->getData();
		} else {
			$autocomplete = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($autocomplete == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$autocompleteTable = $resource->getTableName('autocomplete');
			
			$select = $read->select()
			   ->from($autocompleteTable,array('autocomplete_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$autocomplete = $read->fetchRow($select);
		}
		Mage::register('autocomplete', $autocomplete);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}