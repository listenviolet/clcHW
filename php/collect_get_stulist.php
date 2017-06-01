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
		$classid=$_GET["classid"];
		getStuList($csv_path,$classid,$dir_file);
	}

	function getCSV($classid){
		$query_class_csv="select class_csv from class where class_id=".$classid;
		$result_class_csv=$GLOBALS['db']->query($query_class_csv);
		if(mysqli_num_rows($result_class_csv)==1){
			while($row_csv=mysqli_fetch_assoc($result_class_csv)){
				$csv_path=$row_csv["class_csv"];
			}
			return $csv_path;
		}
		else return false;
	}

	function getStuList($csv_path,$classid,$dir_file){
		$stu_list=array();
		$fileHandle = fopen("$csv_path","r");
		while(($row = fgetcsv($fileHandle,0,","))!=FALSE){
			$stu_email=$row[0];
			$stu_name=$row[1];
			$stu_hwpath="../homework/".$classid."/".$dir_file."/".$stu_email."/";
			$stu_list[]=["email"=>$stu_email,"name"=>$stu_name,"hwpath"=>$stu_hwpath];
		}
		echo json_encode($stu_list);
	}

?>
	