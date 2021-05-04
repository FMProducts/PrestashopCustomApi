<?php
	require_once '../connect.php';

	if($_SERVER['REQUEST_METHOD'] == "GET"){

		$lang = $_GET['language'] ?? 1; // 1 == ru

		$customer_id = $_GET['customer_id'] ?? 0;

		$address = R::getAll("SELECT `ps_address`.`id_address` as 'id', `ps_address`.`address1`,`ps_address`.`address2`, `ps_address`.`phone`, `ps_address`.`phone_mobile`  FROM `ps_address` WHERE `ps_address`.`id_customer` = ?", [$customer_id]);

		$result = array('code' => 1, 'address' => $address);

		print(json_encode($result));

	}
	else{
		$customer_id = $_POST['customer_id'] ?? 0;
		$phone1 = $_POST['phone1'] ?? "";
		$phone2 = $_POST['phone2'] ?? "";
		$address1 = $_POST['address1'] ?? "";
		$address2 = $_POST['address2'] ?? "";
		$city = $_POST['city'] ?? "Ashgabat";

		$queryResult = R::exec("INSERT INTO `ps_address` (`id_address`, `id_country`, `id_state`, `id_customer`, `id_manufacturer`, `id_supplier`, `id_warehouse`, `alias`, `company`, `lastname`, `firstname`, `address1`, `address2`, `postcode`, `city`, `other`, `phone`, `phone_mobile`, `vat_number`, `dni`, `date_add`, `date_upd`, `active`, `deleted`) VALUES (NULL, '0', NULL, ?, '0', '0', '0', '', NULL, '', '', ?, ?, NULL, ?, NULL, ?, ?, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1', '0')", [$customer_id, $address1, $address2, $city , $phone1, $phone2 ]);

		$result = array('code' => 0);
		if ($queryResult == 1) {
			$address = R::getAll("SELECT `ps_address`.`id_address` as 'id', `ps_address`.`address1`,`ps_address`.`address2`, `ps_address`.`phone`, `ps_address`.`phone_mobile`  FROM `ps_address` WHERE `ps_address`.`id_customer` = ? ORDER BY `ps_address`.`id_address` LIMIT 0, 1" , [$customer_id]);
			$result['code'] = 1;
			foreach ($address as $value) {
				$result['address'] = $value;
			}
		}

		print(json_encode($result, JSON_NUMERIC_CHECK));
	}
