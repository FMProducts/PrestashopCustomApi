<?php

	require '../connect.php';
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$prices = R::getAll("SELECT * FROM `admin_data` LIMIT 0, 1");
		print(json_encode(array('code' => 1, 'prices' => $prices[0])));
	}
	else{
		$price_ashgabat = $_POST['ashgabat'];
		$price_other = $_POST['other'];
		R::exec("UPDATE `admin_data` SET `price_other` = ? ,`price_ashgabat` = ? WHERE `admin_data`.`id` = 1", [$price_other, $price_ashgabat ]);
		print(json_encode(array('code' => 1)));
	}