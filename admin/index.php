<?php
	
	require '../connect.php';

	header("Content-type:application/json");

	$orders = R::getAll("SELECT `ps_orders`.`id_order`, `ps_orders`.`reference`, `ps_orders`.`note`,`ps_orders`.`payment`, `ps_orders`.`total_paid`, `ps_orders`.`current_state`, `ps_address`.`address1` , `ps_address`.`phone`, `ps_orders`.`date_add`, `ps_address`.`phone_mobile` ,`ps_orders`.`delivery_number` as 'delivery_price' FROM `ps_orders` LEFT JOIN `ps_address` ON `ps_address`.`id_address` = `ps_orders`.`id_address_delivery` WHERE `ps_orders`.`note` != '' ORDER BY `id_order` DESC LIMIT 0 , 50");

	for ($i=0; $i < count($orders); $i++) { 
		if(count($orders[$i]['phone_mobile']) < 5){
			$orders[$i]['phone_mobile'] = str_replace("+993", "", $orders[$i]['phone_mobile']);
			$orders[$i]['phone_mobile'] = str_replace("993", "", $orders[$i]['phone_mobile']);
			$orders[$i]['phone'] = $orders[$i]['phone_mobile'];
		}
	}

	$prices = R::getAll("SELECT * FROM `admin_data` LIMIT 0, 1");
	$result = array('code' => 1, 'orders' => $orders, 'prices' => $prices[0]);
	print(json_encode($result ));
	require 'web.php';