<?php 
	define("DB_SERVER", "localhost");
	define("DB_USERNAME", "admin");   
	define("DB_PASSWORD", "123");
	define("DB_DATABASE", "clcHW");

	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	if (!$db)
	{
		echo DB_SERVER;
		die('Could not connect: ' . mysqli_connect_error());

	}
?>