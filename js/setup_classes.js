$(document).ready(function(){
	var existed_classes=document.getElementById("existed_classes");
	var add_classes=document.getElementById("add_classes");
	var add=document.getElementById("add");
	var addID=0;
	function pageLoad(){
		getExistedClasses(showExistedClasses,errProcess);
		add.onclick=addClass;
	}

	function getExistedClasses(showExistedClasses,errProcess){
		$.ajax({
			type:"GET",
			url:"../php/setup_getClasses.php",
			success:function(data){
				showExistedClasses(data);
			},
			error:function(data){
				errProcess(data);
			}
		});
	}

	function showExistedClasses(data){
		var existedClasses=JSON.parse(data);
		var stuID=0;
		$.each(existedClasses,function(index,classinfo){
			//show table
			var class_id=classinfo.class_id;
			var class_name=classinfo.class_name;
			$("#existed_classes").append("<form id='form_existed"+class_id+"' action='setup_editExistedClass.php' method='post' enctype='multipart/form-data'></form>");
			$("#form_existed"+class_id).append("<label for='existed_class_id"+class_id+"'>Class Name: </label>");
			$("#form_existed"+class_id).append("<input class='existed_classes_input' type='text' id='existed_class_id"+class_id+"' name='existed_class_id"+class_id+"' readOnly value="+class_name+">");
			$("#form_existed"+class_id).append("<input class='btn btn-default' type='button' id='button_delete"+class_id+"' name='button_delete"+class_id+"' value='Delete Class'>");
			$("#button_delete"+class_id).click(function(){
				deleteExist(class_id);
			});
			/*
			**collapse show student list and delete or add students
			*/
			$("#form_existed"+class_id).append("<div class='card' id='card"+class_id+"'></div>");
			$("#card"+class_id).append("<div class='card-header' role='tab' id='heading"+class_id+"'></div>");
			$("#heading"+class_id).append("<h5 class='mb-0' id='h5"+class_id+"'></h5>");
			$("#h5"+class_id).append("<a data-toggle='collapse' data-parent='#existed_div"+class_id+"' href='#collapse"+class_id+"' aria-expanded='false' aria-controls='collapse"+class_id+"'>Show Student List</a>");
			$("#card"+class_id).append("<div id='collapse"+class_id+"' class='collapse' role='tabpanel' aria-labelledby='heading"+class_id+"'></div>");
			$("#collapse"+class_id).append("<div id='edit_stu_div"+class_id+"' class='container'></div>");
			$("#edit_stu_div"+class_id).append("<table id='table"+class_id+"' class='table table-responsive'></table>");
			$("#table"+class_id).append("<tr><th>#</th><th>E-Mail</th><th>Name</th><th>Edit</th></tr>");
		
			getExistedStu(class_id,showExistedStu,errGetExistedStu);

			$("#form_existed"+class_id).append("<input class='btn btn-primary' type='button' id='button_addStu"+class_id+"' value='Add a student'>");
			$("#button_addStu"+class_id).click(function(){
				addStu(class_id,stuID++);
			});
			$("#existed_classes").append("<hr>");
		});
	}

	function addStu(class_id,stuID){
		$("#form_existed"+class_id).append("<div id='div_stu"+stuID+"'></div>");
		$("#div_stu"+stuID).append("<label for='input_email"+stuID+"'>Student E-Mail : </label>");
		$("#div_stu"+stuID).append("<input id='input_email"+stuID+"' type='text'>");
		$("#div_stu"+stuID).append("<label for='input_name"+stuID+"'>Student Name : </label>");
		$("#div_stu"+stuID).append("<input id='input_name"+stuID+"' type='text'>");
		$("#div_stu"+stuID).append("<input type='button' id='button_delStu"+stuID+"' class='btn btn-danger btn-sm' value='Delete'>");
		$("#button_delStu"+stuID).click(function(){
			$("#div_stu"+stuID).remove();
		});
		$("#div_stu"+stuID).append("<input type='button' id='button_saveStu"+stuID+"' class='btn btn-success btn-sm' value='Save'>");
		$("#button_saveStu"+stuID).click(function(){
			var email=$("#input_email"+stuID).val();
			var name=$("#input_name"+stuID).val();
			addStuClass(class_id,email,name,stuID);
		});
	}

	function addStuClass(class_id,email,name,stuID){
		$.ajax({
			type:"GET",
			data:{"class_id":class_id,"email":email,"name":name},
			url:"../php/setup_addStu.php",
			success:function(data){
				var result=JSON.parse(data);
				if(result==1) $("#div_stu"+stuID).prop('disabled',true);
				if(result==0) alert("Error adding the student.");
			},
			error:function(){
				alert("Error ajax in adding the student.");
			}
		});
	}

	function getExistedStu(class_id,showExistedStu,errGetExistedStu){
		$.ajax({
			type:"GET",
			data:{"class_id":class_id},
			url:"../php/setup_getStu.php",
			success:function(data){
				showExistedStu(data,class_id);
			},
			error:function(){
				errGetExistedStu();
			}
		});
	}

	function showExistedStu(data,classid){
		console.log("showExistedStu");
		var stu_list=JSON.parse(data);
		$.each(stu_list,function(index,listinfo){
			var id=listinfo.id;
			var email=listinfo.email;
			var name=listinfo.name;
			console.log("id: "+id);
			$("#table"+classid).append("<tr id='"+classid+"tr"+index+"'><td>"+(index+1)+"</td><td>"+email+"</td><td>"+name+"</td><td><input type='button' name='deleteStu"+classid+"' value='Delete' class='btn btn-danger' id='"+classid+"deleteStu"+index+"'></td></tr>");
			$("#"+classid+"deleteStu"+index).click(function(){
				deleteStu(classid,id,index);
				//$(this).prop('disabled',true);
			});
		});
	}

	function deleteStu(class_id,stu_id,index){
		$.ajax({
			type:"GET",
			data:{"class_id":class_id,"stu_id":stu_id},
			url:"../php/setup_deleteStu.php",
			success:function(data){
				var result=JSON.parse(data);
				if(result==1) $("#"+class_id+"deleteStu"+index).prop('disabled',true);
				if(result==0) alert("Error deleting.");
			},
			error:function(){
				alert("Error ajax in delete student.");
			}
		});
	}

	function errGetExistedStu(){
		alert("Error getting students.");
	}

	function errProcess(data){
		console.log("error ajax");
	}

	function addClass(){
		addID++;
		var form_addClass=document.createElement("form");
		var label_className=document.createElement("label");
		var input_className=document.createElement("input");
		var label_csv=document.createElement("label");
		var input_csv=document.createElement("input");
		var button_delete=document.createElement("input");
		var submit_save=document.createElement("input");
		var hr=document.createElement("hr");
		var br_1=document.createElement("br");
		var br_2=document.createElement("br");

		var file_a=document.createElement("a");
		file_a.className="btn btn-primary btn-file";
		var file_span=document.createElement("span");
		file_span.className="fileupload-new";
		var file_text_span=document.createTextNode("Select File");
		file_span.appendChild(file_text_span);

		form_addClass.action="../php/setup_addClass.php";
		form_addClass.method="post";
		form_addClass.enctype="multipart/form-data";

		label_className.for="classname"+addID;
		label_className.innerHTML=addID+". Class Name : ";

		input_className.id="classname"+addID;
		input_className.type="text";
		input_className.name="classname";

		label_csv.for="csv"+addID;
		label_csv.innerHTML="Please upload the students csv file :";

		input_csv.id="csv"+addID;
		input_csv.type="file";
		input_csv.name="csv";
		input_csv.className="file-input";

		$(input_csv).on('change',function(){
			fileChange(this);
		});

		file_a.appendChild(file_span);
		file_a.appendChild(input_csv);

		button_delete.type="button";
		button_delete.value="Delete";
		button_delete.className="btn btn-default";

		button_delete.onclick=function delClass(){
			add_classes.removeChild(form_addClass);
		};

		submit_save.type="submit";
		submit_save.value="Save";
		submit_save.className="btn btn-success";

		form_addClass.appendChild(label_className);
		form_addClass.appendChild(input_className);
		form_addClass.appendChild(br_1);
		form_addClass.appendChild(label_csv);
		form_addClass.appendChild(file_a);
		form_addClass.appendChild(br_2);
		form_addClass.appendChild(button_delete);
		form_addClass.appendChild(submit_save);
		form_addClass.appendChild(hr);
		add_classes.appendChild(form_addClass);
	}

	function fileChange(target,id) {
		var isIE = /msie/i.test(navigator.userAgent) && !window.opera; 
		var fileSize = 0; 
		var filetypes =[".csv"]; 
		var filepath = target.value; 
		var filemaxsize = 1024;//1M 
		if(filepath){ 
			var isnext = false; 
			var fileend = filepath.substring(filepath.indexOf(".")); 
			if(filetypes && filetypes.length>0){ 
				for(var i =0; i<filetypes.length;i++){ 
					if(filetypes[i]==fileend){ 
						isnext = true; 
						break; 
					} 
				} 
			} 
			if(!isnext){ 
				alert("Please upload a csv file."); 
				target.value =""; 
				return false; 
			} 
		}else{ 
			return false; 
		} 
		if (isIE && !target.files) { 
			var filePath = target.value; 
			var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
			if(!fileSystem.FileExists(filePath)){ 
				alert("File doesn't exist.Please upload again."); 
				return false; 
			} 
			var file = fileSystem.GetFile (filePath); 
			fileSize = file.Size; 
		} else { 
			fileSize = target.files[0].size; 
		} 

		var size = fileSize / 1024; 
		if(size>filemaxsize){ 
			alert("The maximum size is "+filemaxsize/1024+"M!"); 
			target.value =""; 
			return false; 
		} 
		if(size<=0){ 
			alert("The file can not be empty!"); 
			target.value =""; 
			return false; 
		} 
	}

	pageLoad();
});