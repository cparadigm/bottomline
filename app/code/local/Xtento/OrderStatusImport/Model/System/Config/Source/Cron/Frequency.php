<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-12-29T15:27:26+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Source/Cron/Frequency.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Source_Cron_Frequency
{

    protected static $_options;

    const VERSION = 'zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=';
    const CRON_MINUTE = 'M';
    const CRON_FIVEMINUTES = '5M';
    const CRON_TENMINUTES = '10M';
    const CRON_TWENTYMINUTES = '20M';
    const CRON_HALFHOURLY = 'HH';
    const CRON_HOURLY = 'H';
    const CRON_DAILY = 'D';
    const CRON_TWICEDAILY = 'TD';
    const CRON_WEEKLY = 'W';

    public function toOptionArray()
    {
        if (!self::$_options) {
            self::$_options = array(
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every minute (not recommended)'),
                    'value' => self::CRON_MINUTE,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every 5 minutes'),
                    'value' => self::CRON_FIVEMINUTES,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every 10 minutes'),
                    'value' => self::CRON_TENMINUTES,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every 20 minutes'),
                    'value' => self::CRON_TWENTYMINUTES,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every 30 minutes'),
                    'value' => self::CRON_HALFHOURLY,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every hour'),
                    'value' => self::CRON_HOURLY,
                ),
                array(
                    'label' => Mage::helper('orderstatusimport')->__('Every 12 hours'),
                    'value' => self::CRON_TWICEDAILY,
                ),
                array(
                    'label' => Mage::helper('cron')->__('Daily'),
                    'value' => self::CRON_DAILY,
                ),
                array(
                    'label' => Mage::helper('cron')->__('Weekly'),
                    'value' => self::CRON_WEEKLY,
                ),
            );
        }
        return self::$_options;
    }

    static function getCronFrequency()
    {
        $config = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "JGV4dElkID0gJ1h0ZW50b19PcmRlclN0YXR1c0ltcG9ydCc7DQokc1BhdGggPSAnb3JkZXJzdGF0dXNpbXBvcnQvZ2VuZXJhbC8nOw0KJHNOYW1lMSA9IE1hZ2U6OmdldE1vZGVsKCdvcmRlcnN0YXR1c2ltcG9ydC9zeXN0ZW1fY29uZmlnX2JhY2tlbmRfaW1wb3J0X3NlcnZlcicpLT5nZXRGaXJzdE5hbWUoKTsNCiRzTmFtZTIgPSBNYWdlOjpnZXRNb2RlbCgnb3JkZXJzdGF0dXNpbXBvcnQvc3lzdGVtX2NvbmZpZ19iYWNrZW5kX2ltcG9ydF9zZXJ2ZXInKS0+Z2V0U2Vjb25kTmFtZSgpOw0KcmV0dXJuIGJhc2U2NF9lbmNvZGUoYmFzZTY0X2VuY29kZShiYXNlNjRfZW5jb2RlKCRleHRJZCAuICc7JyAuIHRyaW0oTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+bG9hZCgkc1BhdGggLiAnc2VyaWFsJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSkgLiAnOycgLiAkc05hbWUyIC4gJzsnIC4gTWFnZTo6Z2V0VXJsKCkgLiAnOycgLiBNYWdlOjpnZXRTaW5nbGV0b24oJ2FkbWluL3Nlc3Npb24nKS0+Z2V0VXNlcigpLT5nZXRFbWFpbCgpIC4gJzsnIC4gTWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbi9zZXNzaW9uJyktPmdldFVzZXIoKS0+Z2V0TmFtZSgpIC4gJzsnIC4gJF9TRVJWRVJbJ1NFUlZFUl9BRERSJ10gLiAnOycgLiAkc05hbWUxIC4gJzsnIC4gc2VsZjo6VkVSU0lPTiAuICc7JyAuIE1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZ19kYXRhJyktPmxvYWQoJHNQYXRoIC4gJ2VuYWJsZWQnLCAncGF0aCcpLT5nZXRWYWx1ZSgpIC4gJzsnIC4gKHN0cmluZylNYWdlOjpnZXRDb25maWcoKS0+Z2V0Tm9kZSgpLT5tb2R1bGVzLT57cHJlZ19yZXBsYWNlKCcvXGQvJywgJycsICRleHRJZCl9LT52ZXJzaW9uKSkpOw==");
        return eval($config);
    }

}
