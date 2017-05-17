$(document).ready(function(){
	var stu_classes=document.getElementById("stu_classes");
	function pageLoad(){
		getStuClasses(processStuClasses,errStuClasses);
	}

	function getStuClasses(callback,errback){
		$.ajax({
			type:"GET",
			url:"../php/stu_get_classes.php",
			success:function(data){
				callback(data);
			},
			error:function(data){
				errback(data);
			}
		});
	}

	function processStuClasses(data){
		var stuClasses=JSON.parse(data);
		console.log("success ajax");
		$.each(stuClasses,function(index,classinfo){
			console.log(index);
			console.log("class id: "+classinfo.class_id);
			console.log("class name: "+classinfo.class_name);
			showStuClass(index,classinfo);
		});
	}

	function errStuClasses(data){
		alert("Error ajax.");
	}

	function showStuClass(index,classinfo){
		var class_id=classinfo.class_id;
		var class_name=classinfo.class_name;
		$("#stu_classes").append("<form class='container' id='stu_class"+class_id+"' action='../pages/stu_uploadhws.php' method='post' enctype='multipart/form-data'></form>");
		$("#stu_class"+class_id).append("<label for='input"+class_id+"'>Class Name: </label>");
		$("#stu_class"+class_id).append("<input type='text' id='input"+class_id+"' name='class_name' value='"+class_name+"' readOnly>");
		$("#stu_class"+class_id).append("<input type='hidden' id='classid"+class_id+"' name='classid' value='"+class_id+"'>");
		$("#stu_class"+class_id).append("<input class='btn btn-success' type='submit' id='enterclass"+class_id+"' name='enterclass"+class_id+"' value='View Homeworks'>");
		$("#stu_class"+class_id).append("<hr>");
	}

	function turntoUploadHw(class_id){
		$.ajax({
			type:"GET",
			url:"../pages/stu_class_hw.php",
			data:{"classid":class_id},
			success:function(data){
				alert(data);
			},
			error:function(data){
				errback(data);
			}
		});
	}

	pageLoad();
});