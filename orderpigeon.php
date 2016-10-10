<?php

/* 
 * main program - just execute the Order Pigeon API controller 
 */
$orderpigeon = new OrderPigeonAPIController();
$orderpigeon->execute(); 

/* 
 * Magento Order Pigeon API   
 */
class OrderPigeonAPIController { 

	// PDO database connection 
	protected $conn = null;

	// transaction flag to rollback in case of errors  
	protected $transaction = false;
	
	/* 
	 * constructor - initializations common to all api calls 
	 */
	public function __construct() { 

		// setup database connection and initialize PDO database connection object
		$this->get_db_connection();
		
		// normally we are not in a transaction - if a particular API call requires transactions, this flag should be set 
		$this->transaction = false;
	}

	/*
	 * authenticate api username and password before doing anything else
	 * verify username and password the same way API does it when establishing a session
	*/
	protected function authenticate() { 
		
		// get sent username and password 
		$username = $this->prm('username');
		$password = $this->prm('password');
		
		// get user api key 
		$statement = $this->conn->prepare("select api_key from api_user where username = :username");
		$statement->bindValue(':username', $username, PDO::PARAM_STR);
		$statement->execute();
		$apikey = $statement->fetchColumn();
		
		// if user is not found, error out 
		if (!$apikey) $this->api_error('User invalid. Please try again with a correct username.');

		// api key may or may not have salt 
		$hashArr = explode(':', $apikey);
		
		// if there are 3 sections, that is an unknown format - error out
		if (count($hashArr) != 1 && count($hashArr) != 2) $this->api_error('Invalid api key. Please correct api user setup and try again.');  

		// authentication without salt 
		if (count($hashArr) == 1 && md5($password) != $apikey) $this->api_error('Invalid password. Please try again with correct password.');

		// authentication with salt 
		if (count($hashArr) == 2 && md5($hashArr[1] . $password) != $hashArr[0]) $this->api_error('Invalid credentials. Please try again with correct password.');
	}
	
	/* 
	 * main API execution handler 
	 */
	public function execute() {
		
		// authenticate credentials before processing request
		// this interface only works with an established SOAP API session (obtained via SOAP API login call) 
		// clients usually have problems setting up the API in Magento, so we tried to eliminate the API altogether 
		// as seen with the authenticate implementation above but for shipment upload we definitely need the API 
		// shipment upload logic is too complicated and customizable - we will break something if we bypass it 
		// $this->authenticate(); 
		$session = $this->prm('session');
		$statement = $this->conn->prepare("select 1 from api_session where sessid = :session");
		$statement->bindValue(':session', $session, PDO::PARAM_STR);
		$statement->execute();
		$session_valid = $statement->fetchColumn();
		if (!$session_valid) $this->api_error('Session invalid. Please login and try again.');
		
		// process API request
		try {
		
			switch ($this->prm('request')) {
				case 'get_product_count': $this->get_product_count(); break;
				case 'get_products': $this->get_products(); break;
				case 'get_product': $this->get_product(); break;
				case 'get_products_udropship': $this->get_products_udropship(); break;
				case 'get_product_udropship': $this->get_product_udropship(); break;
				case 'update_inventory': $this->transaction = true; $this->conn->query("start transaction"); $this->update_inventory(); break;
				case 'update_product_udropship': $this->update_product_udropship(); break;
				case 'get_open_orders': $this->get_open_orders(); break;
				case 'get_order_details': $this->get_order_details(); break; 
				case 'add_order_comment': $this->add_order_comment(); break;
				default: $this->api_error('Unknown request.'); break;
			}
		}
		catch (Exception $ex) {
		
			// error - rollback if we are in transaction before returning error
			if ($this->transaction) $this->conn->query("rollback");
		
			// return the error to the client
			$this->api_error($ex->getMessage() . ' ' . $ex->getTraceAsString());
		}
		
		// we should never reach this point
		$this->api_error('Request not handled correctly.');
	}
	
	/* 
	 * API parameters are always passed in via POST 
	 */
	protected function prm($prmname) { 
		return filter_input(INPUT_POST, $prmname);
	}
	
	/* 
	 * returns an attribute id based on a set of possible product attribute codes 
	 */
	protected function get_attribute_id($attributecodes) { 
		return intval($this->conn->query("
		select a.attribute_id
		from eav_entity_type et
		join eav_attribute a on a.entity_type_id = et.entity_type_id  
		where et.entity_type_code = 'catalog_product'
		and   a.attribute_code in ('" . implode("','",$attributecodes) . "')
		")->fetchColumn(0));
	}
	
	/* 
	 * returns the details for an attribute 
	 */
	protected function get_attribute($attributecode) { 
		
		$stmt = $this->conn->prepare("
		select a.attribute_id, a.backend_type, a.frontend_input
		from eav_entity_type et
		join eav_attribute a on a.entity_type_id = et.entity_type_id  
		where et.entity_type_code = 'catalog_product'
		and   a.attribute_code = :attribute_code
		");
		$stmt->bindValue(':attribute_code', $attributecode, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/* 
	 * returns the stock id from the sent stock name  
	 */
	protected function get_stock_id() {
		$stmt = $this->conn->prepare("select stock_id from cataloginventory_stock where stock_name = :stock_name");
		$stmt->bindValue(':stock_name', $this->prm('stock'), PDO::PARAM_STR);
		$stmt->execute();
		return intval($stmt->fetchColumn(0));
	}
	
	/* 
	 * returns the sql for retrieving products 
	 */
	protected function get_products_sql() {

		// are we going to use brand and/or manufacturer - that changes a lot of things   
		$brand_attribute = $this->get_attribute('brand');
		$manufacturer_attribute = $this->get_attribute('manufacturer');

		// if neither brand or manufacturer is found, error out 
		if (!$brand_attribute && !$manufacturer_attribute) throw new Exception('Brand/Manufacturer attributes not found.');
		
		// prepare brand attribute join expression
		$brandjoin = '';
		if ($brand_attribute) {

			// build join expression changes if the front-end is multi-select - only get the ones where we can find exactly a single value 
			// when there are multiple values, the varchar attribute specifies option ids comma separated, in which case we would not be able to match 
			// in the future we should improve this logic by taking the first option id in the join - for now, the multiple branded products will have no brands    
			if ($brand_attribute['frontend_input'] == 'multiselect') {
				$brandjoin = "
				left join catalog_product_entity_varchar bv on bv.entity_id = e.entity_id and bv.attribute_id = {$brand_attribute['attribute_id']} and bv.store_id = 0
				left join eav_attribute_option bo on bv.value = bo.option_id and bo.attribute_id = bv.attribute_id
				left join eav_attribute_option_value b on b.option_id = bo.option_id and b.store_id = 0
				";
			}
			// build join expression changes if the front-end is select 
			elseif ($brand_attribute['frontend_input'] == 'select') {
				$brandjoin = "
				left join catalog_product_entity_int bv on bv.entity_id = e.entity_id and bv.attribute_id = {$brand_attribute['attribute_id']} and bv.store_id = 0
				left join eav_attribute_option bo on bv.value = bo.option_id and bo.attribute_id = bv.attribute_id 
				left join eav_attribute_option_value b on b.option_id = bo.option_id and b.store_id = 0 
				";
			} 
			// regular attribute join
			else { 
				$brandjoin = "left join catalog_product_entity_{$brand_attribute['backend_type']} b on b.entity_id = e.entity_id and b.attribute_id = {$brand_attribute['attribute_id']} and b.store_id = 0";
			} 
		}
			
		// prepare manufacturer attribute join expression
		$manufacturerjoin = '';
		if ($manufacturer_attribute) {

			// build join expression changes if the front-end is multi-select - only get the ones where we can find exactly a single value
			// when there are multiple values, the varchar attribute specifies option ids comma separated, in which case we would not be able to match
			// in the future we should improve this logic by taking the first option id in the join - for now, the multiple branded products will have no brands
			if ($manufacturer_attribute['frontend_input'] == 'multiselect') {
				$manufacturerjoin = "
				left join catalog_product_entity_varchar mv on mv.entity_id = e.entity_id and mv.attribute_id = {$manufacturer_attribute['attribute_id']} and mv.store_id = 0
				left join eav_attribute_option mo on mv.value = mo.option_id and mo.attribute_id = mv.attribute_id
				left join eav_attribute_option_value m on m.option_id = mo.option_id and m.store_id = 0
				";
			}
			// build join expression changes if the front-end is select 
			elseif ($manufacturer_attribute['frontend_input'] == 'select') {
				$manufacturerjoin = "
				left join catalog_product_entity_int mv on mv.entity_id = e.entity_id and mv.attribute_id = {$manufacturer_attribute['attribute_id']} and mv.store_id = 0
				left join eav_attribute_option mo on mv.value = mo.option_id and mo.attribute_id = mv.attribute_id 
				left join eav_attribute_option_value m on m.option_id = mo.option_id and m.store_id = 0 
				";
			} 
			// regular attribute join
			else { 
				$manufacturerjoin = "left join catalog_product_entity_{$manufacturer_attribute['backend_type']} m on m.entity_id = e.entity_id and m.attribute_id = {$manufacturer_attribute['attribute_id']} and m.store_id = 0";
			} 
		}
			
		// prepare brand/manufacturer joins to determine final brand name for a product
		if ($brand_attribute && $manufacturer_attribute) $brandmanufacturersql = 'ifnull(b.value, m.value)';
		elseif ($brand_attribute) $brandmanufacturersql = 'b.value';
		else $brandmanufacturersql = 'm.value';
		
		// get the stock id to pull inventory from (usually the default single stock)
		$stock_id = $this->get_stock_id();
		// debug: echo $stock_id; exit;  
		
		// return the final sql for getting product information to be returned  
		return "
		select e.sku, ifnull(n.value,'') as product_name, ifnull($brandmanufacturersql,'') as brand_name, ifnull(bc.value,'') as brand_code, ifnull(u.value,'') as upc, ifnull(mpn.value,'') as mpn, ifnull(p.value,0) as price, ifnull(c.value,0) as cost, ifnull(s.qty,0) as total_quantity
		from catalog_product_entity e
		left join catalog_product_entity_varchar n on n.entity_id = e.entity_id and n.attribute_id = " . $this->get_attribute_id(array('name')) . " and n.store_id = 0
		left join catalog_product_entity_decimal p on p.entity_id = e.entity_id and p.attribute_id = " . $this->get_attribute_id(array('price')) . " and p.store_id = 0 
		left join catalog_product_entity_decimal c on c.entity_id = e.entity_id and c.attribute_id = " . $this->get_attribute_id(array('cost')) . " and c.store_id = 0 
		left join catalog_product_entity_varchar u on u.entity_id = e.entity_id and u.attribute_id = " . $this->get_attribute_id(array('upc')) . " and u.store_id = 0
		left join cataloginventory_stock_item s on s.product_id = e.entity_id and s.stock_id = {$stock_id}
		left join catalog_product_entity_varchar mpn on mpn.entity_id = e.entity_id and mpn.attribute_id = " . $this->get_attribute_id(array('mpn','manufacturer_sku','part_number')) . " and mpn.store_id = 0
		left join catalog_product_entity_varchar bc on bc.entity_id = e.entity_id and bc.attribute_id = " . $this->get_attribute_id(array('brand_code')) . " and bc.store_id = 0 
		$brandjoin
		$manufacturerjoin
		" . $this->get_last_update_sql();
	}
	
	/* 
	 * returns the sql for retrieving products with udropship info 
	 */
	protected function get_products_udropship_sql() { 
		return "
		select e.sku, vp.vendor_sku as external_sku, vp.vendor_cost as cost, vp.stock_qty as total_quantity, 
		    n.value as product_name, p.value as price, u.value as upc, mv.value as brand_name, mpn.value as mpn
		from catalog_product_entity e
			join udropship_vendor_product vp on vp.product_id = e.entity_id
			join udropship_vendor v on v.vendor_id = vp.vendor_id
			left join catalog_product_entity_varchar n on n.entity_id = e.entity_id and n.attribute_id = " . $this->get_attribute_id(array('name')) . " and n.store_id = 0
			left join catalog_product_entity_decimal p on p.entity_id = e.entity_id and p.attribute_id = " . $this->get_attribute_id(array('price')) . " and p.store_id = 0 
			left join catalog_product_entity_varchar u on u.entity_id = e.entity_id and u.attribute_id = " . $this->get_attribute_id(array('upc')) . " and u.store_id = 0
			left join catalog_product_entity_varchar mpn on mpn.entity_id = e.entity_id and mpn.attribute_id = " . $this->get_attribute_id(array('mpn','manufacturer_sku')) . " and mpn.store_id = 0
			left join catalog_product_entity_int m on m.entity_id = e.entity_id and m.attribute_id = " . $this->get_attribute_id(array('brand','manufacturer')) . " and m.store_id = 0
			left join eav_attribute_option mo on m.value = mo.option_id and mo.attribute_id = m.attribute_id 
			left join eav_attribute_option_value mv on mv.option_id = mo.option_id and mv.store_id = 0 
		";
	}
	
	/* 
	 * returns product info  
	 */
	protected function get_product_udropship() {
	
		// get product info
		$stmt = $this->conn->prepare($this->get_products_udropship_sql() . "where e.sku = :sku");
		$stmt->bindValue(':sku', $this->prm('sku'), PDO::PARAM_STR);
		$stmt->execute();
		$product = $stmt->fetch(); 
		
		// return product info 
		$this->api_return($product);
	}
	
	/* 
	 * updates quantity and cost for a batch of products in Magento
	 */
	protected function update_inventory() {
	
		// get the products to be updated
		$products = unserialize(base64_decode($this->prm('products')));
		// debug: print_r($products); exit;
		
		// get the product ids indexed by skus for fast access 
		$skus = array(); foreach ($products as $product) $skus[] = $product['sku'];
		$product_ids = array(); 
		foreach ($this->conn->query("select entity_id, sku from catalog_product_entity where sku in ('" . implode("','", $skus) . "')")->fetchAll() as $prd) 
			$product_ids[$prd['sku']] = $prd['entity_id'];

		// direct list of entity ids for fast access in queries  
		$entity_ids = implode(',',array_values($product_ids)); 

		// get the stock id to update inventory with (usually the default single stock)
		$stock_id = $this->get_stock_id();
		// debug: echo $stock_id; exit;
		  
		// prepare the statement to update quantity   
		$qtystmt = $this->conn->prepare("update cataloginventory_stock_item set qty = :new_quantity where product_id = :product_id and stock_id = {$stock_id}");

		// prepare the statement to update costs 
		$coststmt = $this->conn->prepare("update catalog_product_entity_decimal set value = :new_cost where entity_id = :product_id and attribute_id = " . $this->get_attribute_id(array('cost')) . " and store_id = 0"); 
		
		// update each product one at a time - this can probably be more efficient if we batch up the update requests - either with replace or multi-statement queries but for now it's fast enough 
		foreach ($products as $product) {   
		
			// debug: echo "Updating: " . print_r($product, true) . "\n"; 
			
			// update quantity if needed 
			if (isset($product['qty'])) { 
				$qtystmt->bindValue(':product_id', $product_ids[$product['sku']], PDO::PARAM_INT);
				$qtystmt->bindValue(':new_quantity', $product['qty'], PDO::PARAM_INT);
				$qtystmt->execute();
			}
			
			// update cost if needed 
			if (isset($product['cost'])) {
				$coststmt->bindValue(':product_id', $product_ids[$product['sku']], PDO::PARAM_INT);
				$coststmt->bindValue(':new_cost', $product['cost'], PDO::PARAM_STR);
				$coststmt->execute();
			}
		} 
		
		// update inventory stock status   
		$this->conn->query("update cataloginventory_stock_item set is_in_stock = if(qty > 0, 1, 0) where product_id in (" . $entity_ids . ") and stock_id = {$stock_id}");
			
		// update inventory stock index for the front end    
		$this->conn->query("
		update cataloginventory_stock_item item_stock, cataloginventory_stock_status status_stock
		set status_stock.qty = item_stock.qty, status_stock.stock_status = item_stock.is_in_stock
		where item_stock.product_id = status_stock.product_id
		and   item_stock.stock_id = status_stock.stock_id
		and   item_stock.product_id in (" . $entity_ids . ") 
		and   item_stock.stock_id = {$stock_id}
		");

		// set last update time for the products 
		$this->conn->query("update catalog_product_entity set updated_at = current_timestamp() where entity_id in (" . $entity_ids . ")");
		
		// update worked fine - commit and return success   
		$this->api_return();
	}
	
	/* 
	 * updates product quantity and cost in uDropship table 
	 */
	protected function update_product_udropship() {
	
		// determine which values to update based on the sent parameters  
		$update_quantity = isset($_POST['quantity']); 
		$update_cost = isset($_POST['cost']);
		
		// prepare the statement to update the product udropship information 
		$stmt = $this->conn->prepare("
		update udropship_vendor_product set  
				product_id = product_id  
		" . ($update_quantity ? " , stock_qty = :quantity " : "") . "
		" . ($update_cost ? " , vendor_cost = :cost " : "") . "
		where product_id = (select entity_id from catalog_product_entity where sku = :sku)
		and   vendor_id = (select vendor_id from udropship_vendor where vendor_name = :vendor)   
		");
				
		// add applicable parameters to the update statement  
		$stmt->bindValue(':sku', $this->prm('sku'), PDO::PARAM_STR);
		$stmt->bindValue(':vendor', $this->prm('vendor'), PDO::PARAM_STR);
		if ($update_quantity) $stmt->bindValue(':quantity', $this->prm('quantity'), PDO::PARAM_INT);
		if ($update_cost) $stmt->bindValue(':cost', $this->prm('cost'), PDO::PARAM_STR);

		// now execute the update - if there are any errors, we will throw an exception  
		$stmt->execute();
		
		// update worked fine - return success  
		$this->api_return();
	}
	
	/* 
	 * returns products count in the database 
	 */
	protected function get_product_count() {
	
		// get all products count and return it 
		$this->api_return($this->conn->query("select count(*) from catalog_product_entity e" . $this->get_last_update_sql())->fetch(PDO::FETCH_COLUMN));
	}

	/* 
	 * returns the selection criteria sql to get the products created/updated since the last inventory pull   
	 */
	protected function get_last_update_sql() {
		
		// if the last update parameter is empty, no filter needed 
		if (!$this->prm('last_update')) return '';
		
		// do the conversion from last update start of the client to our time based on current time of the client and our current time 
		// echo 'Current client time: ' . $this->prm('current_time') . "\n"; 
		// echo 'Current server time: ' . $this->conn->query("select current_timestamp()")->fetchColumn(0) . "\n";
		// echo 'Client last update start time: ' . $this->prm('last_update') . "\n"; 
		// echo (strtotime($this->prm('current_time')) - strtotime($this->prm('last_update'))) . "\n";   
		// echo date('Y-m-d H:i:s', strtotime($this->conn->query("select current_timestamp()")->fetchColumn(0)) - (strtotime($this->prm('current_time')) - strtotime($this->prm('last_update')))); exit;
		$local_update_time = date('Y-m-d H:i:s', strtotime($this->conn->query("select current_timestamp()")->fetchColumn(0)) - (strtotime($this->prm('current_time')) - strtotime($this->prm('last_update'))));
		// debug: echo $local_update_time; exit;

		// return the where condition to get the products created/updated after the requested time 
		return " where e.updated_at >= '{$local_update_time}' "; 
	}
	
	/* 
	 * returns existing products 
	 */
	protected function get_products() {

		$batchno = $this->prm('batchno');
		// debug: echo $batchno; exit;

		// get all products - prepare and execute statement    
		$stmt = $this->conn->prepare($this->get_products_sql() . " order by sku limit " . ($batchno * 10000) . ', 10000');
		$stmt->execute();
		
		// output the products in a cursor - we don't fetch the entire thing at once to preserve memory - output to standard out (send to client) directly in CSV format  
		$stdoutfd = fopen('php://output', 'w');
		while ($product = $stmt->fetch()) fputcsv($stdoutfd, $product);
		
		// close the cursor and stdout 
		$stmt->closeCursor();
		fclose($stdoutfd);
		
		// we do not return like the other API calls here since we output direct CSV  
		exit; 
	}

	/* 
	 * returns product info  
	 */
	protected function get_product() {
	
		// get product info
		$stmt = $this->conn->prepare($this->get_products_sql() . "where e.sku = :sku");
		$stmt->bindValue(':sku', $this->prm('sku'), PDO::PARAM_STR);
		$stmt->execute();
		$product = $stmt->fetch(); 
		
		// return product info 
		$this->api_return($product);
	}
	
	/* 
	 * returns existing products with udropship info 
	 */
	protected function get_products_udropship() {
	
		// get all products - prepare and execute statement    
		$stmt = $this->conn->prepare($this->get_products_udropship_sql() . "where v.vendor_name = :vendor");
		$stmt->bindValue(':vendor', $this->prm('vendor'), PDO::PARAM_STR);
		$stmt->execute();
		
		// output the products in a cursor - we don't fetch the entire thing at once to preserve memory - output to standard out (send to client) directly in CSV format  
		$stdoutfd = fopen('php://output', 'w');
		while ($product = $stmt->fetch()) fputcsv($stdoutfd, $product);
		
		// close the cursor and stdout 
		$stmt->closeCursor();
		fclose($stdoutfd);
		
		// we do not return like the other API calls here since we output direct CSV  
		exit; 
	}

	/* 
	 * returns currently open orders (processing)  
	 */
	protected function get_open_orders() {

		// get all products - prepare and execute statement    
		$orders = $this->conn->query("
		select 
			o.entity_id as order_id, o.increment_id as ordersite_order_id, o.created_at as order_time, 
			o.customer_firstname, o.customer_lastname, o.customer_email,
			b.firstname as billing_firstname, b.lastname as billing_lastname, b.company as billing_company, b.street as billing_address, b.city as billing_city, 
			b.region as billing_state, b.postcode as billing_zip, b.country_id as billing_country, b.telephone as billing_phone,
			s.firstname as shipping_firstname, s.lastname as shipping_lastname, s.company as shipping_company, s.street as shipping_address, s.city as shipping_city, 
			s.region as shipping_state, s.postcode as shipping_zip, s.country_id as shipping_country, s.telephone as shipping_phone,
			o.shipping_description as shipping_description 
		from sales_flat_order o 
			left join sales_flat_order_address b on o.entity_id = b.parent_id and b.address_type = 'billing'
			left join sales_flat_order_address s on o.entity_id = s.parent_id and s.address_type = 'shipping' 
		where o.state = 'processing'				
		")->fetchAll();
		
		// if there are no orders, we are done - return empty array 
		if (!$orders) $this->api_return(array());
		
		// add order id for each order
		// adding order id into order array key to make it more convinient
		// to work with on the next step
		for ($i = 0; $i < count($orders); $i++) 
		{ 
			$orders[intval($orders[$i]['order_id'])] = $orders[$i]; 
			unset($orders[$i]); 
		}

		// now get the paid but unshipped products of the open orders that are not cancelled or refunded
		/*  
		$order_products = $this->conn->query("
		select sku as product_sku, name as product_name, qty_ordered - qty_canceled - qty_refunded - qty_shipped as product_quantity, price as product_price, order_id  
		from sales_flat_order_item
		where order_id in (" . implode(',',array_keys($orders)) . ")
			and   qty_invoiced > 0 
			and   qty_shipped < (qty_ordered - qty_canceled - qty_refunded)
		")->fetchAll();
		*/
		
		// Changed by Customer Paradigm Timur and Tom and Scott
		// To exclude products that do not have property HG(id=5)
		$order_products = $this->conn->query("
			SELECT
				si.sku as product_sku,
				si.name as product_name,
				si.qty_ordered - si.qty_canceled - si.qty_refunded - si.qty_shipped as product_quantity,
				si.price as product_price,
				si.order_id
			FROM
					sales_flat_order_item si 
				JOIN
						sales_flat_order sfo
					ON
							sfo.entity_id = si.order_id
						AND
							sfo.state = 'processing'
				JOIN
						catalog_product_flat_1 sf
					ON
						sf.entity_id = si.product_id
			WHERE
					si.qty_invoiced > 0
				AND
					si.qty_shipped < (si.qty_ordered - si.qty_canceled - si.qty_refunded)
				AND
					sf.vendor = 5
		")->fetchAll();
		
		// add order products to the relevant orders
		foreach ($order_products as $order_product) 
		{
			if (isset($orders[$order_product['order_id']]['order_products']))
			{
				$orders[$order_product['order_id']]['order_products'][] = $order_product;
			}
			else 
			{
				$orders[$order_product['order_id']]['order_products'] = array($order_product);
			}
		} 
		
		// now remove the orders with no products 
		foreach ($orders as $order_id => $order) if (!isset($order['order_products'])) unset($order[$order_id]);
		
		// now remove unnecessary order ids from products 
		foreach ($orders as $order_id => $order) for ($i = 0; $i < count($order['order_products']); $i++) unset($orders[$order_id]['order_products'][$i]['order_id']);
			
		// return open orders   
		$this->api_return($orders); 
	}

	/* 
	 * returns orders details for the UI   
	 */
	protected function get_order_details() {

		// get all products - prepare and execute statement    
		$stmt = $this->conn->prepare("
		select o.*, 
			b.firstname as billing_firstname, b.lastname as billing_lastname, b.company as billing_company, b.street as billing_address, b.city as billing_city, 
			b.region as billing_state, b.postcode as billing_zip, b.country_id as billing_country, b.telephone as billing_phone,
			s.firstname as shipping_firstname, s.lastname as shipping_lastname, s.company as shipping_company, s.street as shipping_address, s.city as shipping_city, 
			s.region as shipping_state, s.postcode as shipping_zip, s.country_id as shipping_country, s.telephone as shipping_phone
		from sales_flat_order o 
		left join sales_flat_order_address b on o.entity_id = b.parent_id and b.address_type = 'billing'
		left join sales_flat_order_address s on o.entity_id = s.parent_id and s.address_type = 'shipping' 
		where o.increment_id = :order_id				
		");
		$stmt->bindValue(':order_id', $this->prm('order_id'), PDO::PARAM_STR);
		$stmt->execute();
		$order = $stmt->fetch();
		
		// now get the order products   
		if (isset($order['entity_id'])) $order['order_products'] = $this->conn->query("select * from sales_flat_order_item where order_id = " . $order['entity_id'])->fetchAll();
		
		// return order details   
		$this->api_return($order); 
	}
	
	/* 
	 * adds an order comment without changing the status (we have a separate call for it becuase using the API, it would be 2 calls - get order status and call add order notes with the current status)   
	 */
	protected function add_order_comment() {

		// add order comment without changing order status     
		$stmt = $this->conn->prepare("
		insert into sales_flat_order_status_history (parent_id, is_customer_notified, comment, status, created_at, entity_name) 
		select entity_id, 0 as is_customer_notified, :order_comments, status, current_timestamp(), 'order' as entity_name  
		from sales_flat_order 
		where increment_id = :order_id
		");
		$stmt->bindValue(':order_id', $this->prm('order_id'), PDO::PARAM_STR);
		$stmt->bindValue(':order_comments', $this->prm('order_comments'), PDO::PARAM_STR);
		$stmt->execute();
		
		// return success   
		$this->api_return(); 
	}

	/*
	 * return successful API response
	*/
	protected function api_return($data = '') {
	
		// if we are in a transaction, commit it
		if ($this->transaction) $this->conn->query("commit");
	
		// return serialized response
		$response = array();
		$response['success'] = '1';
		$response['response'] = $data;
		echo serialize($response);
		exit;
	}
	
	/*
	 * return API error
	*/
	function api_error($errmsg) {
		echo serialize(array('error' => $errmsg));
		exit;
	}
	
	/*
	 * get Magento database connection
	*/
	protected function get_db_connection() {
	
		// database connection parameters are in local.xml
		if (!file_exists('app/etc/local.xml' )) $this->api_error('Failed to open app/etc/local.xml');
	
		// Parse magento's local.xml to get db info
		$xml = simplexml_load_file('app/etc/local.xml');
		$dbhost = $xml->global->resources->default_setup->connection->host;
		$dbuser = $xml->global->resources->default_setup->connection->username;
		$dbpass = $xml->global->resources->default_setup->connection->password;
		$dbname = $xml->global->resources->default_setup->connection->dbname;
	
		// setup database connection 
		try {
			$this->conn = new PDO('mysql:host=' . $dbhost . ';dbname='. $dbname , $dbuser, $dbpass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			$this->api_error('DATABASE CONNECTION ERROR: ' . $e->getMessage());
		}
	}	
}



