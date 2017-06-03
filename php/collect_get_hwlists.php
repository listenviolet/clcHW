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
		$username=$_SESSION['username'];
		$classid=$_GET["classid"];
		getHW($classid);
	}
	else {
		$url="../pages/index.html";
		echo "<script type='text/javascript'>";
		echo "alert('Please login first.');";
		echo "window.location.href='$url'";
		echo "</script>";
	}

	/*
	$classid=1;
	getHW($classid);
	*/
	function getHW($classid){
		$db=$GLOBALS['db'];
		$hw_array=array();
		$currenttime=date("Y-m-d");
		$query_hw="select id,name,starttime,deadline from homework where class_id=".$classid." and deadline<'".$currenttime."' and active=1";
		$result_hw=$db->query($query_hw);
		if(mysqli_num_rows($result_hw)>0){
			while($row=mysqli_fetch_assoc($result_hw)){
				$hw_id=$row["id"];
				$hw_name=$row["name"];
				$hw_starttime=$row["starttime"];
				$hw_deadline=$row["deadline"];
				$hw_array[]=["hw_id"=>$hw_id,"hw_name"=>$hw_name,"hw_starttime"=>$hw_starttime,"hw_deadline"=>$hw_deadline];
			}
		}
		else {
			die("Fail to select from homework.");
		}
		echo json_encode($hw_array);
	}
?>