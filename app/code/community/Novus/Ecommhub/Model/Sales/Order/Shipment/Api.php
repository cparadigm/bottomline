<?php
/**
 * Sales order shippment API
 *
 * @category   Novus
 * @package    Novus_Ecommhub
 * @author     Moinul Al-Mamun  <moinulkuet@gmail.com>
 */
class Novus_Ecommhub_Model_Sales_Order_Shipment_Api extends Mage_Sales_Model_Order_Shipment_Api
{
   /**
     * Add tracking number to order and send email notification
     *
     * @param string $shipmentIncrementId
     * @param string $carrier
     * @param string $title
     * @param string $trackNumber
     * @return int
     */
    public function addTrack($shipmentIncrementId, $carrier, $title, $trackNumber)
    {
        $shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentIncrementId);

        /* @var $shipment Mage_Sales_Model_Order_Shipment */

        if (!$shipment->getId()) {
            $this->_fault('not_exists');
        }

        $carriers = $this->_getCarriers($shipment);

        if (!isset($carriers[$carrier])) {
            $this->_fault('data_invalid', Mage::helper('sales')->__('Invalid carrier specified.'));
        }

        $track = Mage::getModel('sales/order_shipment_track')
                    ->setNumber($trackNumber)
                    ->setCarrierCode($carrier)
                    ->setTitle($title);

        $shipment->addTrack($track);

        try {
				/* new code start - Moinul */
				// send shipment email and update history
                $shipment->sendEmail(true)
                    ->setEmailSent(true)
                    ->save();
                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
                    ->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                //$shipment->save();
			    /* new code end - Moinul */
            
			$track->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }

        return $track->getId();
    }

} // Class Mage_Sales_Model_Order_Shipment_Api End
