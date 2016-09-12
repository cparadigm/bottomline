<?php
/*
require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

Mage::app('admin')->setUseSessionInUrl(false);

umask(0);

//get the most recent order id the cron has hit
$tableName = 'valtran_orders';
$primaryKey = 'order_id';
$db = Mage::getModel('core/resource')->getConnection('core_read');
$result = $db->raw_fetchRow("SELECT MAX(`{$primaryKey}`) as LastID FROM `{$tableName}` WHERE sent=1");

//get product ids of products with PCD vendor
$product_ids = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToFilter('vendor', array('eq' => 5))
    ->load()
    ->getAllIds();

//get order item collection with particular product ids and order id greater than last processed order
$collection = Mage::getResourceModel('sales/order_item_collection')
    ->addAttributeToFilter('product_id', array('in' => $product_ids))
    ->addAttributeToFilter('order_id', array('gt' => $result['LastID']))
    ->load();

foreach($collection as $orderItem) {
    $orders[$orderItem->getOrder()->getIncrementId()] = $orderItem->getOrder();
    $sql = "INSERT INTO `{$tableName}` SET order_id = ?, sent=1, success=1, date=now()";
    $db->query($sql, array($orderItem->getOrderId()));
}
*/
echo 99;
$ch = curl_init();

/*
//validate name and address
echo "validate name and address 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=1234567&iType=1&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "validate name and address 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=1234567&iType=1&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
*/

/*
//validate email
echo "validate email 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=2&iEmailAddr=testing%40testing.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "validate email 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=2&iEmailAddr=testing%40testing.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br><br><br>";

//validate regular order info
echo "validate regular order info 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=3&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=09&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&Country=US&iEmailAddr=test%40tester.com");
$result = curl_exec($ch);
//var_dump($result);
echo "<br><br>";
echo "validate regular order info 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=3&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=09&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&Country=US&iEmailAddr=test%40tester.com");
$result = curl_exec($ch);
//var_dump($result);
echo "<br><br><br><br>";
*/

/*
//validate create order transactions
echo "validate create order transactions 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey12&iType=4&iConftype=1&iSubscribing=Y&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=19&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US&iEmailAddr=test%40tester.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "validate create order transactions 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey13&iType=4&iConftype=1&iSubscribing=Y&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=19&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US&iEmailAddr=test%40tester.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "validate create order transactions 00105 NEW<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKeyNewOrderTest3&iType=4&iConftype=1&iSubscribing=Y&iPayOpt=Vi&iCCNum=4111111111111111&iCCExpMon=09&iCCExpYear=19&iSource=331B02&iTerm=12&iAmount=40&iFName=Joe&iLName=Romello&iPaddr=315%20Missimer%20Ave&iCity=Royersford&iState=PA&iPCode=19468&iCountry=US&iEmailAddr=joeromello%40yahoo.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
*/


echo "lookup customer by email<br>";
curl_setopt($ch,CURLOPT_URL, 
"https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKeyNewOrderTest2&iType=N&iptrans=Y&iLookUp=EMAIL&iEmailAddr=joeromello@yahoo.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br><br><br>";
echo "lookup customer by email<br>";
curl_setopt($ch,CURLOPT_URL, 
"https://secure.palmcoastd.com/pcdtest/xmlvaltran?iMagId=00105&iId=YourKeyNewOrderTest2&iType=N&iPTrans=Y&iLookUp=ADDRESS&iSubName=Joe%20Romello&iPAddr=315%20Missimer%20Ave&iPCode=19468&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br><br><br>";
echo "lookup customer by email<br>";
curl_setopt($ch,CURLOPT_URL, 
"https://secure.palmcoastd.com/pcdtest/xmlvaltran?iMagId=00105&iId=YourKeyNewOrderTest2&iType=N&iPTrans=Y&iLookUp=ADDRESS&iPAddr=315%20Missimer%20Ave&iPCode=19468&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "<br><br>";

/*
//process cybersource charge for out of house order only
echo "process cybersource charge for out of house order only 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKeyC&iType=5&iGateID=1248&iUserAccount=test&iEmailAddr=test%40testing.com&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US&iTerm=12&iAmount=35&iPayOpt=VI&iCCNum=4111111111111111&iCCExpMon=12&iCCExpYear=04&iCyberAuth=Y&iCyberBill=Y");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "process cybersource charge for out of house order only 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKeyC&iType=5&iGateID=1248&iUserAccount=test&iEmailAddr=test%40testing.com&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US&iTerm=12&iAmount=35&iPayOpt=VI&iCCNum=4111111111111111&iCCExpMon=12&iCCExpYear=04&iCyberAuth=Y&iCyberBill=Y");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//change of address
echo "change of address 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=6&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "change of address 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=6&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iFName=Joe&iLName=Green&iPaddr=11%20Commerce%20Blvd&iSAddr=Suite%2044&iCity=Palmcoast&iState=FL&iPCode=32164&iCountry=US");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//email change of address
echo "email change of address 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=7&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "email change of address 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=7&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//optout transaction
echo "optout transaction 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=8&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iGeneralOptOut=Y");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "optout transaction 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=8&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iGeneralOptOut=Y");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//customer service already paid
echo "customer service already paid 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=9&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iAmtPaid=5&icheckdate=11222002&icheckto=YourMagazine&iCheckcleared=02052002&iBankinfo=First%20Union&icomment=I%20payed%20my%20bill%20last%20year%20but%20your%20still%20charging%20me");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "customer service already paid 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=9&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iAmtPaid=5&icheckdate=11222002&icheckto=YourMagazine&iCheckcleared=02052002&iBankinfo=First%20Union&icomment=I%20payed%20my%20bill%20last%20year%20but%20your%20still%20charging%20me");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//customer service missed issue
echo "customer service missed issue 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=A&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iComment=I%20did%20not%20receive%20my%20November%202002%20issue%20please%20send%20it%20to%20me.");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "customer service missed issue 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=A&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iComment=I%20did%20not%20receive%20my%20November%202002%20issue%20please%20send%20it%20to%20me.");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//customer service other
echo "customer service other 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKey&iType=B&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iComment=Could%20you%20please%20send%20me%20lots%20of%20money.");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "customer service other 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKey&iType=B&iSecureKey=T9Xdi9k05TTQlQ0TlQde&iEmailAddr=testing%40testing.com&iComment=Could%20you%20please%20send%20me%20lots%20of%20money.");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";

//payment
echo "payment 00104<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00104&iId=YourKeyD&iType=C&iSecureKey=TiTdP9k22TTklQ0TlQUe&iAmtPaid=10.00&iTestCode=W&iPayOpt=VI&iCCNum=4111111111111111&iCCExpMon=08&iCCExpYear=10");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
echo "payment 00105<br>";
curl_setopt($ch,CURLOPT_URL, "https://secure.palmcoastd.com/pcdtest/valtran?iMagId=00105&iId=YourKeyD&iType=C&iSecureKey=TiTdP9k22TTklQ0TlQUe&iAmtPaid=10.00&iTestCode=W&iPayOpt=VI&iCCNum=4111111111111111&iCCExpMon=08&iCCExpYear=10");
$result = curl_exec($ch);
var_dump($result);
echo "<br><br>";
*/

//close connection
curl_close($ch);
die();
