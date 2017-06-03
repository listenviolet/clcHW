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
		$classid=$_GET["classid"];
		$code=$_GET["code"];
		checkCode($code);
	}
	else {
		$url="../pages/index.html";
		echo "<script type='text/javascript'>";
		echo "alert('Please login first.');";
		echo "window.location.href='$url'";
		echo "</script>";
	}

	function checkCode($code){
		$check_result=array();
		$student_id="";
		$student_email="";
		$hw_path="";
		$query="select student.id,student.email,stu_hw.hw_path from student,stu_hw where stu_hw.id='".$code."' and student.id=stu_hw.stu_id";
		$result=$GLOBALS['db']->query($query);
		if(mysqli_num_rows($result)==1){
			$obj=mysqli_fetch_object($result);
			$student_id=$obj->id;
			$student_email=$obj->email;
			$hw_path=$obj->hw_path;
			$flag=1;
		}
		else $flag=0;
		$check_result[]=["student_id"=>$student_id,"student_email"=>$student_email,"hw_path"=>$hw_path,"flag"=>$flag];
		echo json_encode($check_result);
	}
?>