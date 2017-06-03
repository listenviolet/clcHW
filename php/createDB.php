<?php
	$servername="localhost";
	$username=$_POST["username"];
	$password=$_POST["password"];
	$success=1;

	//Create connection
	$conn = new mysqli($servername,$username,$password);
	//Check connection
	if($conn->connect_error){
		$success=0;
		die("Connection failed: " . $conn->connect_error);
	}

	//Create database
	$sql_createDB = "create database if not exists clcHW";
	if($conn->query($sql_createDB) == TRUE){
		echo "Database created successfully"."<br>";
	}else{
		echo "Error creating database: " . $conn->error."<br>";
		$success=0;
	}

	$sql_useDB="use clcHW";
	if($conn->query($sql_useDB)==TRUE){
		echo "Use DB successfully.<br>";
	}else{
		$success=0;
		echo "Error use DB: ". $conn->error."<br>";
	}

	//Create table prof
	$sql_table_prof="create table if not exists prof(
		id int primary key AUTO_INCREMENT,
		email varchar(50) unique not null,
		name  varchar(50) not null,
		password char(60) not null,
		active int default 1
	)";
	if($conn->query($sql_table_prof)==TRUE){
		echo "Table prof created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table prof: ".$conn->error."<br>";
	}

	//Create table class
	$sql_table_class="create table if not exists class(
		id int primary key AUTO_INCREMENT,
		name varchar(50) not null,
		prof_id int not null,
		csv varchar(200) not null,
		active int default 1,
		foreign key (prof_id) references prof(id)
	)";
	if($conn->query($sql_table_class)==TRUE){
		echo "Table class created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table class: ".$conn->error."<br>";
	}

	//Create table homework
	$sql_table_homework="create table if not exists homework(
		id int primary key AUTO_INCREMENT,
		name varchar(50) not null,
		starttime date not null,
		deadline date not null,
		class_id int not null,
		xml varchar(200) not null,
		hw_path varchar(200) not null,
		active int default 1,
		foreign key (class_id) references class(id)
	)";
	if($conn->query($sql_table_homework)==TRUE){
		echo "Table homework created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table homework: ".$conn->error."<br>";
	}

	//Create table student
	$sql_table_student="create table if not exists student(
		id int primary key AUTO_INCREMENT,
		email varchar(50) unique not null,
		name  varchar(50) not null,
		password char(60) not null,
		active int default 1
	)";
	if($conn->query($sql_table_student)==TRUE){
		echo "Table student created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table student: ".$conn->error."<br>";
	}

	//Create table stu_class
	$sql_table_stu_class="create table if not exists stu_class(
		id int primary key AUTO_INCREMENT,
		class_id int not null,
		stu_id int not null,
		active int default 1,
		foreign key (class_id) references class(id),
		foreign key (stu_id) references student(id)
	)";
	if($conn->query($sql_table_stu_class)==TRUE){
		echo "Table stu_class created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table stu_class: ".$conn->error."<br>";
	}

	//Create table stu_hw
	$sql_table_stu_hw="create table if not exists stu_hw(
		id char(15) primary key,
		stu_id int not null,
		hw_id  int not null,
		hw_path varchar(200) not null,
		active int default 1,
		foreign key (stu_id) references student(id),
		foreign key (hw_id) references homework(id)
	)";
	if($conn->query($sql_table_stu_hw)==TRUE){
		echo "Table stu_hw created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating table stu_hw: ".$conn->error."<br>";
	}

	$sql_drop_procedure="drop procedure if exists updateHW";
	if($conn->query($sql_drop_procedure)==TRUE){
		echo "Drop procedure updateHW successfully.<br>";
	}
	else {
		$success=0;
		echo "Error dropping procedure updateHW.<br>";
	}
	
	$sql_procedure_updateHW="create procedure updateHW(in id char(15),in stuID int,in hwID int,in hw_path varchar(200)) begin declare old_id char(15); declare finish int default 0; declare cur_id cursor for select id from stu_hw where stu_hw.stu_id=stuID and stu_hw.hw_id=hwID and active=1; declare continue handler for not found set finish = 1; open cur_id; update_hw: loop fetch cur_id into old_id; if finish=1 then leave update_hw; end if; update stu_hw set active=0 where id=old_id; end loop update_hw; close cur_id; insert into stu_hw(id,stu_id,hw_id,hw_path) values(id,stuID,hwID,hw_path); end;";
	if($conn->query($sql_procedure_updateHW)==TRUE){
		echo "Procedure updateHW created successfully.<br>";
	}else {
		$success=0;
		echo "Error creating procedure updateHW: ".$conn->error."<br>";
	}

	if($success==1){
		//Create user
		$sql_create_user="create user if not exists admin";
		if($conn->query($sql_create_user)==TRUE){
			echo "User created successfully.<br>";
		}else {
			$success=0;
			echo "Error creating user: ".$conn->error."<br>";
		}

		//Grant priviledges
		$sql_grant_db="grant all on clcHW to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_db)==TRUE){
			echo "Grant DB priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting DB priviledges: ".$conn->error."<br>";
		}

		//Grant all on clcHW.prof
		$sql_grant_prof="grant all on clcHW.prof to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_prof)==TRUE){
			echo "Grant table prof priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table prof priviledges: ".$conn->error."<br>";
		}


		//Grant all on clcHW.class
		$sql_grant_class="grant all on clcHW.class to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_class)==TRUE){
			echo "Grant table class priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table class priviledges: ".$conn->error."<br>";
		}

		//Grant all on clcHW.homework
		$sql_grant_homework="grant all on clcHW.homework to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_homework)==TRUE){
			echo "Grant table homework priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table homework priviledges: ".$conn->error."<br>";
		}

		//Grant all on clcHW.student
		$sql_grant_student="grant all on clcHW.student to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_student)==TRUE){
			echo "Grant table student priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table student priviledges: ".$conn->error."<br>";
		}

		//Grant all on clcHW.stu_class
		$sql_grant_stu_class="grant all on clcHW.stu_class to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_stu_class)==TRUE){
			echo "Grant table stu_class priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table stu_class priviledges: ".$conn->error."<br>";
		}

		//Grant all on clcHW.stu_hw
		$sql_grant_stu_hw="grant all on clcHW.stu_hw to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_stu_hw)==TRUE){
			echo "Grant table stu_hw priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error granting table stu_hw priviledges: ".$conn->error."<br>";
		}

		//Grant procedure updateHW
		$sql_grant_updateHW="grant all on procedure clcHW.updateHW to 'admin'@'localhost' identified by '123'";
		if($conn->query($sql_grant_updateHW)==TRUE){
			echo "Grant procedure updateHW priviledges successfully.<br>";
		}else {
			$success=0;
			echo "Error procedure updateHW priviledges: ".$conn->error."<br>";
		}

		//Grant procedure updateHW
		$sql_grant_updateHW_flush="flush privileges";
		if($conn->query($sql_grant_updateHW_flush)==TRUE){
			echo "Flush successfully.<br>";
		}else {
			$success=0;
			echo "Error flushing: ".$conn->error."<br>";
		}
	}

	$conn->close;
?>