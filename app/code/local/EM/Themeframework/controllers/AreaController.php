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
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Themeframework Area controller
 *
 * @category   Mage
 * @package    Mage_Cms
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class EM_Themeframework_AreaController extends Mage_Core_Controller_Front_Action
{
	public function previewBlockAction() {
		$key = $this->getRequest()->getParam('key');
		Mage::getSingleton('core/cookie')->set('EDIT_BLOCK_KEY', $key);
		$this->getResponse()->setRedirect(Mage::getBaseUrl());
    }

	public function disablePreviewAction() {
		Mage::getSingleton('core/cookie')->delete('EDIT_BLOCK_KEY');
		Mage::getSingleton('core/cookie')->delete('PREVIEW_AREA');
		$this->getResponse()->setRedirect(Mage::getBaseUrl());
	}
	
	public function previewAreaAction() {
		Mage::getSingleton('core/cookie')->set('PREVIEW_AREA', 1);
		$this->getResponse()->setRedirect(Mage::getBaseUrl());
	}
	
	

    /**
     * Default index action (with 404 Not Found headers)
     * Used if default page don't configure or available
     *
     */
    public function defaultIndexAction() {
        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');
        $this->loadLayout();
        $this->renderLayout();
    }

}
