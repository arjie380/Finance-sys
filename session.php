<?php 

	include('includes/config.php');

	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	session_start();

	$check = $_SESSION['login_username'];

	if(!isset($check)) {
	    header("Location:index.php");
	}

?>