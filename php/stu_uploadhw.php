<?php
	session_start();
	require_once 'conn_db.php';
	require('../fpdf181/fpdf.php');

	function getClassName($hwclass){
		$db=$GLOBALS['db'];
		$query_select_class_name="select name from class where id=".$hwclass." and active=1";
		echo $query_select_class_name."<br>";
		$result_select_class_name=$db->query($query_select_class_name);
		if(mysqli_num_rows($result_select_class_name)==1){
			$classname=mysqli_fetch_object($result_select_class_name)->name;
			return $classname;
		}
	}
	
	function upload_hw($j,$hwclass,$hwid,$userid,$name,$classname){
		$ext = end(explode(".", $_FILES["upload".$j]["name"]));
		echo "upload_hw ext:".$ext."<br>";
		
		$target_dir="../homework/".$hwclass."/".$hwid."/".$userid."/".$name."/";
		$target_file=$target_dir.basename($_FILES["upload".$j]["name"]);
		//$target_file=$target_dir.$name.".".$ext;
		
		echo "target_file: ".$target_file."<br>";
		if(move_uploaded_file($_FILES["upload".$j]["tmp_name"], $target_file)){
			$GLOBALS['flag']=1;
			$userid=$_SESSION["userid"];
			echo "userid:".$userid."<br>";
		}
		else {
			$GLOBALS['flag']=0;
		}
		echo "flag:".$GLOBALS['flag']."<br>";
	}

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



	function generateCode(){
		$milliseconds = round(microtime(true) * 1000);
		$rand_fr=rand(0,9);
		$rand_se=rand(0,9);
		$code=$rand_fr.$rand_se.$milliseconds;
		echo "code:".$code;
		return $code;
	}

	function certificate($code,$userid,$hwid,$hw_path){
		$db=$GLOBALS['db'];
		//$query_stu_hw="insert into stu_hw(id,stu_id,hw_id,stu_hw) values ('".$code."',".$userid.",".$hwid.",'".$hw_path."')";
		$procedure_stu_hw="call clcHW.updateHW('".$code."',".$userid.",".$hwid.",'".$hw_path."')";
		echo "<br>".$procedure_stu_hw."<br>";
		$result_stu_hw=$db->query($procedure_stu_hw);
		$_SESSION["code"]=$code;
	}

	function isLoggedIn(){
		if(isset($_SESSION['userid'])){
			return ture;
		}
		else{
			return false;
		}
	}

	if(isLoggedIn()){
		$username=$_SESSION["username"];	
		$userid=$_SESSION["userid"];
		$hwclass=$_POST["hw_class"];
		$hwid=$_POST["hw_id"];
		$hwname=$_POST["hw_name"];
		$hwfilesnum=$_POST["hw_files_num"];
		$classname=getClassName($hwclass);

		$flag=1;
		echo $hwid."<br>";
		echo "hw_class:".$hwclass."<br>";
		echo "classname:".$classname."<br>";
		echo "filenum:".$hwfilesnum."<br>";
		if(!file_exists("../homework/".$hwclass."/")){
			mkdir("../homework/".$hwclass."/",0777);
		}
		if(!file_exists("../homework/".$hwclass."/".$hwid."/")){
			mkdir("../homework/".$hwclass."/".$hwid."/",0777);
		}
		if(!file_exists("../homework/".$hwclass."/".$hwid."/".$userid."/")){
			mkdir("../homework/".$hwclass."/".$hwid."/".$userid."/",0777);
		}

		$name=md5(rand());
		if(!file_exists("../homework/".$hwclass."/".$hwid."/".$userid."/".$name."/")){
			mkdir("../homework/".$hwclass."/".$hwid."/".$userid."/".$name."/",0777);
		}
		
		for($i=0;$i<$hwfilesnum;$i++){
			upload_hw($i,$hwclass,$hwid,$userid,$name,$classname);

		}

		if($flag==1){
			$code=generateCode();
			$target_dir="../homework/".$hwclass."/".$hwid."/".$userid."/".$name."/";
			certificate($code,$userid,$hwid,$target_dir);
		    $_SESSION["classname"]=$classname;
		    $_SESSION["hwname"]=$hwname;
		    $url="../pages/certification.php";
			echo "<script type='text/javascript'>";
			echo "window.location.href='$url'";
			echo "</script>";
		}
		
	}
?>