<?php
	header("Content-type: text/javascript");
	session_start();
	require_once 'conn_db.php';
	function isLoggedIn(){
		if(isset($_SESSION['username'])){
			return ture;
		}
		else{
			return false;
		}
	}
	
	if(isLoggedIn()){
		$stu_classid=$_GET["stu_classid"];
		getHwInfo($stu_classid);
	}
	
/*
	$stu_classid=1;
	getHwInfo($stu_classid);
*/
	function getHwInfo($stu_classid){
		$db=$GLOBALS['db'];
		$hw_array=array();
		$currenttime=date("Y-m-d");
		$query_hwInfo="select id,name,starttime,deadline,xml,hw_path from homework where class_id=".$stu_classid." and starttime<='".$currenttime."' and deadline>='".$currenttime."' and active=1";
		//echo $query_hwInfo;
		$result_hwInfo=$db->query($query_hwInfo);
		if(mysqli_num_rows($result_hwInfo)){
			while($row=mysqli_fetch_assoc($result_hwInfo)){
				$hw_id=$row["id"];
				$hw_name=$row["name"];
				$hw_starttime=$row["starttime"];
				$hw_deadline=$row["deadline"];
				$hw_xml=$row["xml"];
				$hw_path=$row["hw_path"];
				$hw_array[]=["hw_id"=>$hw_id,"hw_name"=>$hw_name,"hw_starttime"=>$hw_starttime,"hw_deadline"=>$hw_deadline,"hw_xml"=>$hw_xml,"hw_path"=>$hw_path];
			}
		}
		echo json_encode($hw_array);
		//print_r($hw_array);
	}
?>