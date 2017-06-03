<?php 
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
		$username=$_SESSION["username"];
		$classid=$_GET["class_id"];
		$hwname=$_GET["hw_name"];
		$hwstarttime=$_GET["hw_starttime"];
		$hwdeadline=$_GET["hw_deadline"];

		$files_info=$_GET["files_info"];
		$filesarray=json_decode($files_info);

		$hw_id=insertHW($hwname,$hwstarttime,$hwdeadline,$classid);
		$xml_path=generateXML($username,$classid,$hw_id,$hwname,$hwstarttime,$hwdeadline,$filesarray);
		updatePathXML($hw_id,$xml_path,$classid);
	}

	$files_info=$_GET["files_info"];
	$filesarray=json_decode($files_info);

	function updatePathXML($hw_id,$xml_path,$classid){
		$db=$GLOBALS['db'];
		mkdir("../homework/".$classid,0777);
		$hw_path="../homework/".$classid."/".$hw_id."/";
		$query_updatePathXML="update homework set xml='".$xml_path."',hw_path='".$hw_path."' where id=".$hw_id." and active=1";
		echo $query_updatePathXML;
		$result_updatePathXML=$db->query($query_updatePathXML);
		if(!$result_updatePathXML){
			die("Error updating homeword path and XML path.");
		}
		
	}

	function insertHW($hwname,$hwstarttime,$hwdeadline,$classid){
		$db=$GLOBALS['db'];
		$query_insertHW="insert into homework(name,starttime,deadline,class_id) values ('".$hwname."','".$hwstarttime."','".$hwdeadline."',".$classid.")";
		echo $query_insertHW;
		$result_insertHW=$db->query($query_insertHW);

		if($result_insertHW){
			//$hw_id=mysqli_insert_id($db);
			$hw_id=$db->insert_id;
			return $hw_id;
		}
		else die("Error inserting into table homework.");
	}

	function generateXML($username,$classid,$hwid,$hwname,$hwstarttime,$hwdeadline,$filesarray){
		$hwnumber=str_replace("hw_id", "", $hwid);
		$numfile=count($filesarray);

		$domtree = new DOMDocument('1.0','UTF-8');
		$xmlRoot = $domtree->createElement("homework");
		$xmlRoot = $domtree->appendChild($xmlRoot);

		$hw_name = $domtree->createElement("hwname","$hwname");
		$hw_name = $xmlRoot->appendChild($hw_name);
		
		$hw_time = $domtree->createElement("hwtime");
		$hw_time = $xmlRoot->appendChild($hw_time);
		$hw_time->appendChild($domtree->createElement("hwstarttime","$hwstarttime"));
		$hw_time->appendChild($domtree->createElement("hwdeadline","$hwdeadline"));

		$files = $domtree->createElement("files");
		$files = $xmlRoot->appendChild($files);

		for($i=0;$i<$numfile;$i++){
			$fileid=$filesarray[$i][0];
			$file_type=$filesarray[$i][1];
			$file_size=$filesarray[$i][2];

			$filenumber=str_replace($hwnumber."file", "", $fileid);
			$filename="file".$filenumber;

			$file = $domtree->createElement($filename);
			$file = $files->appendChild($file);

			$file->appendChild($domtree->createElement("file-type","$file_type"));
			$file->appendChild($domtree->createElement("file-size","$file_size"));
		}
		mkdir("../xml/".$classid,0777);
		$xml_path="../xml/".$classid."/".$hwid.".xml";
		$domtree->save($xml_path);
		return $xml_path;
	}
?>