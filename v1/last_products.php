<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru
	$brands_string = $_GET['brands'] ?? "[]";
	$attributes_string = $_GET['attributes'] ?? "[]";
	$limit = intval($_GET['limit'] ?? 10);
	$offset = intval($_GET['offset'] ?? 0);
	$now = date('Y-m-d');
	$brands = @json_decode($brands_string);
	$attributes = @json_decode($attributes_string);
	$base_query = "SELECT `ps_product`.`id_product` as 'id',`ps_product`.`on_sale`, `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image',  (SELECT COUNT(p.`id_product`) FROM `ps_product` p WHERE p.`id_product` = `ps_product`.`id_product` AND DATEDIFF(p.`date_add`, DATE_SUB(?, INTERVAL 20 DAY)) > 0) as 'new' FROM `ps_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE ";
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

	$last_products = R::getAll($base_query."`ps_product_lang`.`id_lang` = ? AND `ps_image`.`cover` = 1 ORDER BY `ps_product`.`date_upd` DESC LIMIT ? , ?", [$now, $lang, $offset, $limit]);
	$result = array('code' => 1, 'products' => $last_products);

	print(json_encode($result, JSON_NUMERIC_CHECK));
