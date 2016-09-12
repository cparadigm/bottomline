<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-05-08T10:46:54+02:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_System_Config_Source_Order_Status
{

    public function toOptionArray()
    {
        $statuses[] = array('value' => 'no_change', 'label' => Mage::helper('adminhtml')->__('-- No change --'));

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for Custom Order Status introduced in Magento version 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
        }
        return $statuses;
    }

    /*
     * Function to just put all order status "codes" into an array.
     */
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            array_push($statusArray, $status['value']);
        }
        return $statusArray;
    }

    static function isEnabled()
    {
        return eval(call_user_func('ba' . 'se64_' . 'dec' . 'ode', "ICAgICAgICAkZXh0SWQgPSAnWHRlbnRvX09yZGVyU3RhdHVzSW1wb3J0JzsNCiAgICAgICAgJHNQYXRoID0gJ29yZGVyc3RhdHVzaW1wb3J0L2dlbmVyYWwvJzsNCiAgICAgICAgJHNOYW1lID0gTWFnZTo6Z2V0TW9kZWwoJ29yZGVyc3RhdHVzaW1wb3J0L3N5c3RlbV9jb25maWdfYmFja2VuZF9pbXBvcnRfc2VydmVyJyktPmdldEZpcnN0TmFtZSgpOw0KICAgICAgICAkc05hbWUyID0gTWFnZTo6Z2V0TW9kZWwoJ29yZGVyc3RhdHVzaW1wb3J0L3N5c3RlbV9jb25maWdfYmFja2VuZF9pbXBvcnRfc2VydmVyJyktPmdldFNlY29uZE5hbWUoKTsNCiAgICAgICAgJHMgPSB0cmltKE1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZ19kYXRhJyktPmxvYWQoJHNQYXRoIC4gJ3NlcmlhbCcsICdwYXRoJyktPmdldFZhbHVlKCkpOw0KICAgICAgICBpZiAoKCRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lKSkpICYmICRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lMikpKSB7DQogICAgICAgICAgICBNYWdlOjpnZXRDb25maWcoKS0+c2F2ZUNvbmZpZygkc1BhdGggLiAnZW5hYmxlZCcsIDApOw0KICAgICAgICAgICAgTWFnZTo6Z2V0Q29uZmlnKCktPmNsZWFuQ2FjaGUoKTsNCiAgICAgICAgICAgIHJldHVybiBmYWxzZTsNCiAgICAgICAgfSBlbHNlIHsNCiAgICAgICAgICAgIHJldHVybiB0cnVlOw0KICAgICAgICB9"));
    }

}
