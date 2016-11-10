<?php

class TBT_Bss_Model_System_Config_Backend_Category extends Mage_Core_Model_Config_Data
{
    /**
     * After changing "Match Results Against Product Categories"
     *
     * @return TBT_Bss_Model_Adminhtml_System_Config_Backend_Categories
     */
    protected function _afterSave()
    {
        parent::_afterSave();

        if (!$this->isValueChanged()) {
            return $this;
        }

        // Clear search result  data
        Mage::getModel('catalogsearch/fulltext')->resetSearchResults();

        // re-index categories
        if ($this->getValue()) {
            Mage::getModel('bss/mysql4_dym_categories')->updateCategoriesIndexes(null, null, true);
        }

        return $this;
    }

}
