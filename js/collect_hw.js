$(document).ready(function(){
	var input_classid=document.getElementById("classid"); //input element of the form, its value stored the class id
	var classid=input_classid.value;                      
	var hw_lists=document.getElementById("hw-lists");

	function pageLoad(){
		getClassName(classid);
		getHwLists(processGetHwLists,errGetHwLists);
	}

	//Get the name of the chosen class
	function getClassName(classid){
		$.ajax({
			type:"GET",
			data:{"class_id":classid},
			url:"../php/getClassName.php",
			success:function(data){
				var classinfo;
				classinfo=JSON.parse(data);
				var classname=classinfo[0].classname;
				$("#class_name").append("<h2>For Class  "+"<span style='color:#4169E1'>"+classname+"</span></h2>");
			},
			error:function(){
				alert("Error ajax!");
			}
		});
	}

	//To get the students information and the path of the homework
	function getHwLists(processGetHwLists,errGetHwLists){
		$.ajax({
			type:"GET",
			data:{"classid":classid},
			url:"../php/collect_get_hwlists.php",
			success:function(data){
				processGetHwLists(data);
			},
			error:function(data){
				errGetHwLists(data);
			}
		});
	}

	//Get the homework information of this class
	function processGetHwLists(data){
		var hw_array=JSON.parse(data);
		var hw_num=hw_array.length;
		$.each(hw_array,function(index,hwinfo){
			var hw_id=hwinfo.hw_id;
			var hw_name=hwinfo.hw_name;
			var hw_starttime=hwinfo.hw_starttime;
			var hw_deadline=hwinfo.hw_deadline;
			console.log("hw_id:"+hw_id);
			showHwInfo(hw_id,hw_name,hw_starttime,hw_deadline);
			getStuList(classid,hw_id,processGetStuList,errGetStuList);
		});
	}

	function getStuList(classid,hw_id,processGetStuList,errGetStuList){
		$.ajax({
			type:"GET",
			data:{"classid":classid,"hwid":hw_id},
			url:"../php/collect_get_stulist.php",
			success:function(data){
				processGetStuList(data,hw_id);
			},
			error:function(data){
				errGetStuList(data);
			}
		});
	}

	function showHwInfo(hw_id,hw_name,hw_starttime,hw_deadline){
		console.log("in showHwInfo hw_name:"+hw_name);
		console.log("in showHwInfo hw_id:"+hw_id);
		$("#hw-lists").append("<div id='card"+hw_id+"' class='card'></div>");
		$("#card"+hw_id).append("<div id='heading"+hw_id+"' class='card-header' role='tab'></div>");
		$("#card"+hw_id).append("<div id='collapse"+hw_id+"' class='collapse' role='tabpanel' aria-labelledby='heading"+hw_id+"'></div>");
		$("#heading"+hw_id).append("<h4><a data-toggle='collapse' data-parent='#hw-lists' href='#collapse"+hw_id+"' aria-expanded='false' aria-controls='collapse"+hw_id+"'>"+hw_name+"</a></h4>");
		$("#collapse"+hw_id).append("<form id='form_hw"+hw_id+"' action='../php/collect_download_form.php' method='post' enctype='multipart/form-data'></form>");
		$("#form_hw"+hw_id).append("<label for='starttime"+hw_id+"'>Starttime: </label>");
		$("#form_hw"+hw_id).append("<input id='starttime"+hw_id+"' name='starttime' type='text' value='"+hw_starttime+"'>");
		$("#form_hw"+hw_id).append("<label for='deadline"+hw_id+"'>Deadline: </label>");
		$("#form_hw"+hw_id).append("<input id='deadline"+hw_id+"' name='deadline' type='text' value='"+hw_deadline+"'>");
		$("#form_hw"+hw_id).append("<input id='download_list"+hw_id+"' name='download_list' hidden>");
	}

	//Get the student list of this class.
	//Use a table element to show the students e-mail,name and the checkbox shows whether to choose and download one's homework or not.
	function processGetStuList(data,hw_id){
		$("#form_hw"+hw_id).append("<table class='table table-responsive' id='table"+hw_id+"'></table>");
		$("#table"+hw_id).append("<tr><th>#</th><th>E-Mail</th><th>Name</th><th>Choose All<input type='checkbox' name='checkall' id='checkall"+hw_id+"'></th></tr>")
		
		var stu_list=JSON.parse(data);
		console.log(stu_list);
		$.each(stu_list,function(index,listinfo){
			showStuList(index,listinfo,hw_id);
		});

		$("#checkall"+hw_id).click(function(){
			var status=$(this).prop("checked");
			$('input[name="hwfile'+hw_id+'"]').prop("checked",status);
		});
		var $hwfile=$("input[name='hwfile"+hw_id+"']");
		$hwfile.click(function(){
			console.log("hwfile click");
			$("#checkall"+hw_id).prop("checked",$hwfile.length==$("input[name='hwfile"+hw_id+"']:checked").length ? true : false);
		});

		$("#form_hw"+hw_id).append("<button type='submit' id='download"+hw_id+"' class='btn btn-success'>"+"<i class='glyphicon glyphicon-download'></i> Download</button>");
		$("#form_hw"+hw_id).append("<hr>");
		$("#form_hw"+hw_id).submit(function(){
			var downloadlist = [];
			$.each($("input[name='hwfile"+hw_id+"']:checked"),function(){
				downloadlist.push($(this).val());
			});
			$("#download_list"+hw_id).val(downloadlist);
		});
	}

	function showStuList(index,listinfo,hw_id){
		var email=listinfo.email;
		var name=listinfo.name;
		var hwpath=listinfo.hwpath;
		console.log("in show hw_id:"+hw_id);
		$("#table"+hw_id).append("<tr><td>"+(index+1)+"</td><td>"+email+"</td><td>"+name+"</td><td><input type='checkbox' name='hwfile"+hw_id+"' value='"+hwpath+"'></td></tr>");
	}

	function errGetStuList(data){
		alert("error ajax");
	}

	function errGetHwLists(data){
		alert("Fail to get homework.");
	}

	pageLoad();
});