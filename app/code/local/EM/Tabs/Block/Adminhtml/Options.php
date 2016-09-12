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
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


/**
 * Widget which displays the social bookmarking services list
 *
 * @category    EM
 * @package     EM_Tabs
 * @author      Emthemes <emthemes.com>
 */
class EM_Tabs_Block_Adminhtml_Options
extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract 
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('emtabs/options.phtml');
    }
	
	/**
     * Retrieve frontend labels of attribute for each store
     *
     * @return array
     */
    public function getLabelValues()
    {
        $values = array();
		$titleArray = array();
		$name = str_replace(array('parameters','[',']'),'',$this->getData('name'));
		if(Mage::registry('current_widget_instance')){
			/* Get values title from widget instance */
			$params = Mage::registry('current_widget_instance')->getWidgetParameters();	
			if(is_array($params[$name])){
				$titleArray = $params[$name];
			}
		}else if($paramsJson = $this->getRequest()->getParam('widget')){
			/* Get values title from widget in static block(static page) */
			$request = Mage::helper('core')->jsonDecode($paramsJson);
			if(is_array($request['values'])){
				if(isset($request['values'][$name])){
					$titleArray = unserialize(base64_decode($request['values'][$name]));
				}
			}			
		}
		
		if(count($titleArray) > 0){
			foreach($this->getStores() as $store){
				$values[$store->getId()] = isset($titleArray[$store->getId()]) ? $titleArray[$store->getId()] : '';
			}
		}
        return $values;
    }
}
