<?php
	require_once '../connect.php';

	$lang = $_POST['language'] ?? 1; // 1 == ru

	$email = $_POST['email'] ?? "";
	$firstname = $_POST['firstname'] ?? "unknown";
	$lastname = $_POST['lastname'] ?? "unknown";

	$queryResult = R::exec("INSERT INTO `ps_customer` (`id_customer`, `id_shop_group`, `id_shop`, `id_gender`, `id_default_group`, `id_lang`, `id_risk`, `company`, `siret`, `ape`, `firstname`, `lastname`, `email`, `passwd`, `last_passwd_gen`, `birthday`, `newsletter`, `ip_registration_newsletter`, `newsletter_date_add`, `optin`, `website`, `outstanding_allow_amount`, `show_public_prices`, `max_payment_days`, `secure_key`, `note`, `active`, `is_guest`, `deleted`, `date_add`, `date_upd`) VALUES (NULL, '1', '1', '1', '1', NULL, '1', NULL, NULL, NULL, ?, ?, ?, '', CURRENT_TIMESTAMP, NULL, '0', NULL, NULL, '0', NULL, '0.000000', '0', '60', '-1', NULL, '0', '0', '0', CURRENT_TIMESTAMP,CURRENT_TIMESTAMP )", [$firstname , $lastname, $email]);


	$result = array('code' => $queryResult); 
	if ($queryResult == 1) {
		$customers = R::getAll("SELECT `ps_customer`.`id_customer` as 'id', `ps_customer`.`firstname`, `ps_customer`.`lastname` FROM `ps_customer` WHERE `ps_customer`.`email` = ? AND `ps_customer`.`lastname` = ? AND `ps_customer`.`firstname` = ?", [$email, $lastname, $firstname]);

		foreach ($customers as $value) {
			R::exec("INSERT INTO `ps_cart` (`id_cart`, `id_shop_group`, `id_shop`, `id_carrier`, `delivery_option`, `id_lang`, `id_address_delivery`, `id_address_invoice`, `id_currency`, `id_customer`, `id_guest`, `secure_key`, `recyclable`, `gift`, `gift_message`, `mobile_theme`, `allow_seperated_package`, `date_add`, `date_upd`) VALUES (NULL, '1', '1', '1', '', '1', '0', '0', '1', ?, '1', '-1', '1', '0', NULL, '0', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", [$value['id']]);
			$result['code'] = 1;
			$result['customer'] = $value;
		}
	}

	print(json_encode($result, JSON_NUMERIC_CHECK));