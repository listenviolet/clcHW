$(document).ready(function(){
	var stu_classes=document.getElementById("stu_classes");
	function pageLoad(){
		getStuClasses(processStuClasses,errStuClasses);
	}

	function getStuClasses(processStuClasses,errStuClasses){
		$.ajax({
			type:"GET",
			url:"../php/stu_getClasses.php",
			success:function(data){
				processStuClasses(data);
			},
			error:function(data){
				errStuClasses(data);
			}
		});
	}

	//Get the classes information
	function processStuClasses(data){
		var stuClasses=JSON.parse(data);
		console.log("success ajax");
		$.each(stuClasses,function(index,classinfo){
			showStuClass(index,classinfo);
		});
	}

	function errStuClasses(data){
		alert("Error ajax.");
	}

	//Show the classes list and choose a class to submit the homework
	function showStuClass(index,classinfo){
		var class_id=classinfo.class_id;
		var class_name=classinfo.class_name;
		console.log("class_id:"+class_id);
		$("#stu_classes").append("<form class='container' id='stu_class"+class_id+"' action='../pages/stu_uploadhw.php' method='post' enctype='multipart/form-data'></form>");
		$("#stu_class"+class_id).append("<label for='input"+class_id+"'>Class Name: </label>");
		$("#stu_class"+class_id).append("<input type='text' id='input"+class_id+"' name='class_name' value='"+class_name+"' readOnly>");
		$("#stu_class"+class_id).append("<input type='hidden' id='classid"+class_id+"' name='classid' value='"+class_id+"'>");
		$("#stu_class"+class_id).append("<input class='btn btn-success' type='submit' id='enterclass"+class_id+"' name='enterclass"+class_id+"' value='View Homeworks'>");
		$("#stu_class"+class_id).append("<hr>");
	}

	pageLoad();
});