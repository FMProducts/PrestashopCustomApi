<?php
	require_once '../connect.php';

	$lang = $_GET['language'] ?? 1; // 1 == ru
	
	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		$result = array('code' => 1, 'groups' => []);

		$result['brands'] = R::getAll("SELECT `ps_manufacturer`.`id_manufacturer` as 'id', `ps_manufacturer`.`name` FROM `ps_manufacturer` WHERE `ps_manufacturer`.`active` = 1");

		$groups = R::getAll("SELECT `ps_attribute_group`.`id_attribute_group` as 'id', `ps_attribute_group_lang`.`name` FROM `ps_attribute_group` LEFT JOIN `ps_attribute_group_lang` ON `ps_attribute_group_lang`.`id_attribute_group` = `ps_attribute_group`.`id_attribute_group` WHERE `ps_attribute_group_lang`.`id_lang` = ?", [$lang]);

		foreach ($groups as $group) {
			$group['attributes'] = R::getAll("SELECT `ps_attribute`.`id_attribute` as 'id',`ps_attribute_lang`.`name` FROM `ps_attribute` LEFT JOIN `ps_attribute_lang` ON `ps_attribute_lang`.`id_attribute` = `ps_attribute`.`id_attribute` WHERE `ps_attribute_lang`.`id_lang` = ? AND `ps_attribute`.`id_attribute_group` = ?", [$lang, $group['id']]);
			array_push($result['groups'], $group);
		}

		$json = json_encode($result, JSON_NUMERIC_CHECK);
		print($json);
	}
	else{
		print("poshel nahuy");
	}