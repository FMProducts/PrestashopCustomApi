<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$customer_id = $_GET['customer_id'] ?? 0;
	$result = array('code' => 1);


	$orders = R::getAll("SELECT `ps_orders`.`reference`, `ps_orders`.`total_paid_real` as 'price', `ps_orders`.`date_add` as 'created_at', `ps_image`.`id_image` as 'cover', `ps_orders`.`current_state` as 'state' FROM `ps_orders` LEFT JOIN `ps_cart` ON `ps_cart`.`id_cart` = `ps_orders`.`id_cart` LEFT JOIN `ps_cart_product` ON  `ps_cart_product`.`id_cart` = `ps_cart`.`id_cart` LEFT JOIN `ps_image` ON `ps_image`.`id_product` = `ps_cart_product`.`id_product` WHERE `ps_orders`.`id_customer` = ? GROUP BY `ps_cart_product`.`id_cart`", [$customer_id]);

	$result['orders'] = $orders;



	print(json_encode($result, JSON_NUMERIC_CHECK));