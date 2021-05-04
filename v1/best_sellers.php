<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru
	$brands_string = $_GET['brands'] ?? "[]";
	$attributes_string = $_GET['attributes'] ?? "[]";

	$offset = intval($_GET['offset'] ?? 0);
	$limit = intval($_GET['limit'] ?? 10);
	$brands = @json_decode($brands_string);
	$attributes = @json_decode($attributes_string);

	$base_query = "SELECT `ps_product`.`id_product` as 'id', `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image' FROM `ps_product_sale` LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_product_sale`.`id_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE";

	if ($brands) {
		$query = "( ";
		foreach ($brands as $value) {
			$query .= "`ps_product`.`id_manufacturer` = ".$value." OR";
		}
		$query = substr($query, 0, -2);
		$query .= " ) AND";
		$base_query .= $query;
	}

	if ($attributes) {
		foreach($attributes as $value){
			$base_query .= "`ps_product_attribute_combination`.`id_attribute` = ".$value." AND";
		}
	}

	$best_sellers = R::getAll($base_query." `ps_image`.`cover` = 1 AND `ps_product_lang`.`id_lang` = ? ORDER BY `ps_product_sale`.`sale_nbr` DESC LIMIT ?,?", [$lang, $offset, $limit]);

	$result = array('code' => 1, 'products' => $best_sellers);

	print(json_encode($result, JSON_NUMERIC_CHECK));
