<?php

	$webOrders = R::getAll("SELECT * FROM `ps_orders` WHERE `note` = '' OR `note` is NULL");


	foreach($webOrders as $order){
		$json = [];
		$data = R::getAll("SELECT `ps_attribute_lang`.`name` as 'attr', `ps_cart_product`.`quantity` as 'count', `ps_image`.`id_image` as 'cover' , `ps_product_lang`.`name`, `ps_product`.`price`, `ps_product_attribute`.`reference` as 'reference' FROM `ps_cart_product` LEFT JOIN `ps_product_lang` ON `ps_cart_product`.`id_product` = `ps_product_lang`.`id_product` LEFT JOIN `ps_image` ON `ps_image`.`id_product` = `ps_cart_product`.`id_product` LEFT JOIN `ps_product` ON `ps_product`.`id_product` = `ps_cart_product`.`id_product` LEFT JOIN `ps_product_attribute` ON `ps_product_attribute`.`id_product_attribute` = `ps_cart_product`.`id_product_attribute` LEFT JOIN `ps_product_attribute_combination` ON (`ps_product_attribute_combination`.`id_product_attribute` = `ps_cart_product`.`id_product_attribute`) LEFT JOIN `ps_attribute_lang` ON `ps_attribute_lang`.`id_attribute` = `ps_product_attribute_combination`.`id_attribute` WHERE `ps_cart_product`.`id_cart` = ? GROUP BY `ps_cart_product`.`id_product`" , [$order['id_cart']]);

		foreach ($data as $value) {
			$title = $value['count']."x - ".$value['reference']." - ".$value['attr'];
			$name = $value['name'];
			array_push($json, array('count' => $value['count'], 'cover' => $value['cover'], 'name' => $name , 'title' => $title, 'price' => $value['price']));
		}
		R::exec("UPDATE `ps_orders` SET `note` = ? , `delivery_number` = ? WHERE `ps_orders`.`id_order` = ?" , [json_encode($json) , $order['total_shipping'] ,$order['id_order']]);
	}
