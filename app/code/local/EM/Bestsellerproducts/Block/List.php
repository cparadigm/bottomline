<?php
class EM_Bestsellerproducts_Block_List extends Mage_Catalog_Block_Product_Abstract
implements Mage_Widget_Block_Interface
{
	protected function _construct()
    {
        parent::_construct();
        $cacheLifeTime = $this->getCacheLifeTime() ? $this->getCacheLifeTime() : 7200;
        $cacheTags = array(Mage_Catalog_Model_Product::CACHE_TAG,Mage_Cms_Model_Page::CACHE_TAG,'em_bestsellerproducts');
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
			'bestsellerproducts',
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
				$this->setTemplate('em_bestseller_products/bestseller_custom.phtml');	
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
    
    /* --------*/
    public function getCacheLifeTime(){		
		return $this->getData('cache_lifetime');
	}
    
	public function getColumnCount(){
		return $this->getData('column_count');
	}
	
	public function getLimitCount(){
		return $this->getData('limit_count');
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

    protected function getBestsellerProduct()
	{		
		$strCategories = $this->getData('new_category');
		if($strCategories)
		{
			$query = "
						SELECT DISTINCT SUM( order_items.qty_ordered ) AS  `ordered_qty` ,  `order_items`.`name` AS  `order_items_name` ,  `order_items`.`product_id` AS  `entity_id` ,  `e`.`entity_type_id` ,  `e`.`attribute_set_id` , `e`.`type_id` ,  `e`.`sku` ,  `e`.`has_options` ,  `e`.`required_options` ,  `e`.`created_at` ,  `e`.`updated_at` 
						FROM  `".Mage::getSingleton('core/resource')->getTableName('sales_flat_order_item')."` AS  `order_items` 
						INNER JOIN  `".Mage::getSingleton('core/resource')->getTableName('sales_flat_order')."` AS  `order` ON  `order`.entity_id = order_items.order_id
						AND  `order`.state <>  'canceled'
						LEFT JOIN  `".Mage::getSingleton('core/resource')->getTableName('catalog_product_entity')."` AS  `e` ON e.entity_id = order_items.product_id
						INNER JOIN  `".Mage::getSingleton('core/resource')->getTableName('catalog_product_website')."` AS  `product_website` ON product_website.product_id = e.entity_id
						AND product_website.website_id =  '1'
						INNER JOIN  `".Mage::getSingleton('core/resource')->getTableName('catalog_category_product_index')."` AS  `cat_index` ON cat_index.product_id = e.entity_id
						AND cat_index.store_id =1
						AND cat_index.category_id
						IN ( ".$strCategories." ) 
						WHERE (
						parent_item_id IS NULL
						)
						GROUP BY  `order_items`.`product_id` 
						HAVING (
						SUM( order_items.qty_ordered ) >0
						)
						ORDER BY  `ordered_qty` DESC 
						LIMIT 0 ,".$this->getLimitCount()."
					";
		 
		}else
		{
			$query = "	SELECT SUM( order_items.qty_ordered ) AS  `ordered_qty` ,  `order_items`.`name` AS  `order_items_name` ,  `order_items`.`product_id` AS  `entity_id` ,  `e`.`entity_type_id` ,  `e`.`attribute_set_id` , `e`.`type_id` ,  `e`.`sku` ,  `e`.`has_options` ,  `e`.`required_options` ,  `e`.`created_at` ,  `e`.`updated_at` 
						FROM  `".Mage::getSingleton('core/resource')->getTableName('sales_flat_order_item')."` AS  `order_items` 
						INNER JOIN  `".Mage::getSingleton('core/resource')->getTableName('sales_flat_order')."` AS  `order` ON  `order`.entity_id = order_items.order_id
						AND  `order`.state <>  'canceled'
						LEFT JOIN  `".Mage::getSingleton('core/resource')->getTableName('catalog_product_entity')."` AS  `e` ON e.entity_id = order_items.product_id
						INNER JOIN  `".Mage::getSingleton('core/resource')->getTableName('catalog_product_website')."` AS  `product_website` ON product_website.product_id = e.entity_id
						AND product_website.website_id =  '".Mage::app()->getWebsite()->getId()."'
						WHERE (
						parent_item_id IS NULL
						)
						GROUP BY  `order_items`.`product_id` 
						HAVING (
						SUM( order_items.qty_ordered ) >0
						)
						ORDER BY  `ordered_qty` DESC 
						LIMIT 0 ,".$this->getLimitCount()."
						";
		}

		
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
        return $readConnection->fetchAll($query);

	}
    
    public function getProductCollection(){
		$_bestseller_products = $this->getBestsellerProduct();
		$_temp_productIds = array();
		$count=0; 
		$limit = $this->getData('limit_count');

        /**
         * Build up a case statement to ensure the order of ids is preserved
         */
        $orderString = array('CASE e.entity_id');

        foreach ($_bestseller_products as $i => $_product){
		
			if(in_array($_product['entity_id'],$_temp_productIds))
			{
				continue;
			}
			else
			{
				$_temp_productIds[] = $_product['entity_id'];
                $orderString[] = 'WHEN '.$_product['entity_id'].' THEN '.$i;
				$count++;
				if($count == $limit)
				{
					break;
				}
			}
		}

        $orderString[] = 'END';
        $orderString = implode(' ', $orderString);

		$products= Mage::getModel('catalog/product')->getCollection()
			->addAttributeToFilter('status', array('neq' => Mage_Catalog_Model_Product_Status::STATUS_DISABLED))
		    ->addAttributeToFilter('visibility',array("neq"=>1))
			->addAttributeToFilter('entity_id',array('in' => $_temp_productIds))
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes());
		if($_temp_productIds){
			$products->getSelect()
				->order(new Zend_Db_Expr($orderString));
		}
		return $products;	
	}
}
?>
