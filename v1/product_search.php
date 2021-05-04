<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$keyword = $_GET['keyword'] ?? "";

	$products = R::getAll("SELECT `ps_product`.`id_product` as 'id', `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, 
		`ps_image`.`id_image` as 'image'FROM `ps_category_product`
		LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_category_product`.`id_product`
		LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` 
		LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product`
		WHERE `ps_image`.`cover` = 1 AND `ps_product_lang`.`name` LIKE ? GROUP BY `ps_product`.`id_product`", ["%".$keyword."%"]);

	$result = array('code' => 1, 'products' => $products);

	print(json_encode($result, JSON_NUMERIC_CHECK));