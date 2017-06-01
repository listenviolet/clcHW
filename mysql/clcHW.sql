create database if not exists clcHW 
	default character set utf8
	default collate utf8_general_ci;

use clcHW;

drop table stu_hw;
drop table stu_class;
drop table homework;
drop table class;
drop table prof;
drop table student;

create table if not exists prof(
	id int primary key AUTO_INCREMENT,
	email varchar(50) unique not null,
	name  varchar(50) not null,
	password char(60) not null,
	active int default 1
);

create table if not exists class(
	id int primary key AUTO_INCREMENT,
	name varchar(50) not null,
	prof_id int not null,
	csv varchar(200) not null,
	active int default 1,
	foreign key (prof_id) references prof(id)
);

create table if not exists homework(
	id int primary key AUTO_INCREMENT,
	class_id int not null,
	xml varchar(200) not null,
	active int default 1,
	foreign key (class_id) references class(id)
);

create table if not exists student(
	id int primary key AUTO_INCREMENT,
	email varchar(50) unique not null,
	name  varchar(50) not null,
	password char(60) not null,
	active int default 1
);

create table if not exists stu_class(
	id int primary key AUTO_INCREMENT,
	class_id int not null,
	stu_id int not null,
	active int default 1,
	foreign key (class_id) references class(id),
	foreign key (stu_id) references student(id)
);

create table if not exists stu_hw(
	id char(15) primary key,
	stu_id int not null,
	hw_id  int not null,
	hw_path varchar(100) not null,
	active int default 1,
	foreign key (stu_id) references student(id),
	foreign key (hw_id) references homework(id)
);

create user if not exists admin;
grant all on clcHW to 'admin'@'localhost' identified by '123';
grant all on clcHW.class to 'admin'@'localhost' identified by '123';
grant all on clcHW.prof to 'admin'@'localhost' identified by '123';
grant all on clcHW.student to 'admin'@'localhost' identified by '123';
grant all on clcHW.homework to 'admin'@'localhost' identified by '123';
grant all on clcHW.stu_class to 'admin'@'localhost' identified by '123';
grant all on clcHW.stu_hw to 'admin'@'localhost' identified by '123';
