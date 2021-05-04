<?php
	require_once '../connect.php';
	$result = array('code' => 0);

	function passwdGen($length = 8, $flag = 'ALPHANUMERIC')
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function orderDetailCreate($reference, $cart_id){
		$orders = R::getAll("SELECT * FROM `ps_orders` WHERE `ps_orders`.`reference` = ? LIMIT 0 , 1", [$reference]);
		foreach ($orders as $order) {
			$products = R::getAll("SELECT `ps_cart_product`.`id_product`, `ps_cart_product`.`id_product_attribute`, `ps_product_lang`.`name`, `ps_cart_product`.`quantity`, `ps_product`.`price` FROM `ps_cart_product` LEFT JOIN `ps_product_lang` ON `ps_product_lang`.`id_product` = `ps_cart_product`.`id_product` LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_cart_product`.`id_product` WHERE `ps_cart_product`.`id_cart` = ? AND `ps_product_lang`.`id_lang` = 1", [$cart_id]);
			foreach ($products as $product) {
				$name = $product['name'];
				R::exec("INSERT INTO `ps_order_detail` (`id_order_detail`, `id_order`, `id_order_invoice`, `id_warehouse`, `id_shop`, `product_id`, `product_attribute_id`, `product_name`, `product_quantity`, `product_quantity_in_stock`, `product_quantity_refunded`, `product_quantity_return`, `product_quantity_reinjected`, `product_price`, `reduction_percent`, `reduction_amount`, `reduction_amount_tax_incl`, `reduction_amount_tax_excl`, `group_reduction`, `product_quantity_discount`, `product_ean13`, `product_upc`, `product_reference`, `product_supplier_reference`, `product_weight`, `id_tax_rules_group`, `tax_computation_method`, `tax_name`, `tax_rate`, `ecotax`, `ecotax_tax_rate`, `discount_quantity_applied`, `download_hash`, `download_nb`, `download_deadline`, `total_price_tax_incl`, `total_price_tax_excl`, `unit_price_tax_incl`, `unit_price_tax_excl`, `total_shipping_price_tax_incl`, `total_shipping_price_tax_excl`, `purchase_supplier_price`, `original_product_price`, `original_wholesale_price`) VALUES (NULL, ?, '112', '0', '1', ?, ?, ? , ?, '0', '0', '0', '0', ?, '0.00', '0.000000', '0.000000', '0.000000', '0.00', '0.000000', '', '', '', '', '0.000000', '0', '0', '', '0.000', '0.000000', '0.000', '0', '', '0', CURRENT_TIMESTAMP, ?, ?, ?, ?, '0.000000', '0.000000', ?, ?, ?)", [$order['id_order'], $product['id_product'], $product['id_product_attribute'], $name, $product['quantity'], $product['price'],$product['price'],$product['price'],$product['price'],$product['price'],$product['price'],$product['price'],$product['price']]);
			}
		}

	}

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$reference = strtoupper(passwdGen(9, 'NO_NUMERIC'));
		$secure_key = "5dbb3c27abe3cb6d48488376dead0227";

		$customer_id = $_POST['customer_id'] ?? 0;
		$cart_id = $_POST['cart_id'] ?? 0;
		$address_id = $_POST['address_id'] ?? 0;
		$payment_method = $_POST['payment_method'] ?? "unkown";
		$price = $_POST['total_price'] ?? 0;

		$code = R::exec("INSERT INTO `ps_orders` (`reference`, `id_carrier`, `id_lang`, `id_customer`, `id_cart`, `id_currency`, `id_address_delivery`, `id_address_invoice`, `current_state`,`secure_key`, `payment`, `module`, `total_paid`, `total_paid_tax_incl`, `total_paid_tax_excl`, `total_paid_real`, `total_products`, `total_products_wt`, `round_type`, `valid`,`date_add`, `date_upd`, `invoice_date`, `delivery_date`) VALUES  (?, 2, 1, ?, ?, 2, ?, ?, 21, ?,  ?, 'modul_name', ?, ?, ?, ?, ?,?,2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)",[$reference, $customer_id, $cart_id, $address_id, $address_id, $secure_key, $payment_method, $price, $price, $price, $price, $price, $price]);

		orderDetailCreate($reference, $cart_id);
		R::exec("INSERT INTO `ps_cart` (`id_carrier`, `delivery_option`, `id_lang`, `id_address_delivery`, `id_address_invoice`, `id_currency`, `id_customer`, `id_guest`, `secure_key`, `date_add`, `date_upd`) VALUES ( 2, '{}', 1, ?, ?, 2, ?, 0, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", [$address_id, $address_id, $customer_id,$secure_key]);
		$cart = R::getAll("SELECT * FROM `ps_cart` WHERE `ps_cart`.`id_customer` = ? ORDER BY `ps_cart`.`id_cart` DESC LIMIT 0,1", [$customer_id]);

		$result['cart_id'] = $cart[0]['id_cart'];
		$result['code'] = $code;
	}


	print(json_encode($result, JSON_NUMERIC_CHECK));