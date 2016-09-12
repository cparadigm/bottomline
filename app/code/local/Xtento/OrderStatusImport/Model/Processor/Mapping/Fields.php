<?php

/**
 * Product:       Xtento_OrderStatusImport (1.3.5)
 * ID:            zBz5rQGncoKSGGGFx+5QMonW+L3uUtQguMNYVlhDmXU=
 * Packaged:      2014-01-21T10:43:26+00:00
 * Last Modified: 2012-02-27T17:13:56+01:00
 * File:          app/code/local/Xtento/OrderStatusImport/Model/Processor/Mapping/Fields.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderStatusImport_Model_Processor_Mapping_Fields extends Xtento_OrderStatusImport_Model_Processor_Mapping_Abstract
{

    /*
     * array(
     * 'label'
     * 'disabled'
     * 'tooltip'
     * 'default_value_disabled'
     * 'default_values'
     * )
     */
    public function getMappingFields()
    {
        if ($this->_importFields !== NULL) {
            return $this->_importFields;
        }

        $importFields = array(
            'order_info' => array(
                'label' => '-- Order Information -- ',
                'disabled' => true
            ),
            'order_number' => array(
                'label' => 'Order Number *',
                'default_value_disabled' => true
            ),
            'tracking_number' => array('label' => 'Tracking Number'),
            'carrier_code' => array(
                'label' => 'Shipping Carrier Code',
                'default_values' => $this->getDefaultValues('shipping_carriers')
            ),
            'carrier_name' => array('label' => 'Shipping Carrier Name'),
            'order_status' => array(
                'label' => 'Order Status',
                'default_values' => $this->getDefaultValues('order_status')
            ),
            'order_status_history_comment' => array('label' => 'Order History Comment'),
            //'custom1' => array('label' => 'Custom Data 1'),
            //'custom2' => array('label' => 'Custom Data 2'),
            'item_info' => array(
                'label' => '-- Item Information -- ',
                'disabled' => true
            ),
            'sku' => array(
                'label' => 'SKU',
                'default_value_disabled' => true
            ),
            'qty' => array('label' => 'Quantity'),
        );

        $this->_importFields = $importFields;

        return $this->_importFields;
    }
}
