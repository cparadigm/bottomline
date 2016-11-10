<?php
/**
 * WDCA - Better Store Search
 *
 * NOTICE OF LICENSE
*/

/**
 *
 * @category   TBT
 * @package    TBT_Bss
 * @author     WDCA Better Store Search Team <contact@wdca.ca>
 */
class TBT_Bss_Block_System_Config_Reinstalldb extends TBT_Bss_Block_System_Config_Abstractbutton
{
    public function getButtonData($buttonBlock)
    {
        $params = array(
            'website' => $buttonBlock->getRequest()->getParam('website')
        );
        $url = Mage::helper('adminhtml')->getUrl('adminhtml/manage_diagnostics/reinstalldb', $params);
        $data = array(
            'label'     => Mage::helper('adminhtml')->__('Re-install Database'),
            'onclick'   => 'setLocation(\''.$url.'\')',
            'class'     => '',
            'comment'    => "When clicked, this will attempt to remove Magento's memory of the Sweet Tooth database components ever being installed. Magento will then attempt to re-install the database from scratch upon next access.  This may help resolve issues with an abnormal installation or inconsistent database migration.",
        );

        return $data;
    }
}