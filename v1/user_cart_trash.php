<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$cart_id = $_GET['cart_id'] ?? 0;
	$product_id = $_GET['product_id'] ?? 0;
	$product_attribute_id = $_GET['product_attribute_id'] ?? 0;

	$code = R::exec("DELETE FROM `ps_cart_product` WHERE `ps_cart_product`.`id_cart` = ? AND `ps_cart_product`.`id_product` = ? AND `ps_cart_product`.`id_product_attribute` = ?", [$cart_id , $product_id, $product_attribute_id]);
	$result = array('code' => $code);

	print(json_encode($result, JSON_NUMERIC_CHECK));