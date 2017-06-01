<?php
	session_start();
	require_once 'conn_db.php';
	//Check and upload file
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

	//select the existed classes_id
	$username=$_SESSION["username"];
	$userid=$_SESSION["userid"];
	$query_prof_select_classesid="select classes_id from prof where prof_id=".$userid;
	$result_prof_select_classesid=$GLOBALS['db']->query($query_prof_select_classesid);
	if(mysqli_num_rows($result_prof_select_classesid)==1){
		$prof_existed_classes=$result_prof_select_classesid->fetch_object()->classes_id;
	}

	//Delete the classes that have been chosen
	$deletearray=explode(",", $_POST["deletearray"]);
	if($deletearray[0]!=NULL){
		$delete_num=count($deletearray);
		for($i=0;$i<$delete_num;$i++){
			//replace prof classes_id
			$existed_class=$deletearray[$i].",";
			$prof_existed_classes=str_replace("$existed_class", "", $prof_existed_classes);

			//select student existed classes_id--read csv file and delete stu class
			$query_select_class_csv="select class_csv from class where class_id=".$deletearray[$i];
			$result_select_class_csv=$GLOBALS['db']->query($query_select_class_csv);
			if(mysqli_num_rows($result_select_class_csv)==1){
				$class_csv_addr=$result_select_class_csv->fetch_object()->class_csv;
				$fileHandle = fopen("$class_csv_addr","r");
				while (($row = fgetcsv($fileHandle,0,","))!=FALSE){
					$stu_email=$row[0];
					$query_student_classes_id="select classes_id from student where email='".$stu_email."'";
					$result_student_classes_id=$GLOBALS['db']->query($query_student_classes_id);
					if(mysqli_num_rows($result_student_classes_id)==1){
						$student_classes_id=$result_student_classes_id->fetch_object()->classes_id;
						$student_classes_id=str_replace($existed_class, "", $student_classes_id);

						$query_update_stu_classes_id="update student set classes_id='".$student_classes_id."' where email='".$stu_email."'";
						$result_update_stu_classes_id=$GLOBALS['db']->query($query_update_stu_classes_id);
					}
				}
			}

			//set delete class active=0
			$query_class_set_active="update class set class_active=0 where class_id=".$deletearray[$i];
			$result_class_set_active=$GLOBALS['db']->query($query_class_set_active);
		}
		//update prof classes_id
		$query_prof_afdel_classes="update prof set classes_id='".$prof_existed_classes."' where prof_id=".$userid;
		$result_prof_afdel_classes=$GLOBALS['db']->query($query_prof_afdel_classes);
	}
	
	//Set classes.
	$classarray=explode(",",$_POST["classarray"]);
	if($classarray[0]!=NULL){
		$class_num=count($classarray);
		$classes=$prof_existed_classes;

		for($i=0;$i<$class_num;$i++){
			$classid=$classarray[$i];
			if($_POST[$classid]!=NULL){
				$class_csv=str_replace("class_id", "class_csv", $classid);
				$class_csv_addr=upload_csv($class_csv);
				if($class_csv_addr!=false){
					$query_insertclass="insert into class (class_name,class_csv) values ('".$_POST[$classid]."','".$class_csv_addr."')";
					$result_insertclass=$GLOBALS['db']->query($query_insertclass);
					$class_id=mysqli_insert_id($db);
					$classes=$classes.$class_id.",";
				}
				
				else {
					$url="../pages/setup_classes.php";
					echo "<script type='text/javascript'>";
					echo "window.location.href='$url'";
					echo "alert('The file must be under 10kb and only in csv format.');";
					echo "</script>";
					exit("Error to upload the csv file.");
				}
					
				////////////////////////
				
				$fileHandle = fopen("$class_csv_addr","r");
				while (($row = fgetcsv($fileHandle,0,","))!=FALSE){
					$stu_email=$row[0];
					$query_select_student="select * from student where email='".$stu_email."'";
					$result_select_student=$db->query($query_select_student);
					if(mysqli_num_rows($result_select_student)==1){
						while($row_stu=mysqli_fetch_assoc($result_select_student)){
							$stu_classes=$row_stu["classes_id"];
							if($stu_classes==NULL){ //classes is empty
								$stu_classes=$stu_classes.$class_id.",";
							}
							else{ //already has so check if this class exists; if not add
								$stu_classes_array=explode(",", $stu_classes);
								if(in_array($class_id, $stu_classes_array)){
									//echo "this class already exists";
									//echo '<br>';
								}
								else {
									//echo "this class doesn't exists";
									//echo '<br>';
									$stu_classes=$stu_classes.$class_id.",";
								}
							}

							$query_update_stuclasses="update student set classes_id='".$stu_classes."' where email='".$stu_email."'";
							//echo '<br>';
							//echo $query_update_stuclasses;
							$result_update_stuclasses=$db->query($query_update_stuclasses);
						}
					}
				}
			}
		}
		$query_addClasses="update prof set classes_id="."'".$classes."'"." where prof_id=".$userid;
		$result_addClasses=$db->query($query_addClasses);
	}

		$url="../pages/setup_hw.php";
		echo "<script type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
?>