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
		$userid=$_SESSION['userid'];
		getClasses($userid);
	}

	function getClasses($userid){
		$db=$GLOBALS['db'];
		$classes_info=array();
		$query_stu_class="select class.id,class.name from stu_class,class where stu_class.stu_id=".$userid." and stu_class.active=1 and class.id=stu_class.class_id";
		$result_stu_class=$db->query($query_stu_class);
		if(mysqli_num_rows($result_stu_class)){
			while($row=mysqli_fetch_assoc($result_stu_class)){
				$class_id=$row["id"];
				$class_name=$row["name"];
				$classes_info[]=["class_id"=>$class_id,"class_name"=>$class_name];
			}
		}
		
		echo json_encode($classes_info);
	}
?>