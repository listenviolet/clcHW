$(document).ready(function(){
	var hws=document.getElementById("hws");
	var input_classid=document.getElementById("classid");
	var stu_classid=input_classid.value;
	var filesType=new Array();
	var filesSize=new Array();
	console.log("stu_classid: "+stu_classid);

	pageLoad();

	function pageLoad(){
		getHwInfo(processGetHwInfo,errGetFileName);
	}

	function getHwInfo(processGetHwInfo,errGetFileName){
		$.ajax({
			type:"GET",
			data:{"stu_classid":stu_classid},
			url:"../php/stu_get_hws.php",
			success:function(data){
				processGetHwInfo(data);
			},
			error:function(data){
				errGetFileName(data);
			}
		});
	}

	function processGetHwInfo(data){
		var hw_array=JSON.parse(data);
		console.log("in processGetHwInfo");
		$.each(hw_array,function(index,hwinfo){
			processGetFileInfo(index,hwinfo);
		});
	}

	//Each homework requires one or several files to be uploaded
	//Each file has the limitations about the maximum size and the type 
	function processGetFileInfo(index,hwinfo){
		var hw_id=hwinfo.hw_id;
		var hw_name=hwinfo.hw_name;
		var hw_starttime=hwinfo.hw_starttime;
		var hw_deadline=hwinfo.hw_deadline;
		var hw_xml=hwinfo.hw_xml;
		var hw_path=hwinfo.hw_path;
		console.log("hw_xml: "+hw_xml);

		var xhttp=new XMLHttpRequest();
		xhttp.onreadystatechange=function(){
			if(this.readyState==4 && this.status==200){
				showHwRequire(this,hw_id,hw_name,hw_starttime,hw_deadline);
			}
		};

		if(xhttp.open("GET",hw_xml,true)){
			console.log("xhttp open.");
		}
		xhttp.send();
	}

	function showHwRequire(xml,hw_id,hw_name,hw_starttime,hw_deadline,k){
		var xmlDoc=xml.responseXML;
		var hw_files_length=xmlDoc.getElementsByTagName("files")[0].childNodes.length;
		console.log("hw_name: "+hw_name+" hw_files_length: "+hw_files_length);
		$("#hws").append("<form id='hw_form"+hw_id+"' action='../php/stu_uploadhw.php' method='post' enctype='multipart/form-data'></form>");
		$("#hw_form"+hw_id).append("<h4>"+hw_name+"</h4>");
		$("#hw_form"+hw_id).append("<input name='hw_name' value='"+hw_name+"' hidden>");
		$("#hw_form"+hw_id).append("<div id='hw_starttime_div"+hw_id+"' name='hw_starttime_div'>Start time: "+hw_starttime+"</div>");
		$("#hw_form"+hw_id).append("<div id='hw_deadline_div"+hw_id+"' name='hw_deadline_div'>Deadline: "+hw_deadline+"</div>");
		$("#hw_form"+hw_id).append("<input id='hw_class' name='hw_class' value="+stu_classid+" hidden>");
		$("#hw_form"+hw_id).append("<input id='hw_id"+hw_id+"' name='hw_id' value='"+hw_id+"' hidden>");
		$("#hw_form"+hw_id).append("<input id='hw_files_num"+hw_id+"' name='hw_files_num' value="+hw_files_length+" hidden>");
		$("#hw_form"+hw_id).append("<div id='hw_file_div"+hw_id+"'></div>");
		$("#hw_form"+hw_id).append("<input id='hw_submit"+hw_id+"' name='hw_submit' type='submit' value='Submit Homework' class='btn btn-success'>");
		$("#hw_form"+hw_id).append("<hr>");
		for(var j=0;j<hw_files_length;j++){
			var hw_curr_file;
			hw_curr_file=xmlDoc.getElementsByTagName("files")[0].childNodes[j];
			var hw_curr_filetype=hw_curr_file.childNodes[0].childNodes[0].nodeValue;			
			var hw_curr_filesize=hw_curr_file.childNodes[1].childNodes[0].nodeValue;

			$("#hw_file_div"+hw_id).append("<h5>File "+(j+1)+": </h5>");
			$("#hw_file_div"+hw_id).append("<label for='"+hw_id+"filesize_input"+j+"'>Maximum size: </label>");
			$("#hw_file_div"+hw_id).append("<input id='"+hw_id+"filesize_input"+j+"' value='"+hw_curr_filesize+"' readonly>");

			$("#hw_file_div"+hw_id).append("<label for='"+hw_id+"filetype_input"+j+"'>File type: </label>");
			$("#hw_file_div"+hw_id).append("<input id='"+hw_id+"filetype_input"+j+"' value='"+hw_curr_filetype+"' readonly>");
			console.log("<input id='"+hw_id+"filetype_input"+j+"'");
			$("#hw_file_div"+hw_id).append("<a class='btn btn-primary btn-file'><span class='fileupload-new'>Choose file</span><input type='file' id='"+hw_id+"upload"+j+"' name='upload"+j+"'></a>");
			$("#"+hw_id+"upload"+j).change(function(){

				var curr_filesize=this.files[0].size;
				var curr_filename=$(this).val();
				var curr_filetype=curr_filename.substring(curr_filename.indexOf("."));
				var id=$(this).attr("id");
				checkFile(id,curr_filesize,curr_filetype);
			});
		}

	}

	function checkFile(id,curr_filesize,curr_filetype){
		var hwID=id.substring(0,id.indexOf("upload"));
		var fileID=id.substring(id.indexOf("upload")+6);
		var require_size=$("#"+hwID+"filesize_input"+fileID).val();
		var require_type=$("#"+hwID+"filetype_input"+fileID).val();
		if(require_size*1000<curr_filesize){
			alert("The file size exceeds the limit allowed and cannot be saved.");
			window.location.reload();
		}
		else if(require_type!=curr_filetype){
			alert("The file type is not allowed.");
			window.location.reload();
		}
	}

	function errGetFileName(data){
		console.log("err ajax");
	}	
});