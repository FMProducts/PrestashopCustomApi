<?php

	header('Content-Type: application/json');
	require 'rb.php';
	R::setup( 'mysql:host=localhost;dbname=database_name', 'username', 'password');	

?>
