<?php
/**
 * EMThemes
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the framework to newer
 * versions in the future. If you wish to customize the framework for your
 * needs please refer to http://www.emthemes.com/ for more information.
 *
 * @category    EM
 * @package     EM_ThemeFramework
 * @copyright   Copyright (c) 2012 CodeSpot JSC. (http://www.emthemes.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Giao L. Trinh (giao.trinh@emthemes.com)
 */

class EM_Themeframework_Model_Layout_Update extends Mage_Core_Model_Layout_Update
{
	protected function _getPages($handle) {
		$pages = Mage::getSingleton('themeframework/page')->getCollection()
			->addFieldToFilter('status',1)
			->addStoreFilter(Mage::app()->getStore()->getId());

		// add or condition for handle & custom_handle attribute
		$where = $pages->getSelect()->getPart(Zend_Db_Select::WHERE);
		$where[] = " AND (handle = '{$handle}' OR custom_handle = '{$handle}')";
		$pages->getSelect()->setPart(Zend_Db_Select::WHERE, $where);
		$pages->getSelect()->order('sort DESC');
		//echo $pages->getSelect(); die;
		return $pages;
	}
	
    public function merge($handle) {
		$packageUpdatesStatus = $this->fetchPackageLayoutUpdates($handle);
		
		if (Mage::app()->isInstalled()) {
			
			// add EMFramework layout update
			$pages = $this->_getPages($handle);
			if($pages->count()) {
				foreach($pages as $page){
					$layoutUpdate = $page->getLayoutUpdateXml();
					if(!empty($layoutUpdate)) $this->addUpdate($layoutUpdate);
				}
			}
			
			$this->fetchDbLayoutUpdates($handle);
		}

		return $this;
	}
}