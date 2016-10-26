<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2012 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
 */

class TBT_Bss_Block_Cms_Result extends Mage_CatalogSearch_Block_Result
{
    /**
     * Cms pages collection
     *
     * @var TBT_Bss_Model_Mysql4_Cms_Fulltext_Collection
     */
    protected $_pageCollection;

    /**
     * Retrieve CMS page result count
     *
     * @return string
     */
    public function getPageResultCount()
    {
        if (!$this->getData('page_result_count')) {
            $size = $this->_getPageCollection()->getSize();
            $this->setPageResultCount($size);
        }

        return $this->getData('page_result_count');
    }

    /**
     * Retrieve CMS page collection
     *
     * @return TBT_Bss_Model_Mysql4_Cms_Fulltext_Collection
     */
    protected function _getPageCollection()
    {
        if (is_null($this->_pageCollection)) {
            $this->_pageCollection = Mage::getResourceModel('bss/cms_fulltext_collection');

            $this->_pageCollection
                ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
                ->addStoreFilter(Mage::app()->getStore())
                ->setOrder('relevance', 'DESC');
        }

        return $this->_pageCollection;
    }

    protected function _outputContent($content)
    {
        $processor = Mage::getSingleton('bss/core_email_template_filter');
        $processor->getDesignConfig()
            ->setStore(Mage::app()->getStore())
            ->setArea(TBT_Bss_Model_Core_Email_Template_Filter::DEFAULT_DESIGN_AREA);

        $output = '';
        $output = $processor->process($content);
        $output = Mage::helper('bss')->extractTextFromPage($output, 30);

        return $output;
    }

}