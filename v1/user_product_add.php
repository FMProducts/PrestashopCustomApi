<?php
	require_once '../connect.php';

	$result = array('code' => 0);
	if($_SERVER['REQUEST_METHOD'] == "GET"){

		$lang = $_GET['language'] ?? 1; // 1 == ru

		$cart_id = $_GET['cart_id'] ?? 0;
		$product_id = $_GET['product_id'] ?? 0;
		$quantity = $_GET['quantity'] ?? 0;

		$result['code'] = 1;

	}
	else{
		$result['code'] = 405;
		$result['message'] = "require GET request";
	
	}
	print(json_encode($result, JSON_NUMERIC_CHECK));
