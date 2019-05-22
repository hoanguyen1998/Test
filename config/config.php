<?php 
ob_start(); //turn off output buffering
session_start();

$timezone = date_default_timezone_set("Europe/London");

$con = mysqli_connect("localhost:3306", "root", "meimei.9599", "social");

if(mysqli_connect_errno()) {
	echo "Failed to connect" . mysqli_connect_errno();
}
?>