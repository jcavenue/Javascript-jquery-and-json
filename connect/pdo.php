<?php
	$dsn = 'mysql:host=localhost;dbname=misc';
	$username = 'wa4e';
	$pass = 'wa4e';

	try {
		$pdo = new PDO($dsn, $username, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
?>