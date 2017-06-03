$(document).ready(function(){
	function pageLoad(){
		getClasses(processGetClasses,errProcess);
	}
	
	function getClasses(callback,errback){
		$.ajax({
			type:"GET",
			url:"../php/setupDB_hw.php",
			success:function(data){
				callback(data);
			},
			error:function(data){
				errback(data);
			}
		});
	}

	//Gets the classes information
	function processGetClasses(data){
		var classes=JSON.parse(data);
		console.log("processGetClasses");
		$.each(classes,function(index,classinfo){
			console.log("id: "+classinfo.class_id);
			console.log("name: "+classinfo.class_name);
			showClasses(index,classinfo);
		});
	}

	function errProcess(err){
		console.log("error ajax");
	}

	//Shows the classes information
	function showClasses(index,classinfo){
		var class_id=classinfo.class_id;
		var class_name=classinfo.class_name;
		$("#hw").append("<form class='container' id='setupHW"+class_id+"' action = '../pages/setup_hw_detail.php' method='post' enctype='multipart/form-data'></div>");
		$("#setupHW"+class_id).append("<label for='input"+class_id+"'>Class Name: </label>");
		$("#setupHW"+class_id).append("<input type='text' id='input"+class_id+"' name='input"+class_id+"' value='"+class_name+"' readOnly>");
		$("#setupHW"+class_id).append("<input type='hidden' id='classid"+class_id+"' name='classid' value='"+class_id+"'>");
		$("#setupHW"+class_id).append("<input class='btn btn-success' type='submit' id='submit"+class_id+"' name='submit"+class_id+"' value='Set Homework'>");
		$("#setupHW"+class_id).append("<hr>");
	}

	pageLoad();
});