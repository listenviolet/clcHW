<?php
	header("Content-type: text/javascript");
	$classid=$_GET["class_id"];
	require_once 'conn_db.php';
	$classinfo=array();
	$query_select_class_name="select name from class where id=".$classid;
	$result_select_class_name=$GLOBALS['db']->query($query_select_class_name);
	if(mysqli_num_rows($result_select_class_name)==1){
		$classname=mysqli_fetch_object($result_select_class_name)->name;
		$classinfo[]=["classname"=>$classname];
	}
	echo json_encode($classinfo);
?>