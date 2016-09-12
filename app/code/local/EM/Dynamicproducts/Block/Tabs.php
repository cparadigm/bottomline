<?php
/**
 * Magento
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
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Tabs block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class EM_Dynamicproducts_Block_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
   protected $_attributeTabBlock = 'adminhtml/catalog_product_edit_tab_attributes';

	public function __construct()
	{
		parent::__construct();
		$this->setId('product_info_tabs');
		$this->setDestElementId('product_edit_form');
		$this->setTitle(Mage::helper('catalog')->__('Product Information'));
	}

	protected function _prepareLayout()
	{
		$this->addTab('categories', array(
                'label'     => Mage::helper('catalog')->__('Categories'),
                'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
                'class'     => 'ajax',
			));

			$this->addTab('inventory hugo', array(
			    'label'     => Mage::helper('catalog')->__('Inventory hugo'),
     			'content'   => $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_inventory')->toHtml(),
			));
				
		return parent::_prepareLayout();
	}

	/**
	 * Retrive product object from object if not from registry
	 *
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct()
	{
		if (!($this->getData('product') instanceof Mage_Catalog_Model_Product)) {
			$this->setData('product', Mage::registry('product'));
		}
		return $this->getData('product');
	}

	/**
	 * Getting attribute block name for tabs
	 *
	 * @return string
	 */
	public function getAttributeTabBlock()
	{
		if (is_null(Mage::helper('adminhtml/catalog')->getAttributeTabBlock())) {
			return $this->_attributeTabBlock;
		}
		return Mage::helper('adminhtml/catalog')->getAttributeTabBlock();
	}

	public function setAttributeTabBlock($attributeTabBlock)
	{
		$this->_attributeTabBlock = $attributeTabBlock;
		return $this;
	}

	/**
	 * Translate html content
	 *
	 * @param string $html
	 * @return string
	 */
	protected function _translateHtml($html)
	{
		Mage::getSingleton('core/translate_inline')->processResponseBody($html);
		return $html;
	}
}
