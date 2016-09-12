<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    EM
 * @package     EM_Tabs
 */


/**
 * Widget which displays tab system
 *
 * @category    EM
 * @package     EM_Tabs
 * @author      Emthemes <emthemes.com>
 */
class EM_Tabs_Block_Group
extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
	/* 
		Get directives of widget 
		param : int $number -> order of widget instance
		return : {{widget type='...' ...}}
	*/
	public function getWidgetInstance($number = 1){
		
		$idInstance = $this->getData('instance_'.$number);
		$directives = '';
		if($idInstance){
			$instance = Mage::getModel('widget/widget_instance')->load($idInstance);
			if(!count(array_intersect(array(0,Mage::app()->getStore()->getId()),$instance->getStoreIds())))
				return '';
			$params = $instance->getWidgetParameters();
			$pageGroups = $instance->getData('page_groups');
			$handles = Mage::app()->getFrontController()->getAction()->getLayout()->getUpdate()->getHandles();
			if(is_array($pageGroups)){
				foreach($pageGroups as $page){
					if(in_array($page['layout_handle'],$handles)){
						$params['template'] = $page['page_template'];
						break;
					}	
				}
			}
			$directives = Mage::getSingleton('widget/widget')->getWidgetDeclaration($instance->getInstanceType(),$params);
		}		
		return $directives;
	}
	
	/* 
		Get static block content
		param : int $number -> order of static block
		return : string
	*/
	public function getStaticBlock($number = 1){
		$idBlock = $this->getData('block_'.$number);
		if($idBlock){
			return Mage::getModel('cms/block')->load($idBlock)->getContent();
		}
		return '';
	}
	/*
		Get Title for each tab in group tab
		param : int $number -> order of tab
		return : string
	*/
	public function getTitleTab($number = 1){
		$storeId = Mage::app()->getStore()->getId();
		if($this->getData('instance')){
			$params = Mage::getSingleton('widget/widget_instance')->load($this->getData('instance'))->getWidgetParameters();
			$titleTab = ($params['title_'.$number][$storeId]) ? $params['title_'.$number][$storeId] : $params['title_'.$number][0];
		}
		else if($this->getData('title_'.$number)){
			$titleArray = unserialize(base64_decode($this->getData('title_'.$number)));
			$titleTab = ($titleArray[$storeId]) ? $titleArray[$storeId] : $titleArray[0];
		}	
		return $titleTab;
	}
}
