<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$quantity = $_GET['quantity'] ?? 0;
	$cart_id = $_GET['cart_id'] ?? 0;
	$product_id = $_GET['product_id'] ?? 0;

	$code = R::exec("UPDATE `ps_cart_product` SET `quantity` = ? WHERE `ps_cart_product`.`id_cart` = ? AND `ps_cart_product`.`id_product` = ?" , [$quantity,$cart_id, $product_id]);
	$result = array('code' => $code);

	print(json_encode($result, JSON_NUMERIC_CHECK));