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
		$userid=$_SESSION['userid'];
		$class_id=$_GET["class_id"];
		getStuList($class_id);
	}

	/*
	$class_id=1;
	getStuList($class_id);
	*/
	function getStuList($class_id){
		$db=$GLOBALS['db'];
		$stu_list=array();
		$query_stu_class="select stu_id from stu_class where class_id=".$class_id."  and active=1";
		$result_stu_class=$db->query($query_stu_class);
		if(mysqli_num_rows($result_stu_class)){
			while($row=mysqli_fetch_assoc($result_stu_class)){
				$stu_id=$row["stu_id"];
				$query_stu="select name,email from student where id=".$stu_id." and active=1";
				$result_stu=$db->query($query_stu);
				if(mysqli_num_rows($result_stu)){
					$obj=mysqli_fetch_object($result_stu);
					$stu_email=$obj->email;
					$stu_name=$obj->name;
					//echo $stu_name;
					$stu_list[]=["id"=>$stu_id,"email"=>$stu_email,"name"=>$stu_name];
				}
			}
		}
		echo json_encode($stu_list);
	}

?>
	