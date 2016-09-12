<?php

class Boardroom_Pcd_Helper_Pcd extends Mage_Core_Helper_Abstract {

    const PCD_URL = 'http://www.palmcoastd.com/pcdtest/valtran?';

    public function sendPcdRequest($data) {

        $query = http_build_query($data);
        var_dump($query);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,self::PCD_URL.$query);
        //curl_setopt($ch,CURLOPT_URL,"http://www.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey13&iType=4&iConftype=1&iSubscribing=Y&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=19&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US&iEmailAddr=test%40tester.com");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);

    }

    public function processPcdItem($order, $item) {

        $product = Mage::getModel('catalog/product')->load($item->getProductId());
        $address = $order->getBillingAddress();
        $payment = $order->getPayment();
        if ($product->getAttributeText('vendor')=='PCD') {
            $region = Mage::getModel('directory/region')->load($address->getRegionId());
            $data = array();
            $data['iMagId'] = $product->getSku();
            $data['iId'] = $order->getId().$item->getId();
            $data['iType'] = 4;
            $data['iConftype'] = 1;
            $data['iSubscribing'] = 'Y';
            if ($payment->getMethod()=='purchaseorder') {
                $data['iPayOpt'] = 'B';
            } else {
                $data['iPayOpt'] = 'P';
                $data['i3rdPty'] = 'Y';
            }
            //$data['iPayOpt'] = ($payment->getMethod()=='purchaseorder'?'B':'P');
            $data['iSource'] = $product->getSourcekey();
            $data['iTerm'] = '12';
            $data['iAmount'] = $item->getPrice();
            $data['iFName'] = $order->getCustomerFirstname();
            $data['iLName'] = $order->getCustomerFirstname();
            $data['iPaddr'] = $address->getStreet();
            $data['iSAddr'] = '';
            $data['iCity'] = $address->getCity();
            $data['iState'] = $region->getCode();
            $data['iPCode'] = $address->getPostcode();
            $data['iCountry'] = $address->getCountryId();
            $data['iEmailAddr'] = $order->getCustomerEmail();

            $street = $data['iPaddr'];
            $street = implode(" ",$street);
            $data['iPaddr'] = $street;

            $response = $this->sendPcdRequest($data);
var_dump($response);
            $val = explode("<VAL>",$response);
            if (count($val)>1) {
                $val = explode("</VAL>",$val[1]);
                if (count($val)>1) {
                    if ($val[0]=="COMPLETE") {
                        $id = explode("<ID>",$val[1]);
                        if (count($id)>1) {
                            $id = explode("</ID>",$id[1]);
                            if (count($id)>1) {
                                $id = $id[0];
                                $item->setData('pcd_id',$id);
                                $item->save();
                                $qtys[$item->getId()] = $item->getQtyOrdered();
                                $shipmentId = Mage::getModel('sales/order_shipment_api')->create($order->getIncrementId(), $qtys ,'' ,false,1);

                                $order->setPcdProcessed(1);
                                $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
                                $order->setStatus(Mage_Sales_Model_Order::STATE_COMPLETE, true);
                                $history = $order->addStatusHistoryComment('PCD Item Processed', false);
                                $history->setIsCustomerNotified(true);
                                $order->save();
                            }
                        }
                    } else {
                        echo "DIE";var_dump($response);die();
                    }
                }
            }
        }

    }

    public function updateAddress($address) {
        $customer = Mage::getModel('customer/customer')->load($address->getCustomerId());
        if ($customer->getId()) {
            $items = $this->getCustomerPcdItems($customer);
            foreach($items as $item) {
                $product = Mage::getModel('catalog/product')->load($item[0]);

                $data = array();
                $data['iMagId'] = $product->getSku();
                $data['iId'] = $item[2].'-'.$item[1];
                $data['iType'] = 4;
                $data['iptrans'] = 'Y';
                $data['iLookUp'] = 'ADDRESS';
                $data['iSubName'] = $address->getFirstname().' '.$address->getLastname();
                $data['iPaddr'] = trim($address->getStreet1().' '.$address->getStreet2());
                $data['iPCode'] = $address->getPostcode();
                $data['iCountry'] = $address->getCountryId();

                $this->sendPcdRequest($data);
            }
        }
    }

    public function getCustomerPcdItems($customer) {
        $orders = Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('customer_id', $customer->getId())
            ->addFieldToFilter('is_pcd', 1)
            ->addFieldToFilter('pcd_processed', 1);

        $itemsArr = array();
        foreach($orders as $order) {
            $items = $order->getAllVisibleItems();
            foreach ($items as $item) {
                if ($item->getIsPcd()==1&&!in_array($item->getProductId(),$itemsArr)) {
                    $itemsArr[] = array($item->getProductId(),$item->getId(),$order->getId());
                }
            }
        }

        return $itemsArr;

    }

}
