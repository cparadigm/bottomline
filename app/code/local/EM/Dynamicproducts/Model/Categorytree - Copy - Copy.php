<?php

class EM_Dynamicproducts_Model_Categorytree extends Mage_Core_Model_Abstract 
{

	public function toOptionArray()
	{
		//		$category = Mage::getModel('catalog/category');
		//		$tree = $category->getTreeModel();
		//		$tree->load();
		//		$ids = $tree->getCollection()->getAllIds();
		//
		//		$categories[]=array('value' => $this->getStoreCategories(),'label' =>  'abcdef');
		//		if ($ids){
		//			//			foreach ($ids as $id){
		//			foreach ($tree->getCollection() as $cat){
		//				//if($id < 3)
		//				//continue;
		//				//$cat = Mage::getModel('catalog/category');
		//				//$cat->load($id);
		//				$categories[]=array('value' => $cat->getId(),'label' =>  $this->getCatNameCustom($cat));
		//			}
		//		}
		//		return $categories;
		//		$_helper = Mage::helper('catalog/category') ;
		//		//$_categories = $_helper->getStoreCategories();
		//		$_categories = Mage::getModel('catalog/category')->getCollection();
		//		$categories[]=array('value' =>count($_categories) ,'label' =>  'So luong');
		//		if (count($_categories) > 0){
		//		 foreach($_categories as $_category)
		//		 {
		//		 	$categories[]=array('value' => $_category->getId(),'label' =>  $this->getCatNameCustom($_category));
		//		 	$_category = Mage::getModel('catalog/category')->load($_category->getId());
		//		 	$_subcategories = $_category->getChildrenCategories() ;
		//		 	if (count($_subcategories) > 0){
		//		 		foreach($_subcategories as $_subcategory){
		//		 			$categories[]=array('value' => $_subcategory->getId(),'label' =>  $this->getCatNameCustom($_subcategory));
		//		 			$_nextcategory = Mage::getModel('catalog/category')->load($_subcategory->getId()) ;
		//		 			$_nextsubcategories = $_nextcategory->getChildrenCategories();
		//		 			if (count($_nextsubcategories) > 0)
		//		 			{
		//		 				foreach($_nextsubcategories as $_nextsubcat)
		//		 				{
		//		 					$categories[]=array('value' => $_nextsubcat->getId(),'label' =>  $this->getCatNameCustom($_nextsubcat));
		//		 				}
		//		 			}
		//		 		}
		//		 	}
		//		 }
		//		}
		//	return $categories;
		//		$model	=	Mage::getModel('catalog/category');
		//		$rootCategoryId = 3; //Mage::app()->getStore()->getRootCategoryId();
		//		$category = $model->load($rootCategoryId);
		//		$result=array();
		//		$result = $this->getCategoriesCustom($category,$result);
		//		return $result;

		$store_cats = Mage::getModel('catalog/category')->getCollection();
		//$store_cats = $obj->getStoreCategories();
		$catID_select = 0 ; //$this->getData('category');
		$result;
		foreach ($store_cats as $cat)
		{
			if($catID_select==$cat->getId())
			{
				$result[]=array('value' => $cat->getId(),'label' =>  $this->getCatNameCustom($cat));
				foreach (Mage::getModel('catalog/category')->load($cat->getId())->getChildrenCategories() as $childCategory)
				{
					$result[]=array('value' => $cat->getId(),'label' =>  $this->getCatNameCustom($cat));
				}
				
			}
			else		
			{
				$result[]=array('value' => $cat->getId(),'label' =>  $this->getCatNameCustom($cat));			
				foreach (Mage::getModel('catalog/category')->load($cat->getId())->getChildrenCategories() as $childCategory)
				{
					$result[]=array('value' => $childCategory->getId(),'label' =>  $childCategory->getName());
				}
				
			}
		}
		return $result;
	}
	
	function getCategoriesCustom($parent,$result){
		$result[]=array('value' => count($parent->getChildrenCategories()),'label' =>  $this->getCatNameCustom($parent));
		$childrens =  $parent->getChildrenCategories();
		//		if(count($childrens)){
		//			foreach($childrens as $child){
		//				$result[] = $this->getCategoriesCustom($child,$result);
		//			}
		//		}
		return $result;
	}

	function getCatNameCustom($category)
	{
		$level = $category->getLevel();
		$html = '';
		for($i = 0;$i < $level;$i++){ $html .= $i; }
		 return	$html.' '.$category->getName();
	}
}
?>
