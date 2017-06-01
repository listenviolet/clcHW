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
		$class_id=$_GET["class_id"];
		$email=$_GET["email"];
		$name=$_GET["name"];
		addStuClass($class_id,$email,$name);
	}
	/*
	$class_id=1;
	$email="stu_3@gmail.com";
	$name="WANG WU";
	addStuClass($class_id,$email,$name);
	*/
	function addStuClass($class_id,$email,$name){
		$db=$GLOBALS['db'];
		$flag=1;
		$query_stu="select id from student where email='".$email."' and name='".$name."' and active=1";
		$result_stu=$db->query($query_stu);

		if(mysqli_num_rows($result_stu)==1){
			$obj=mysqli_fetch_object($result_stu);
			$stu_id=$obj->id;
			$query_stu_class="insert into stu_class (class_id,stu_id) values (".$class_id.",".$stu_id.")";
			$result_stu_class=$db->query($query_stu_class);
			if(!$result_stu_class){
				$flag=0;
			}
			
		}
		else{
			$flag=0;
		}
		echo json_encode($flag);
	}
?>
	