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
		$userid=$_SESSION['userid'];
		getClasses($userid);
	}

	function getClasses($userid){
		$classes_info=array();
		$query_prof_select_existed_classes="select id,name,csv from class where prof_id=".$userid." and active=1";
		$result_prof_select_existed_classes = $GLOBALS['db']->query($query_prof_select_existed_classes);
		if(mysqli_num_rows($result_prof_select_existed_classes)){
			while($row_prof_classes=mysqli_fetch_assoc($result_prof_select_existed_classes)){
				$class_id=$row_prof_classes["id"];
				$class_name=$row_prof_classes["name"];
				$class_csv=$row_prof_classes["csv"];
				$classes_info[]=["class_id"=>$class_id,"class_name"=>$class_name,"class_csv"=>$csv_path];
			}
		}
		echo json_encode($classes_info);
	}
?>