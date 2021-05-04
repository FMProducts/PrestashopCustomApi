<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru
	$now = date('Y-m-d');
	$best_sellers = R::getAll("SELECT `ps_product`.`id_product` as 'id',`ps_product`.`on_sale`, `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image',(SELECT COUNT(p.`id_product`) FROM `ps_product` p WHERE p.`id_product` = `ps_product`.`id_product` AND DATEDIFF(p.`date_add`, DATE_SUB(?, INTERVAL 20 DAY)) > 0) as 'new' FROM `ps_product_sale` LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_product_sale`.`id_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE `ps_image`.`cover` = 1 AND `ps_product_lang`.`id_lang` = ? ORDER BY `ps_product_sale`.`sale_nbr` DESC LIMIT 5", [$now, $lang]);

	$last_products = R::getAll("SELECT `ps_product`.`id_product` as 'id',`ps_product`.`on_sale`, `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image',(SELECT COUNT(p.`id_product`) FROM `ps_product` p WHERE p.`id_product` = `ps_product`.`id_product` AND DATEDIFF(p.`date_add`, DATE_SUB(?, INTERVAL 20 DAY)) > 0) as 'new' FROM `ps_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE `ps_product_lang`.`id_lang` = ? AND `ps_image`.`cover` = 1 ORDER BY `ps_product`.`date_upd` DESC LIMIT 5", [$now , $lang]);

	$discount_products = R::getAll("SELECT `ps_product`.`id_product` as 'id',`ps_product`.`on_sale`, `ps_product`.`reference` as 'venderCode', `ps_product_lang`.`name` as 'title' , `ps_product`.`price`, `ps_image`.`id_image` as 'image',(SELECT COUNT(p.`id_product`) FROM `ps_product` p WHERE p.`id_product` = `ps_product`.`id_product` AND DATEDIFF(p.`date_add`, DATE_SUB(?, INTERVAL 20 DAY)) > 0) as 'new' FROM `ps_product` LEFT JOIN `ps_product_lang` ON `ps_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_product`.`id_product` = `ps_image`.`id_product` WHERE `ps_product_lang`.`id_lang` = ? AND `ps_image`.`cover` = 1 AND `ps_product`.`on_sale` = 1 ORDER BY `ps_product`.`date_upd` DESC LIMIT 5", [$now , $lang]);

	$banners = R::getAll("SELECT `ps_st_iosslider`.`id_st_iosslider` as 'id', `ps_st_iosslider`.`active`, `ps_st_iosslider_lang`.`url`, `ps_st_iosslider_lang`.`image_multi_lang` as 'filename', `ps_st_iosslider_lang`.`title` FROM `ps_st_iosslider` LEFT JOIN `ps_st_iosslider_lang` ON `ps_st_iosslider_lang`.`id_st_iosslider` = `ps_st_iosslider`.`id_st_iosslider` WHERE `ps_st_iosslider`.`id_st_iosslider_group` = 3 AND `ps_st_iosslider`.`active` = 1 AND `ps_st_iosslider_lang`.`id_lang` = 3 ORDER BY `ps_st_iosslider`.`position` ASC");


	$single_banners = R::getAll("SELECT `ps_st_iosslider`.`id_st_iosslider` as 'id' ,`ps_st_iosslider`.`position`, `ps_st_iosslider`.`active`, `ps_st_iosslider_lang`.`url`, `ps_st_iosslider_lang`.`image_multi_lang` as 'filename', `ps_st_iosslider_lang`.`title` FROM `ps_st_iosslider` LEFT JOIN `ps_st_iosslider_lang` ON `ps_st_iosslider_lang`.`id_st_iosslider` = `ps_st_iosslider`.`id_st_iosslider` WHERE `ps_st_iosslider`.`id_st_iosslider_group` = 4 AND `ps_st_iosslider`.`active` = 1 AND `ps_st_iosslider_lang`.`id_lang` = 3 ORDER BY `ps_st_iosslider`.`position` ASC");


	$brands = R::getAll("SELECT `ps_manufacturer`.`id_manufacturer` as 'id', `ps_manufacturer`.`name` FROM `ps_manufacturer` WHERE `ps_manufacturer`.`active` = 1 ORDER BY `ps_manufacturer`.`date_add` DESC");

	$result = array('code' => 1, 'best_sellers' => $best_sellers, 'last_products' => $last_products, 'discount_products' => $discount_products, 'banners' => $banners, 'single_banners' => $single_banners,'brands' => $brands, 'now' => $now);

	print(json_encode($result, JSON_NUMERIC_CHECK));
