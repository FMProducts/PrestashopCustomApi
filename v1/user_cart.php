<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$customer_id = $_GET['customer_id'] ?? 0;

	$cart = R::getAll("SELECT * FROM `ps_cart` WHERE `ps_cart`.`id_customer` = ? ORDER BY `ps_cart`.`id_cart` DESC LIMIT 0,1", [$customer_id]);

	$result = array('code' => 1);
	if (count($cart) > 0) {
		$result['products'] = [];
		$cart_id = $cart[0]['id_cart'];
		$products = R::getAll("SELECT `ps_cart_product`.`quantity`,`ps_product`.`id_product` as 'id', `ps_product`.`reference` as 'vendorCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image', `ps_cart_product`.`id_product_attribute` FROM `ps_cart_product` LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_cart_product`.`id_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE `id_cart` = ? AND `ps_image`.`cover` = 1 AND `ps_product_lang`.`id_lang` = ?", [$cart_id , $lang]);

		foreach ($products as $product) {
			$attributes = R::getAll("SELECT `ps_attribute_group_lang`.`name` as 'group_name', `ps_attribute_lang`.`name` as 'name' FROM `ps_product_attribute_combination` LEFT JOIN `ps_attribute_lang` ON `ps_attribute_lang`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` LEFT JOIN `ps_attribute` ON `ps_attribute`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` LEFT JOIN `ps_attribute_group_lang` ON `ps_attribute_group_lang`.`id_attribute_group`= `ps_attribute`.`id_attribute_group` AND `ps_attribute_group_lang`.`id_lang` = `ps_attribute_lang`.`id_lang` WHERE `ps_product_attribute_combination`.`id_product_attribute` = ? AND `ps_attribute_lang`.`id_lang` = ?", [$product['id_product_attribute'], $lang]);
			$product['attributes'] = $attributes;
			array_push($result['products'], $product);
		}

		$result['cart_id'] = $cart_id;
	}


	print(json_encode($result, JSON_NUMERIC_CHECK));