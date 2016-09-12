<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
class EM_Themeframework_Model_Observer extends Varien_Object
{
	protected $_pages = null;
	protected function initPages($handles){
		if(!$this->_pages){
			$pages = Mage::getSingleton('themeframework/page')->getCollection()->addFieldToFilter('status',1)
				->addStoreFilter(Mage::app()->getStore()->getId());

			// add or condition for handle & custom_handle attribute
			foreach($handles as &$handle)
				$handle = '\'' . $handle . '\'';
			$cond = implode(",", $handles);
			$where = $pages->getSelect()->getPart(Zend_Db_Select::WHERE);
			$where[] = " AND (handle IN ($cond) OR custom_handle IN ($cond))";
			$pages->getSelect()->setPart(Zend_Db_Select::WHERE, $where);
			
			$pages->getSelect()->order('sort DESC');	
			$this->_pages = $pages;	
		}
		return $this->_pages;	
	}
	
	/* Update template */
    public function changeTemplateEvent($observer) {
		$handles = $observer->getEvent()->getLayout()->getUpdate()->getHandles();
		$pages = $this->initPages($handles);
		if(!$pages->count())
			return $this;
		
		$layout = '';
		
		foreach($pages as $page){
			if((in_array($page->getHandle(),$handles) || $page->getHandle() == 'custom_handle') && ($page->getLayout()))
				$layout = $page->getLayout();
		}
		$observer->getEvent()->getAction()->getLayout()->helper('page/layout')->applyTemplate($layout);
    }

	/* Update custom layout */
	public function changeLayoutEvent($observer){
		$handles = $observer->getEvent()->getLayout()->getUpdate()->getHandles();
		$pages = $this->initPages($handles);
		if(!$pages->count())
			return $this;
		$update = $observer->getEvent()->getLayout()->getUpdate();
		foreach($pages as $page){
			if(in_array($page->getHandle(),$handles) || $page->getHandle() == 'custom_handle'){
				$layoutUpdate = $page->getLayoutUpdateXml();
				if(!empty($layoutUpdate)){
					$update->addUpdate($layoutUpdate);
				}	
			}			
		}
		return $this;
	}	
	
	public function processAfterHtmlDispatch($observer) {
		
		$cookie = Mage::getSingleton('core/cookie');
		$key = $cookie->get('EDIT_BLOCK_KEY');
		if (!$key || !$cookie->get('adminhtml')) return;
		
		$block = $observer->getEvent()->getData('block');
		$name = $block->getNameInLayout();
		
		// is static block
		if (is_a($block, 'Mage_Cms_Block_Block') || is_a($block, 'Mage_Cms_Block_Widget_Block')) {
			$block_id = $block->getBlockId();
			$model = Mage::getModel('cms/block')
				->setStoreId(Mage::app()->getStore()->getId())
				->load($block_id);
			if (!($id = $model->getId())) $id = $block_id;
			
			$title = $model->getTitle();
			$transport = $observer->getEvent()->getTransport();
			
			$html = trim($transport->getHtml());
			$transport->setHtml($html
				."<div class=\"em_themeframework_previewblock".(!$html ? ' empty' : '')."\" style=\"display:none\">"
				."<a target=\"_blank\" href=\"".Mage::helper('adminhtml')->getUrl("adminhtml/cms_block/edit", array('block_id' => $id, 'key' => $key))."\">$title</a>"
				."</div>");
		} 
		// is widget
		elseif (strlen($name) == 32 && preg_replace('/[^a-z0-9]/', '', $name) == $name) {
			$transport = $observer->getEvent()->getTransport();
			$html = trim($transport->getHtml());
			$transport->setHtml($html
				."<div class=\"em_themeframework_previewblock".(!$html ? ' empty' : '')."\" style=\"display:none\">"
				."Widget ".$block->getType()
				."<br/><span class=\"path\">".$block->getTemplateFile()."</span>"
				."</div>");

		}
	}
	
	/**
     * Add typography wysiwyg plugin config
     *
     * @param Varien_Event_Observer $observer
     * @return EM_Themeframework_Model_Observer
     */
    public function prepareWysiwygPluginConfig(Varien_Event_Observer $observer)
    {
        $config = $observer->getEvent()->getConfig();
        $settings = Mage::getModel('themeframework/typography_config')->getWysiwygPluginSettings($config);
        $config->addData($settings);
        return $this;
    }
}
?>
