<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-26T18:16:26+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Backend/Import/Cron.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Backend_Import_Cron extends Mage_Core_Model_Config_Data {
    const CRON_STRING_PATH = 'crontab/jobs/order_status_import/schedule/cron_expr';
    const AUTOIMPORT_MESSAGE = 'Gur Genpxvat Ahzore Vzcbeg Zbqhyr pbhyqa\'g or ranoyrq. Cyrnfr znxr fher lbh\'er hfvat n inyvq yvprafr xrl.';

    protected function _afterSave() {
        $frequency = $this->getData('groups/import/fields/frequency/value');
        $customExpression = $this->getData('groups/import/fields/custom_cron/value');

        if (empty($customExpression)) {
            $frequencyMinute = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_MINUTE;
            $frequencyFiveMinutes = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_FIVEMINUTES;
            $frequencyTenMinutes = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_TENMINUTES;
            $frequencyTwentyMinutes = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_TWENTYMINUTES;
            $frequencyHalfHourly = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_HALFHOURLY;
            $frequencyHourly = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_HOURLY;
            $frequencyDaily = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_DAILY;
            $frequencyTwiceDaily = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_TWICEDAILY;
            $frequencyWeekly = Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;

            $minuteExpr = '0';
            $hourExpr = '0';
            $dayMonthExpr = '*';
            $monthExpr = '*';
            $dayWeekExpr = '*';
            if ($frequency == $frequencyMinute) {
                $minuteExpr = '*';
                $hourExpr = '*';
            }
            if ($frequency == $frequencyFiveMinutes) {
                $minuteExpr = '*/5';
                $hourExpr = '*';
            }
            if ($frequency == $frequencyTenMinutes) {
                $minuteExpr = '*/10';
                $hourExpr = '*';
            }
            if ($frequency == $frequencyTwentyMinutes) {
                $minuteExpr = '*/20';
                $hourExpr = '*';
            }
            if ($frequency == $frequencyHalfHourly) {
                $minuteExpr = '0,30';
                $hourExpr = '*';
            }
            if ($frequency == $frequencyHourly) {
                $hourExpr = '*';
            }
            if ($frequency == $frequencyDaily) {
                # Nothing to change
            }
            if ($frequency == $frequencyTwiceDaily) {
                $minuteExpr = '0';
                $hourExpr = '3,15';
            }
            if ($frequency == $frequencyWeekly) {
                $minuteExpr = '0';
                $dayWeekExpr = '1';
            }

            $cronExprArray = array(
                $minuteExpr, # Minute
                $hourExpr, # Hour
                $dayMonthExpr, # Day of the Month
                $monthExpr, # Month of the Year
                $dayWeekExpr, # Day of the Week
            );

            $cronExprString = join(' ', $cronExprArray);
        } else {
            $cronExprString = $customExpression;
        }

        if (!Xtento_OrderStatusImport_Model_System_Config_Source_Order_Status::isEnabled()) {
            # The cronjob import isn't enabled
            Mage::getSingleton('adminhtml/session')->addError("Fatal Error: \n" . Mage::helper('orderstatusimport')->__(str_rot13(Xtento_OrderStatusImport_Model_System_Config_Backend_Import_Cron::AUTOIMPORT_MESSAGE)));
            Mage::getConfig()->saveConfig('orderstatusimport/general/last_exception', date('c', Mage::getModel('core/date')->timestamp(time())) . ": Fatal Error: \n" . Mage::helper('orderstatusimport')->__(str_rot13(Xtento_OrderStatusImport_Model_System_Config_Backend_Import_Cron::AUTOIMPORT_MESSAGE)));
            $cronStringTwo = '* * * * *';
        }

        try {
            Mage::getConfig()->saveConfig(self::CRON_STRING_PATH, $cronExprString);
            Mage::getConfig()->cleanCache();
        } catch (Exception $e) {
            throw new Exception(Mage::helper('cron')->__('Unable to save cron expression'));
        }
    }

}
