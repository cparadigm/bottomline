<?php
require_once 'app/Mage.php';

    Mage::app('default');

    ini_set("log_errors", 1);
    ini_set("error_log", "/var/www/html/brmage/var/log/order-3.log");
    error_log( "order-3 transaction begins" );

    $paymentMethod = "purchaseorder" ;
    $shippingMethod = "flatrate_flatrate" ;

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip = $_POST["zip"];
    $productid = $_POST["productid"];
    $success = $_POST["success"];
    $failure = $_POST["failure"];

    $error_occurred = true ;

    error_log( "order-3-data-received," . 
        $lastname . ',' . 
        $firstname . ',' . 
        $email . ',' . 
        $address1 . ',' . 
        $address2 . ',' . 
        $city . ',' . 
        $state . ',' . 
        $zip . ',' . 
        $productid );

    $region = Mage::getModel('directory/region')->loadByCode($state, 'US');
    error_log( "order-3 adding " . $lastname . ',' . $firstname );

    $storeid = '8';
    $store = Mage::getModel('core/store')->load($storeId);
    $websiteId = $store->getWebsiteId();

    $customer = Mage::getModel("customer/customer");

    $customer->setWebsiteId($websiteId);
    $customer->loadByEmail($email); 

    if ( $customer->getEmail() == null ) { 
        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId($websiteId)
                 ->setStore($store)
                 ->setGroupId(1)
                 ->setFirstname($firstname)
                 ->setLastname($lastname)
                 ->setEmail($email);

        error_log( "order-3 saving " . $lastname . ',' . $firstname );
        try {
            $customer->save();
            }
        catch (Exception $e) {
            error_log($e->getMessage());
                }
            }
    else {  
        error_log("existing customer");
        }

    $address = Mage::getModel("customer/address");
    $address->setCustomerId($customer->getId())
            ->setFirstname($customer->getFirstname())
            ->setLastname($customer->getLastname())
            ->setCountryId('US')
            ->setRegionId($region->getId()) 
            ->setPostcode($zip)
            ->setCity($city)
            ->setStreet($address1)
            ->setIsDefaultBilling('1')
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');
 
    error_log( "order-3 saving address" . $address1 );
    try {
        $address->save();
        }
    catch (Exception $e) {
        error_log('address save error:' . $e->getMessage());
        }

    try {
        $customer->addAddress($address)             
            ->setIsDefaultBilling($address)
            ->setIsDefaultShipping($address);

        $customer->save();
        }
    catch (Exception $e) {
        error_log('customer address save error:' . $e->getMessage());
        }


    error_log( "order-3 creating order for product:" . $productid );
    $transaction = Mage::getModel('core/resource_transaction');
    $reservedOrderId = Mage::getSingleton('eav/config')
        ->getEntityType('order')
        ->fetchNewIncrementId($storeid);

    $currencyCode  = Mage::app()->getBaseCurrencyCode();
    $order = Mage::getModel('sales/order')
        ->setIncrementId($reservedOrderId)
        ->setStoreId($storeid)
        ->setQuoteId(0)
        ->setGlobalCurrencyCode($currencyCode)
        ->setBaseCurrencyCode($currencyCode)
        ->setStoreCurrencyCode($currencyCode)
        ->setOrderCurrencyCode($currencyCode);


    $order->setCustomerEmail($customer->getEmail())
        ->setCustomerFirstname($customer->getFirstname())
        ->setCustomerLastname($customer->getLastname())
        ->setCustomerGroupId($customer->getGroupId())
        ->setCustomerIsGuest(0)
        ->setCustomer($customer);

    $billing = $customer->getDefaultBillingAddress();
    error_log( "order-3 billing address" . $billing );

        
    $billingAddress = Mage::getModel('sales/order_address')
        ->setStoreId($storeid)
        ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
        ->setCustomerId($customer->getId())
        ->setCustomerAddressId($customer->getDefaultBilling())
        ->setCustomerAddress_id($billing->getEntityId())
        ->setPrefix($billing->getPrefix())
        ->setFirstname($billing->getFirstname())
        ->setMiddlename($billing->getMiddlename())
        ->setLastname($billing->getLastname())
        ->setSuffix($billing->getSuffix())
        ->setCompany($billing->getCompany())
        ->setStreet($billing->getStreet())
        ->setCity($billing->getCity())
        ->setCountry_id($billing->getCountryId())
        ->setRegion($billing->getRegion())
        ->setRegion_id($billing->getRegionId())
        ->setPostcode($billing->getPostcode())
        ->setTelephone($billing->getTelephone())
        ->setFax($billing->getFax())
        ->setVatId($billing->getVatId());
    $order->setBillingAddress($billingAddress);

    $shipping = $customer->getDefaultShippingAddress();
    error_log( "order-3 shipping address" . $shipping );
    $shippingAddress = Mage::getModel('sales/order_address')
        ->setStoreId($storeid)
        ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
        ->setCustomerId($customer->getId())
        ->setCustomerAddressId($customer->getDefaultShipping())
        ->setCustomer_address_id($shipping->getEntityId())
        ->setPrefix($shipping->getPrefix())
        ->setFirstname($shipping->getFirstname())
        ->setMiddlename($shipping->getMiddlename())
        ->setLastname($shipping->getLastname())
        ->setSuffix($shipping->getSuffix())
        ->setCompany($shipping->getCompany())
        ->setStreet($shipping->getStreet())
        ->setCity($shipping->getCity())
        ->setCountry_id($shipping->getCountryId())
        ->setRegion($shipping->getRegion())
        ->setRegion_id($shipping->getRegionId())
        ->setPostcode($shipping->getPostcode())
        ->setTelephone($shipping->getTelephone())
        ->setFax($shipping->getFax())
        ->setVatId($billing->getVatId());

    $order->setShippingAddress($shippingAddress)
        ->setShippingMethod($shippingMethod);

    $orderPayment = Mage::getModel('sales/order_payment')
        ->setStoreId($storeid)
        ->setCustomerPaymentId(0)
        ->setMethod($paymentMethod)
        ->setPoNumber(' – ');

    $order->setPayment($orderPayment);

    $product = Mage::getModel('catalog/product')->load($productid);
    error_log( "order-3 creating order item for product:" . $product->getName() );
    try {
        $orderItem = Mage::getModel('sales/order_item')
            ->setStoreId($storeid)
            ->setQuoteItemId(0)
            ->setQuoteParentItemId(NULL)
            ->setProductId($product->getId())
            ->setProductType($product->getTypeId())
            ->setQtyBackordered(NULL)
            ->setTotalQtyOrdered(1)
            ->setQtyOrdered(1)
            ->setName($product->getName())
            ->setSku($product->getSku())
            ->setPrice($product->getFinalPrice())
            ->setBasePrice($product->getFinalPrice())
            ->setOriginalPrice($product->getFinalPrice())
            ->setRowTotal($product->getFinalPrice())
            ->setBaseRowTotal($product->getFinalPrice());
            error_log( "order-3 adding order item ", $orderItem );
            $order->addItem($orderItem);
        }
    catch (Exception $e) {
        error_log('order item error:' . $e->getMessage());
        }


    $order->setSubtotal($product->getFinalPrice())
            ->setBaseSubtotal($product->getFinalPrice())
            ->setGrandTotal($product->getFinalPrice())
            ->setBaseGrandTotal($product->getFinalPrice());

    $transaction->addObject($order);
    $transaction->addCommitCallback(array($order, 'place'));
    $transaction->addCommitCallback(array($order, 'save'));
 
    try {
        $transaction->save(); 
        $error_occurred = false; 
        }
    catch (Exception $ex) {
        error_log( 'order create error:' . $ex->getMessage());
        }   
    catch (Mage_Core_Exception $e) {
        error_log('mage core error:' . $e->getMessage());
        }



    // Resource Clean-Up
    $order = $customer = $service = $product = null;
    error_log( "order-3 transaction ends" );

    if ( $error_occurred ) {
        header("Location:" . $failure);
        }
    else {
        header("Location:" . $success);
    }


function var_error_log( $prefix, $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $prefix . ' ' . $contents );        // log contents of the result of var_dump( $object )
    }

?>

