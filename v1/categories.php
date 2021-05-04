<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru

	$parent = $_GET['parent'] ?? 2;

	$categories = R::getAll("SELECT `ps_category`.`id_category` as 'id' , `ps_category_lang`.`name` as 'name', `ps_category`.`position`, `ps_category`.`id_parent` as 'parentId',(SELECT COUNT(*) FROM `ps_category` as `cat` WHERE `cat`.`id_parent` = `ps_category`.`id_category`) as 'childCount' FROM `ps_category` LEFT JOIN `ps_category_lang` ON `ps_category_lang`.`id_category` = `ps_category`.`id_category` WHERE `ps_category`.`id_parent` = ? AND `ps_category_lang`.`id_lang` = ?", [$parent , $lang]);

	$result = array('code' => 1, 'categories' => $categories);

	print(json_encode($result, JSON_NUMERIC_CHECK));
