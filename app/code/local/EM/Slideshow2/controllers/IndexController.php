<?php
class EM_Slideshow2_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/slideshow2?id=15 
    	 *  or
    	 * http://site.com/slideshow2/id/15 	
    	 */
    	/* 
		$slideshow2_id = $this->getRequest()->getParam('id');

  		if($slideshow2_id != null && $slideshow2_id != '')	{
			$slideshow2 = Mage::getModel('slideshow2/slideshow2')->load($slideshow2_id)->getData();
		} else {
			$slideshow2 = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($slideshow2 == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$slideshow2Table = $resource->getTableName('slideshow2');
			
			$select = $read->select()
			   ->from($slideshow2Table,array('slideshow2_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$slideshow2 = $read->fetchRow($select);
		}
		Mage::register('slideshow2', $slideshow2);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}