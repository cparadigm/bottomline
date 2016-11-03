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

/**
 *
 * @category   TBT
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */

class TBT_Bss_Model_Mysql4_Setup extends Mage_Core_Model_Resource_Setup
{

    protected $_exceptionStack    = array();
    protected $_setup             = null;
    protected $_firstInstallation = false;

    /**
     * Dispatches _preApply() and _postApply() before and after it falls back to its parent
     * method, which will:
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function applyUpdates()
    {
        $dbVer = $this->_getResource()->getDbVersion($this->_resourceName);
        $configVer = (string) $this->_moduleConfig->version;

        $updatesApplied = false;

        if ($dbVer === false) {
            $this->_firstInstallation = true;
        }

        if ($dbVer !== false) {
            $status = version_compare($configVer, $dbVer);
            if ($status == self::VERSION_COMPARE_GREATER) {
                $updatesApplied = true;
            }
        } elseif ($configVer) {
            $updatesApplied = true;
        }

        if ($updatesApplied) {
            $this->_preApply();
        }

        parent::applyUpdates();

        if ($updatesApplied) {
            $this->_postApply();
        }

        return $this;
    }

    public function isFirstInstallation()
    {
        return $this->_firstInstallation;
    }

    /**
     * Clears cache and prepares anything that needs to generally happen before running DB install scripts.
     * @return TBT_Rewards_Model_Mysql4_Setup
     */
    public function prepareForDb()
    {
        try {
            if (Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.4.0.0')) {
                Mage::app()->getCacheInstance()->flush();
            } else { // version is 1.3.3 or lower.
                Mage::app()->getCache()->clean();
            }
        } catch (Exception $ex) {
            $this->addInstallProblem("Problem clearing cache:" . $ex);
        }

        return $this;
    }

    /**
     * Runs before install/update SQL has been executed
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    protected function _preApply()
    {
        return $this;
    }

    /**
     * Runs after install/update SQL has been executed
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    protected function _postApply()
    {
        if ($this->_firstInstallation) {
            $this->_createFirstInstallNotice();
        } else {
            $this->_createSuccessfulUpdateNotice();
        }

        return $this;
    }


    protected function _createFirstInstallNotice()
    {
        $version = Mage::getConfig()->getNode('modules/TBT_Bss/version');

        $url = Mage::getModel('core/config_data')->load("web/unsecure/base_url",'path')->getValue() .
            Mage::getUrl('adminhtml/diagnostictest/runTestsweet',array("_nosid"=>true));

        $firstInstalledMsgTitle = "Better Store Search v{$version} was successfully installed!";

        $firstInstalledMsgDesc = "Better Store Search v{$version} was successfully installed on your store. <a target='_blank' href='"
            . $url
            . "'> Run our diagnostics tool </a> to ensure your system is healthy";

        $this->createInstallNotice($firstInstalledMsgTitle, $firstInstalledMsgDesc);

        return $this;
    }

    /**
     * This method will create a backend notification regarding a successful
     * Sweet Tooth installation, with the appropriate version number.
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    protected function _createSuccessfulUpdateNotice()
    {
        $version = Mage::getConfig()->getNode('modules/TBT_Bss/version');
        $msgTitle = "Better Store Search was successfully updated to version {$version} !";
        $msgDesc = "Better Store Search was successfully updated to version {$version} on your store.";

        $this->createInstallNotice($msgTitle, $msgDesc);

        return $this;
    }

    /**
     * Creates an installation message notice in the backend.
     * @param string $msgTitle
     * @param string $msgDesc
     * @param string $url=null if null default Sweet Tooth URL is used.
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function createInstallNotice($msgTitle, $msgDesc, $url = null, $severity = null)
    {
        $message = Mage::getModel('adminnotification/inbox');
        $message->setDateAdded(date("c", time()));


        if ($severity === null) {
            $severity = Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE;
        }

        if ($url == null) {
            $message->setUrl("http://www.betterstoresearch.com/changelog");
        }

        $message->setTitle($msgTitle);
        $message->setDescription($msgDesc);
        $message->setUrl($url);
        $message->setSeverity($severity);
        $message->save();

        return $this;
    }

    /**
     * Clears any install problems (exceptions) that were in the stack
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function clearProblems()
    {
        $this->_exceptionStack = array();
        return $this;
    }

    /**
     * Alter table for each column update and ignore duplicate column errors
     * This is used since "if column not exists" function does not exist
     * for MYSQL
     *
     * @param unknown_type $installer
     * @param string $tableName
     * @param array $columns
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function addColumns($tableName, $columns)
    {
        if (!is_array($columns)) {
            $columns = array($columns);
        }

        foreach ($columns as $column) {
            $sql = "ALTER TABLE {$tableName} ADD COLUMN ( {$column} );";
            // run SQL and ignore any errors including (Duplicate column errors)
            try {
                $this->run($sql);
            } catch (Exception $ex) {
                $this->addInstallProblem($ex);
            }
        }

        return $this;
    }

    /**
     * Attempt to add a foreign key constraint between two tables
     *
     * @param unknown_type $installer
     * @param string $table1Name
     * @param string $column1
     * @param string $table2Name
     * @param string $column2=null (uses column1 if null)
     * @param string $onDelete='NO ACTION'
     * @param string $onUpdate='NO ACTION'
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function addForeignKey($keyName, $tableName, $columnName, $refTableName, $refColumnName = null, $onDelete = 'NO ACTION', $onUpdate = 'NO ACTION')
    {
        try {
            if (empty($refColumnName)) {
                $refColumnName = $columnName;
            }

            $connection = $this->getConnection();
            if (Mage::helper('bss/version')->isBaseMageVersionAtLeast('1.4.0.1')) {
                $connection->addForeignKey(
                    $keyName,
                    $tableName,
                    $columnName,
                    $refTableName,
                    $refColumnName,
                    $onDelete,
                    $onUpdate
                );
            } else {
                $connection->addConstraint(
                    $keyName,
                    $tableName,
                    $columnName,
                    $refTableName,
                    $refColumnName,
                    $onDelete,
                    $onUpdate
                );
            }
        } catch (Exception $ex) {
            $this->addInstallProblem($ex);
        }

        return $this;
    }

    /**
     * Adds an exception problem to the stack of problems that may
     * have occured during installation.
     * Ignores duplicate column name errors; ignore if the msg starts with "SQLSTATE[42S21]: Column already exists"
     * @param Exception $ex
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function addInstallProblem(Exception $ex)
    {
        if (strpos($ex->getMessage(), "SQLSTATE[42S21]: Column already exists") !== false) {
            return $this;
        }

        if (strpos($ex->getMessage(), "SQLSTATE[42000]: Syntax error or access violation: 1091 Can't DROP") !== false
                && strpos($ex->getMessage(), "check that column/key exists") !== false) {

            return $this;
        }

        $this->_exceptionStack[] = $ex;

        return $this;
    }

    /**
     * Returns true if any problems occured after installation
     * @return boolean
     */
    public function hasProblems()
    {
        return sizeof($this->_exceptionStack) > 0;
    }

    /**
     * Returns a string of problems that occured after any installation scripts were run through this helper
     * @return string message to display to the user
     */
    public function getProblemsString()
    {
        $msg = $this->__("The following errors occured while trying to install the module.");
        $msg .= "\n<br>";
        foreach ($this->_exceptionStack as $i => $ex) {
            $msg .= "<b>#{$i}: </b>";
            if (Mage::getIsDeveloperMode()) {
                $msg .= nl2br($ex);
            } else {
                $msg .= $ex->getMessage ();
            }
            $msg .= "\n<br>";
        }
        $msg .= "\n<br>";
        $msg .= $this->__("If any of these problems were unexpected, I recommend that you contact the module support team to avoid problems in the future.");

        return $msg;
    }

    /**
     * alter table for each column update and ignore duplicate column errors
     * This is used since "if column not exists" function does not exist
     * for MYSQL
     *
     * @param unknown_type $installer
     * @param string $tableName
     * @param array $columns
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function dropColumns($tableName, $columns)
    {
        foreach ($columns as $column) {
            $sql = "ALTER TABLE {$tableName} DROP COLUMN {$column};";
            // run SQL and ignore any errors including (Duplicate column errors)
            try {
                $this->run($sql);
            } catch (Exception $ex) {
                $this->addInstallProblem($ex);
            }
        }

        return $this;
    }

    /**
     * Add an EAV entity attribute to the database.
     *
     * @param string $entityType        entity type (catalog_product, order, order_item, etc)
     * @param string $attributeCode    attribute code
     * @param array $data                 eav attribute data
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function addAttribute($entityType, $attributeCode, $data)
    {
        try {
            $this->_getSetupSingleton()->addAttribute($entityType, $attributeCode, $data);
        } catch (Exception $ex) {
            $this->addInstallProblem($ex);
        }

        return $this;
    }

    /**
     * Add an EAV entity attribute to a group. By default, if no $setId / $groupId is specified, default will be used.
     * @param string $entityTypeId Entity type ('catalog_product', 'catalog_category', etc)
     * @param string $attributeId  EAV attribute code ('bss_weight', etc)
     * @param [type] $setId        EAV attribute set ID
     * @param [type] $groupId      EAV group ID
     * @param [type] $sortOrder    Attribute's order in group
     */
    public function addAttributeToGroup($entityTypeId, $attributeId, $sortOrder = null, $setId = null, $groupId = null)
    {
        try {
            $setup = $this->_getSetupSingleton();
            // if no attribute set ID is specified, we'll set the default
            if (!$setId) {
                $setId = $setup->getDefaultAttributeSetId($entityTypeId);
            }
            // if no group ID is specified, we'll set the default
            if (!$groupId) {
                $groupId = $setup->getDefaultAttributeGroupId($entityTypeId, $setId);
            }

            $setup->addAttributeToGroup(
                $entityTypeId,
                $setId,
                $groupId,
                $attributeId,
                $sortOrder
            );
        } catch (Exception $ex) {
            $this->addInstallProblem($ex);
        }

        return $this;
    }

    /**
     * Runs a SQL query using the install resource provided and
     * remembers any errors that occur.
     *
     * @param unknown_type $installer
     * @param string $sql
     * @return TBT_Bss_Model_Mysql4_Setup
     */
    public function attemptQuery($sql)
    {
        try {
            $this->run($sql);
        } catch (Exception $ex) {
            $this->addInstallProblem($ex);
        }

        return $this;
    }

    public function cleanCache()
    {
        Mage::getConfig()->cleanCache();
        return $this;
    }

    /**
     * @return Mage_Eav_Model_Entity_Setup
     */
    protected function _getSetupSingleton()
    {
        if ($this->_setup == null) {
            $this->_setup = new Mage_Eav_Model_Entity_Setup('core_setup');
        }

        return $this->_setup;
    }
}
