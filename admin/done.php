<?php
	
	require '../connect.php';
	$id = $_REQUEST['orderId'] ?? 0;
	$done = $_REQUEST['done'] ?? 0;

	R::exec("UPDATE `ps_orders` SET `current_state` = ? WHERE `ps_orders`.`id_order` = ?" , [$done , $id]);

	$result = array('code' => 1);
	print(json_encode($result));