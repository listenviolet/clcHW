<?php 
	session_start();
	$name = $_GET["username"];
	$sql = "SELECT id FROM Users WHERE username='$name' limit 1";
	$result = mysql_query($sql);
	$value = mysql_fetch_object($result);
	$_SESSION['myid'] = $value->id;

?>