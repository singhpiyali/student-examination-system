<?php
	//Connect to the database
	$host='localhost';
	$user='root';
	$password='';
	$dbname='examination_system';

	//Set Data Source Name (DSN)
	$dsn ='mysql:host='.$host.';dbname='.$dbname;

	try{
		//Create PDO Instance/Object
		$pdo = new PDO($dsn,$user,$password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
		//echo 'Connection Successful';
	}
	catch(Exception $e){
		echo 'Connection failed '.$e->getMessage();
	}
?>