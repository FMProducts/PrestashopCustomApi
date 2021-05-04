<?php
	require_once '../connect.php';

	$lang = $_POST['language'] ?? 1; // 1 == ru

	$email = $_POST['email'] ?? "";

	$customers = R::getAll("SELECT `ps_customer`.`id_customer` as 'id', `ps_customer`.`firstname`, `ps_customer`.`lastname` FROM `ps_customer` WHERE `ps_customer`.`email` = ?", [$email]);

	$result = array('code' => 0, 'customer' => null);

	foreach ($customers as $value) {
		$result['code'] = 1;
		$result['customer'] = $value;
	}

	print(json_encode($result, JSON_NUMERIC_CHECK));