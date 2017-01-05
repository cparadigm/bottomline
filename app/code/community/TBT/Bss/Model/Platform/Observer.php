<?php
/**
 * This file will be encrypted.
 */
class TBT_Bss_Model_Platform_Observer extends Varien_Object
{
    public function initBssConfig($observer)
    {
        Mage::helper('bss/loyalty_checker')->onModuleActivity();
        return $this;
    }
}
