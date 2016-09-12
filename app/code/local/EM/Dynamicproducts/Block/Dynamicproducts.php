<?php
class EM_Dynamicproducts_Block_Dynamicproducts extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{	
	protected function _construct()
    {
        parent::_construct();
        $cacheLifeTime = $this->getCacheLifeTime() ? $this->getCacheLifeTime() : 7200;
        $cacheTags = array(Mage_Catalog_Model_Product::CACHE_TAG,Mage_Cms_Model_Page::CACHE_TAG);
        if($this->ShowLabel() && Mage::helper('core')->isModuleEnabled('EM_Productlabels')){
            $cacheTags[] = EM_Productlabels_Model_Productlabels::CACHE_TAG;
        }
        $this->addData(array(
            'cache_lifetime'    => $cacheLifeTime,
            'cache_tags'        => $cacheTags
        ));
    }  
    
	public function getCacheKeyInfo()
	{
		return array(
			'dynamicproducts',
			Mage::app()->getStore()->getId(),
			(int)Mage::app()->getStore()->isCurrentlySecure(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::app()->getStore()->getCurrentCurrencyCode(),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
			serialize($this->getData())
		);
	}
	
	public function _prepareLayout()
	{	
		return parent::_prepareLayout();
	}

		protected function _toHtml()
	{	
		if($this->getData('choose_template')	==	'custom_template')
		{
			if($this->getData('custom_theme'))
				$this->setTemplate($this->getData('custom_theme'));	
			else 
				$this->setTemplate('em_featured_products/featured_custom.phtml');	
		}
		else
		{
			$this->setTemplate($this->getData('choose_template'));	
		}
		return parent::_toHtml();
	}
    
	public function getCategories()
	{
		$strCategories=  $this->getData('new_category');
		$arrCategories = explode(",", $strCategories);
		return $arrCategories;
	}
	
	public function getColumnCount(){
		return $this->getData('column_count');
	}
    
	public function getFeatureChoosed(){
		return $this->getData('featured_choose');
	}
    
	public function getCacheLifeTime(){		
	   return $this->getData('cache_lifetime');
	}
    
    public function getThumbnailWidth(){
        $tempwidth = $this->getData('thumbnail_width');
        if (!(is_numeric($tempwidth)))
            $tempwidth = 150;
        return $tempwidth;
	}
    
    public function getThumbnailHeight(){
        $tempheight = $this->getData('thumbnail_height');
       if (!(is_numeric($tempheight)))
            $tempheight = 150;
        return $tempheight;
	}
	
	public function getItemWidth(){
        $tempwidth = $this->getData('item_width');
        if (!(is_numeric($tempwidth)))
            $tempwidth = null;
        return $tempwidth;
	}
    
    public function getItemHeight(){
        $tempheight = $this->getData('item_height');
       if (!(is_numeric($tempheight)))
            $tempheight = null;
        return $tempheight;
	}
	
	public function getItemSpacing(){
        $tempheight = $this->getData('item_spacing');
       if (!(is_numeric($tempheight)))
            $tempheight = null;
        return $tempheight;
	}
    
    public function ShowThumb(){
        return $this->getData('show_thumbnail');
	}
    
    public function getAltImg(){
        return $this->getData('alt_img');
	}
    
    public function ShowProductName(){
        return $this->getData('show_product_name');
	}
    
    public function ShowDesc(){
        return $this->getData('show_description');
	}
    
    public function ShowPrice(){
        return $this->getData('show_price');
	}
    
    public function ShowReview(){
        return $this->getData('show_reviews');
	}
    
    public function ShowAddtoCart(){
        return $this->getData('show_addtocart');
	}
    
    public function ShowAddto(){
        return $this->getData('show_addto');
	}
    
    public function ShowLabel(){
        return $this->getData('show_label');
	}
    
    protected function getProductCollection()
	{
		$pageSize=10;
		 $config2 = $this->getData('order_by');
         if(isset($config2))
		{      
           $orders = explode(' ',$config2);
        }
		$products= Mage::getModel('catalog/product')->getCollection()
		//->setStoreId($storeId) // check lai trong multi store
		//->addStoreFilter($store_id) //lay cac san pham trong store hien tai
		->addAttributeToFilter('status', array('neq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED))
		/*
		->joinField(
			    'qty',
			    'cataloginventory/stock_item',
			    'qty',
			    'product_id=entity_id',
			    '{{table}}.stock_id=1',
			    'left'
			    )
			    ->addAttributeToFilter('qty', array('gt' => 0))//*/
			    ->addAttributeToFilter('visibility',array("neq"=>1))
			     ->addAttributeToFilter($this->getFeatureChoosed(), array('gt' => 0)); 
		//Sort		 
		if(count($orders))
			$products->addAttributeToSort($orders[0],$orders[1]);
		else
		$products->addAttributeToSort('name', 'asc');
		//Filter by categories	
		$config1 = $this->getData('new_category');
		if($config1)
		{
			$result = array();
			$condition_cat = array();
			$alias = 'cat_index';
			$categoryCondition = $products->getConnection()->quoteInto(
			$alias.'.product_id=e.entity_id AND '.$alias.'.store_id=? AND ',
			$products->getStoreId()
			);
			$categoryCondition.= $alias.'.category_id IN ('.$config1.')';
			$products->getSelect()->joinInner(
			array($alias => $products->getTable('catalog/category_product_index')),
			$categoryCondition,
			array()
			);
			$products->_categoryIndexJoined = true;
			$products->distinct(true);
		}
			//Page size & CurPage
			$pageSize = $this->getData('limit_count');
			$curPage = 1;
			
            $products->setPageSize($pageSize);
    
    	    $products->setCurPage($curPage);
        	
    	    $products->addAttributeToSelect('*');
            
        $this->_addProductAttributesAndPrices($products);
		return $products;

	}
}