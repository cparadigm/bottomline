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
class TBT_Bss_Manage_DiagnosticsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * This controller action will remove the database install entry from the Magento
     * core_resource table. This in turn will force Magento to re-install the database scripts.
     */
    public function reinstalldbAction() {

        echo "Deleting core_resource table entries that have the code 'bss_setup'...<br>";
        flush();

        $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
        $conn->beginTransaction();

        $this->_clearDbInstallMemory($conn, 'bss_setup');

        echo "Done.";
        flush();

        $conn->commit();

        echo "<br><br>\n"
            ."<a href='". $this->getUrl('adminhtml/notification') . "'>CLICK HERE</a> "
            ."to go back to the dashboard and module will retun it's own database install scripts over again ";

        exit;

    }

    public function _clearDbInstallMemory($conn, $code) {

        $table_prefix = Mage::getConfig()->getTablePrefix() ;

        $conn->query("
            DELETE FROM    `{$table_prefix}core_resource`
            WHERE    `code` = '{$code}'
            ;
        ");
        echo "Resource DB for {$code} has been cleared<br>";

        return $this;
    }


}
?>