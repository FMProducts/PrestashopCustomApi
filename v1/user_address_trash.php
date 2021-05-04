<?php
	require_once '../connect.php';

	$result = array('code' => 0);

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$address_id = $_POST['address_id'] ?? 0;

		$code = R::exec("DELETE FROM `ps_address` WHERE `ps_address`.`id_address` = ?", [$address_id]);
		$result['code'] = $code;
	}
	print(json_encode($result, JSON_NUMERIC_CHECK));