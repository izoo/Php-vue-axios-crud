<?php

session_start();

$DB_host = "localhost";
$DB_user = "phpmyadmin";
$DB_pass = "chronixx";
$DB_name = "vuedb";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}
include_once 'system_class.php';
$user = new USER($DB_con);