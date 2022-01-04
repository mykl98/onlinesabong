<?php
$whitelist = array('127.0.0.1', "::1");

/*
if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $servername = "localhost";
	$username = "u528264240_onlinesabong";
	$password = "Skooltech_113012";
	$dbname = "u528264240_onlinesabong";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$baseUrl = "https://raptorapps.xyz/onlinesabong";
}else{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "onlinesabong";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$baseUrl = "http://localhost/onlinesabong";
}
*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinesabong";
$conn = new mysqli($servername, $username, $password, $dbname);
//$baseUrl = "http://localhost/onlinesabong";
$baseUrl = "http://192.168.1.2/onlinesabong";

date_default_timezone_set("Asia/Manila");

function sanitize($input){
	global $conn;
	$output = mysqli_real_escape_string($conn, $input);
	return $output;
}

function saveLog($log){
	$logFile = fopen("log.txt", "a") or die("Unable to open file!");
	$timeStamp = date("Y-m-d") . '-' . date("h:i:sa");
	fwrite($logFile, $timeStamp .' Log: '. $log . "\n");
	fclose($logFile);
}

function systemLog($log,$account){
	global $conn;
	$date = date("Y-m-d");
	$time = date("h:m:ia");
	$table = "log";
	$sql = "INSERT INTO `$table` (date,time,log,account) VALUES ('$date','$time','$log','$account')";
	if(mysqli_query($conn,$sql)){
		$table = "super-log";
		$sql = "INSERT INTO `$table` (date,time,log,account) VALUES ('$date','$time','$log','$account')";
		if(mysqli_query($conn,$sql)){
			return "true";
		}else{
			return "false" . $conn->error;
		}
	}else{
		return "false" . $conn->error;
	}
}

function generateCode($length){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}