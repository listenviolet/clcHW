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
		$stu_id=$_GET['stu_id'];
		deleteStu($class_id,$stu_id);
	}

	function deleteStu($class_id,$stu_id){
		$db=$GLOBALS['db'];
		$flag=1;
		$query_stu_class="update stu_class set active=0 where class_id=".$class_id." and stu_id=".$stu_id;
		$result_stu_class=$db->query($query_stu_class);
		if(!$result_stu_class){
			$flag=0;
		}
		echo json_encode($flag);
	}

?>
	