<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$product_id = $_GET['product_id'] ?? 0;

	$result = array('code' => 0);
	$products = R::getAll("SELECT `ps_product`.`id_product` as 'id', `ps_product`.`reference` as 'vendorCode', `ps_product_lang`.`name` as 'title', `ps_product`.`price` FROM `ps_product` LEFT JOIN `ps_product_lang` ON `ps_product_lang`.`id_product` = `ps_product`.`id_product` WHERE `ps_product_lang`.`id_lang` = ? AND `ps_product`.`id_product` = ?", [$lang, $product_id]);
	if (count($products) > 0) {
		$product = $products[0];
		$result['code'] = 1;
		$result['product'] = $product;
		
		// get images
		$images = R::getAll("SELECT * FROM `ps_image` WHERE `ps_image`.`id_product` = ? ORDER BY `position`", [$product['id']]);
		$result['images'] = $images;

		// get groups
		$groups = R::getAll("SELECT `ps_attribute_group_lang`.`id_attribute_group`, `ps_attribute_group_lang`.`name` FROM `ps_product_attribute` LEFT JOIN `ps_product_attribute_combination` ON `ps_product_attribute_combination`.`id_product_attribute` = `ps_product_attribute`.`id_product_attribute` LEFT JOIN `ps_attribute` ON `ps_attribute`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` LEFT JOIN `ps_attribute_group_lang` ON `ps_attribute_group_lang`.`id_attribute_group` = `ps_attribute`.`id_attribute_group` WHERE `ps_product_attribute`.`id_product` = ? AND `ps_attribute_group_lang`.`id_lang` = ? GROUP BY `ps_attribute_group_lang`.`id_attribute_group`", [$product['id'], $lang]);
		$result['groups'] = [];
		foreach ($groups as $group) {
			$group = array('id' => $group['id_attribute_group'] , 'name' => $group['name']);
			// get attributes
			$group['attributes'] = R::getAll("SELECT `ps_attribute_lang`.`id_attribute` as 'id', `ps_attribute_lang`.`name` FROM `ps_product_attribute` LEFT JOIN `ps_product_attribute_combination` ON `ps_product_attribute_combination`.`id_product_attribute` = `ps_product_attribute`.`id_product_attribute` LEFT JOIN `ps_attribute` ON `ps_attribute`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` LEFT JOIN `ps_attribute_lang` ON `ps_attribute_lang`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` WHERE `ps_product_attribute`.`id_product` = ? AND `ps_attribute_lang`.`id_lang` = ? AND `ps_attribute`.`id_attribute_group` = ?  GROUP BY `ps_product_attribute_combination`.`id_attribute`", [$product_id, $lang, $group['id']]);
			array_push($result['groups'], $group);
		}

		$feature = R::getAll("SELECT `ps_feature_lang`.`name`, `ps_feature_value_lang`.`value` as 'value' FROM `ps_feature_product` LEFT JOIN `ps_feature_lang` ON `ps_feature_lang`.`id_feature` = `ps_feature_product`.`id_feature` LEFT JOIN `ps_feature_value_lang` ON `ps_feature_value_lang`.`id_feature_value` = `ps_feature_product`.`id_feature_value` WHERE `ps_feature_product`.`id_product` = ? AND `ps_feature_lang`.`id_lang` = ? AND `ps_feature_value_lang`.`id_lang` = ?", [$product['id'], $lang, $lang]);
		$result['feature'] = $feature;

	}

	print(json_encode($result, JSON_NUMERIC_CHECK));