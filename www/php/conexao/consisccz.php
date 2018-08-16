<?php
header("Content-Type: text/html; charset=utf-8");


$db_host = "10.1.20.29";
$db_name = "sisccz_bd";
$db_user = "cczadm";
$db_pass = "5alvaRespeit0";

	try{
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db_con->query("SET NAMES 'utf8'");
		$db_con->query("SET character_set_connection=utf8");
		$db_con->query("SET character_set_client=utf8");
		$db_con->query("SET character_set_results=utf8");		
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
?>