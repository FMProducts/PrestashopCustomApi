<?php
	require_once '../connect.php';

	function getProductAttributeId($product_id){
		R::exec("INSERT INTO `ps_product_attribute` (`id_product_attribute`, `id_product`, `reference`, `supplier_reference`, `location`, `ean13`, `upc`, `wholesale_price`, `price`, `ecotax`, `quantity`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES (NULL, ?, 'vendorCode', NULL, NULL, NULL, NULL, '0.000000', '0.000000', '0.000000', '0', '0.000000', '0.000000', NULL, '1', CURRENT_TIMESTAMP)", [$product_id]);
		$productAttributes = R::getAll("SELECT * FROM `ps_product_attribute` WHERE `ps_product_attribute`.`id_product` = ? ORDER BY `ps_product_attribute`.`id_product_attribute` DESC LIMIT 0, 1", [$product_id]);

		return $productAttributes[0]['id_product_attribute'] ?? 0;
	}

	function createUser(){
		$firstname = "unknown";
		$lastname = "unknown";
		$email = "guest@gmail.com";
		$queryResult = R::exec("INSERT INTO `ps_customer` (`id_customer`, `id_shop_group`, `id_shop`, `id_gender`, `id_default_group`, `id_lang`, `id_risk`, `company`, `siret`, `ape`, `firstname`, `lastname`, `email`, `passwd`, `last_passwd_gen`, `birthday`, `newsletter`, `ip_registration_newsletter`, `newsletter_date_add`, `optin`, `website`, `outstanding_allow_amount`, `show_public_prices`, `max_payment_days`, `secure_key`, `note`, `active`, `is_guest`, `deleted`, `date_add`, `date_upd`) VALUES (NULL, '1', '1', '1', '1', NULL, '1', NULL, NULL, NULL, ?, ?, ?, '', CURRENT_TIMESTAMP, NULL, '0', NULL, NULL, '0', NULL, '0.000000', '0', '60', '-1', NULL, '0', '1', '0', CURRENT_TIMESTAMP,CURRENT_TIMESTAMP )", [$firstname , $lastname, $email]);


		$customers = R::getAll("SELECT `ps_customer`.`id_customer` as 'id', `ps_customer`.`is_guest` as 'guest', `ps_customer`.`firstname`, `ps_customer`.`lastname` FROM `ps_customer` WHERE `ps_customer`.`is_guest` = 1 AND `ps_customer`.`email` = ? AND `ps_customer`.`lastname` = ? AND `ps_customer`.`firstname` = ? ORDER BY `ps_customer`.`id_customer` DESC LIMIT 0 , 1", [$email, $lastname, $firstname]);
		return $customers[0];
	}

	function createCart($customer_id){
		$result['cart_result'] = R::exec("INSERT INTO `ps_cart` (`id_cart`, `id_shop_group`, `id_shop`, `id_carrier`, `delivery_option`, `id_lang`, `id_address_delivery`, `id_address_invoice`, `id_currency`, `id_customer`, `id_guest`, `secure_key`, `recyclable`, `gift`, `gift_message`, `mobile_theme`, `allow_seperated_package`, `date_add`, `date_upd`) VALUES (NULL, '1', '1', '1', '', '1', '0', '0', '1', ?, ?, '-1', '1', '0', NULL, '0', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", [$customer_id, $customer_id]);
	}

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$customer_id = $_GET['customer_id'] ?? 0;
	$product_id = $_GET['product_id'] ?? 0;
	$quantity = $_GET['quantity'] ?? 0;
	$attributes_str = $_GET['attributes'] ?? "[]";
 	
	$customer = NULL;
	$attributes = json_decode($attributes_str);



	$result = array('code' => 0);
	if ($customer_id == 0) {
		$customer = createUser();
		$customer_id = $customer['id'];
		createCart($customer_id);
	}

	$cart = R::getAll("SELECT * FROM `ps_cart` WHERE `ps_cart`.`id_customer` = ? ORDER BY `ps_cart`.`id_cart` DESC LIMIT 0,1", [$customer_id]);

	if(count($cart) > 0){
		$cart_id = $cart[0]['id_cart'];
		$id_product_attribute = getProductAttributeId($product_id);
		foreach ($attributes as $value) {

			R::exec("INSERT INTO `ps_product_attribute_combination` (`id_attribute`, `id_product_attribute`) VALUES (?, ?)", [$value, $id_product_attribute]);
		}
		$code = R::exec("INSERT INTO `ps_cart_product` (`id_cart`, `id_product`, `id_address_delivery`, `id_shop`, `id_product_attribute`, `quantity`, `date_add`) VALUES (?, ?, '0', '1', ?, ?, CURRENT_TIMESTAMP)", [$cart_id, $product_id,$id_product_attribute, $quantity]);
		if (!empty($customer)) {
			$customer['userCart'] = $cart_id;
		}
		$result['code'] = 1;
	}
	else{
		$result['code'] = 0;
	}


	$result['customer'] = $customer ?? NULL;
	print(json_encode($result, JSON_NUMERIC_CHECK));