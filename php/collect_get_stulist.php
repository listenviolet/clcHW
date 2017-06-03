<?php
	header("Content-type: text/javascript");
	session_start(); 
	require_once 'conn_db.php';
	function isLoggedIn(){
		if(isset($_SESSION['userid'])){
			return ture;
		}
		else{
			return false;
		}
	}
	
	if(isLoggedIn()){
		$classid=$_GET["classid"];
		$hwid=$_GET["hwid"];
		getStuList($classid,$hwid);
	}

/*
	$classid=1;
	$hwid=2;
	getStuList($classid,$hwid);
*/
	function getStuList($classid,$hwid){
		$stu_list=array();
		$db=$GLOBALS['db'];
		$query_stu_class="select student.id,student.name,student.email,stu_hw.hw_path from stu_class,student,stu_hw where stu_class.class_id=".$classid." and stu_class.active=1 and stu_class.stu_id=student.id and student.active=1 and stu_hw.stu_id=student.id and stu_hw.hw_id=".$hwid." and stu_hw.active=1";
		$result_stu_class=$db->query($query_stu_class);
		
		if(mysqli_num_rows($result_stu_class)){
			while($row=mysqli_fetch_assoc($result_stu_class)){
				$stu_id=$row["id"];
				$stu_email=$row["email"];
				$stu_name=$row["name"];
				$hw_path=$row["hw_path"];
				$stu_list[]=["stu_id"=>$stu_id,"email"=>$stu_email,"name"=>$stu_name,"hwpath"=>$hw_path];
			}
		}
		echo json_encode($stu_list);
		//print_r($stu_list);
	}
?>
	