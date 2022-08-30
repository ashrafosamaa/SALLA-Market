<?php
	require_once 'config.php';

	try {
		$mysql = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	} catch (Exception $error) {
		echo $error->getMessage();
	}