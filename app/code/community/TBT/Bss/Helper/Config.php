<?php

class TBT_Bss_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * Checks if partial word matching is enabled in BSS Config section
     * @return boolean Returns true if option is enabled, false otherwise
     */
    public function isEnabledPartialWordMatching()
    {
        return Mage::getStoreConfigFlag("bss/special/partial_word_matching");
    }

    /**
     * Checks if matching search results against product tags is enabled in BSS Config section
     * @return boolean Returns true if option is enabled, false otherwise
     */
    public function isTagMatchEnabled()
    {
        return Mage::getStoreConfigFlag("bss/match/tag");
    }

    /**
     * Checks if matching search results against product vategories is enabled in BSS Config section
     * @return boolean Returns true if option is enabled, false otherwise
     */
    public function isCategoryMatchEnabled()
    {
        return Mage::getStoreConfigFlag("bss/match/category_names");
    }
}
