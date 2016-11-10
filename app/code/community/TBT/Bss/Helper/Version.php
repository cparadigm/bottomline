<?PHP
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
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

class TBT_Bss_Helper_Version extends Mage_Core_Helper_Abstract
{
    /**
     * True if the version of Magento currently being rune is Enterprise Edition
     */
    public function isMageEnterprise()
    {
        return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' );
    }

    /**
     * attempt to convert an Enterprise, Professional, Community magento version number to its compatable Community version
     *
     * @param string $task fix problems where direct version numbers cant be changed to a community release without knowing the intent of the task
     */
    public function convertVersionToCommunityVersion($version, $task = null)
    {
        /* Enterprise -
         * 1.9 | 1.8 | 1.5
         */
        if ($this->isMageEnterprise()) {
            if (version_compare ( $version, '1.11.0.0', '>=' ))
                return '1.6.0.0';
            if (version_compare ( $version, '1.9.1.0', '>=' ))
                return '1.5.0.0';
            if (version_compare ( $version, '1.9.0.0', '>=' ))
                return '1.4.2.0';
            if (version_compare ( $version, '1.8.0.0', '>=' ))
                return '1.3.1.0';
            return '1.3.1.0';
        }

        /* Professional -
         * If Entprise_Enterprise module is installed but it didn't pass Enterprise_Enterprise tests
         * then the installation must be Magento Pro edition.
         * 1.7 | 1.8
         */
        if (Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' )) {
            if (version_compare ( $version, '1.11.0.0', '>=' ))
                return '1.6.0.0';
            if (version_compare ( $version, '1.10.0.0', '>=' ))
                return '1.5.0.0';
            if (version_compare ( $version, '1.9.0.0', '>=' ))
                return '1.4.1.0';
            if (version_compare ( $version, '1.8.0.0', '>=' ))
                return '1.4.1.0';
            if (version_compare ( $version, '1.7.0.0', '>=' ))
                return '1.3.1.0';
            return '1.3.1.0';
        }

        /* Community -
         * 1.5rc2 - December 29, 2010
         * 1.4.2 - December 8, 2010
         * 1.4.1 - June 10, 2010
         * 1.3.3.0 - (April 23, 2010) *** does this release work like to 1.4.0.1?
         * 1.4.0.1 - (February 19, 2010)
         * 1.4.0.0 - (February 12, 2010)
         * 1.3.0 - March 30, 2009
         * 1.2.1.1 - February 23, 2009
         * 1.1 - July 24, 2008
         * 0.6.1316 - October 18, 2007
         */

        return $version;
    }

    /**
     * Returns true if the base version of this Magento installation
     * is equal to the version specified or newer.
     * @param string $version
     * @param unknown_type $task
     */
    public function isBaseMageVersionAtLeast($version, $task = null)
    {

        // convert Magento Enterprise, Professional, Community to a base version
        $mage_base_version = $this->convertVersionToCommunityVersion ( Mage::getVersion (), $task );

        if (version_compare ( $mage_base_version, $version, '>=' )) {
            return true;
        }

        return false;
    }

}
