<?php
class EM_Newproducts_Block_List extends Mage_Catalog_Block_Product_Abstract
implements Mage_Widget_Block_Interface
{
	protected function _construct()
	{
		if($this->getCacheLifeTime())
		{
			$this->addData(array(
				'cache_lifetime'    => $this->getCacheLifeTime(),
				'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG)
			));
		}
		else
		{
			$this->addData(array(
				'cache_lifetime'    => 7200,
				'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG)
			));
		}
		parent::_construct();

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
			$this->setTemplate('em_new_products/new_custom.phtml');
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
		if($this->getData('column_count')!="")
		return $this->getData('column_count');
		return -1;
	}

	public function getCustomClass($temp){
		if ($this->getData('custom_class'))
		return $this->getData('custom_class');
		else
		return $temp;
	}

	public function getLimitCount(){
		return $this->getData('limit_count');
	}

	public function getFeatureChoosed(){
		return $this->getData('featured_choose');
	}

	public function getOrderBy(){
		return $this->getData('order_by');
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
	
	public function getItemClass($temp){
		if ($this->getData('item_class'))
		return $this->getData('item_class');
		else
		return $temp;
	}
	
	public function getFrontendTitle(){
		return $this->getData('frontend_title');
	}

	public function getFrontendDescription(){
		return $this->getData('frontend_description');
	}

	public function ShowThumb(){
		return $this->getData('show_thumbnail');
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
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
		$products= Mage::getModel('catalog/product')->getCollection()
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
		 ->addAttributeToFilter('qty', array('gt' => 0)) Doan nay chi lay ra duoc simple product */
		->addAttributeToFilter('visibility',array("neq"=>1))
		->addAttributeToFilter('news_from_date', array('or'=> array(
		0 => array('date' => true, 'to' => $todayDate),
		1 => array('is' => new Zend_Db_Expr('null')))
		), 'left')
		->addAttributeToFilter('news_to_date', array('or'=> array(
		0 => array('date' => true, 'from' => $todayDate),
		1 => array('is' => new Zend_Db_Expr('null')))
		), 'left')
		->addAttributeToFilter(
		array(
		array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
		array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
		)
		);
		//Sort
		$config2 = $this->getData('order_by');
		if(isset($config2))
		{
			$orders = explode(' ',$config2);
		}
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
			
		return $products;
	}
}
?>
