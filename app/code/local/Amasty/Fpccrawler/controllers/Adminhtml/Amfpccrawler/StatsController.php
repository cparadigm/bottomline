<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Fpccrawler
 */
class Amasty_Fpccrawler_Adminhtml_Amfpccrawler_StatsController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('report/amfpccrawler');
        $block = $this->getLayout()->createBlock('amfpccrawler/adminhtml_stats');
        $this->_addContent($block);
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(
            'report/amfpccrawler/amfpccrawler_stats'
        );
    }

    protected function _setActiveMenu($menuPath)
    {
        $this->getLayout()->getBlock('menu')->setActive($menuPath);
        $this->_title($this->__('Reports'))->_title($this->__('FPC Visual Stats'));

        return $this;
    }

    protected function _title($text = NULL, $resetIfExists = true)
    {
        if (Mage::helper('ambase')->isVersionLessThan(1, 4)) {
            return $this;
        }

        return parent::_title($text, $resetIfExists);
    }

}