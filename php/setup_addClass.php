<?php
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
		$classname=$_POST["classname"];
		$class_csv="csv";
		if(isset($classname)){
			$class_csv_addr=upload_csv($class_csv);
			if($class_csv_addr!=false){
				$class_id=insertClass($classname,$userid,$class_csv_addr);
				insertStuClass($class_id,$class_csv_addr);
			}
		}
	}

	function insertClass($name,$prof_id,$csv){
		$db=$GLOBALS['db'];
		$query_insert_class="insert into class (name,prof_id,csv) values ('".$name."',".$prof_id.",'".$csv."')";
		$result_insert_class=$db->query($query_insert_class);
		$class_id=mysqli_insert_id($db);
		return $class_id;
	}

	function insertStuClass($class_id,$csv){
		$db=$GLOBALS['db'];
		$fileHandle = fopen("$csv","r");
		while (($row = fgetcsv($fileHandle,0,","))!=FALSE){
			$stu_email=$row[0];
			$stu_name=$row[1];
			$query_stu_id="select id from student where email='".$stu_email."' and name='".$stu_name."'";
			$result_stu_id=$db->query($query_stu_id);
			if(mysqli_num_rows($result_stu_id)==1){
				$stu_id=$result_stu_id->fetch_object()->id;
				$query_stu_class="insert into stu_class (class_id,stu_id) values (".$class_id.",".$stu_id.")";
				$result_stu_class=$db->query($query_stu_class);
			}
			else{
				die("Contains wrong information in csv file.");
			} 
		}

		$url="../pages/setup_classes.php";
		echo "<script type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
	}

	/*
	**Check and upload file
	*/
	function upload_csv($class_csv){
		$ext = end(explode(".", $_FILES[$class_csv]["name"]));
		$target_dir="../csv/";
		$name=md5(rand());  //To avoid the name confliction, use md5 to rename the csv file
		$target_file=$target_dir . $name.".".$ext;
		$uploadOk=1;
		$fileType=pathinfo($target_file,PATHINFO_EXTENSION);
		
		if(file_exists($target_file)){
			$uploadOk=0;
		}else if($_FILES[$class_csv]["size"]>10000){
			$uploadOk=0;
		}else if($fileType!="csv"){
			$uploadOk=0;
		}

		if($uploadOk==0){
			return false;
		}
		else{
			if(move_uploaded_file($_FILES[$class_csv]["tmp_name"], $target_file)){
				return $target_file;
			}
			else {
				return false;
			}
		}
	}


?>