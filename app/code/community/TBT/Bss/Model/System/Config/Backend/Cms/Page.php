<?php

class TBT_Bss_Model_System_Config_Backend_Cms_Page extends Mage_Core_Model_Config_Data
{
    /**
     * If CMS was just enabled, invalidate BSS index.
     * @return $this
     */
    public function _afterSave()
    {
        if ($this->isValueChanged() && $this->getValue()) {
            // Clear search result  data
            Mage::getModel('bss/cms_fulltext')->resetSearchResults();

            Mage::helper('bss')->invalidateBssIndex();
        }

        return parent::_afterSave();
    }
}