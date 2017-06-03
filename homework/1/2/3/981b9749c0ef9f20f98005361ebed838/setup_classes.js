$(document).ready(function(){
	var i=1;
	var classesid = new Array();  //Stored the final classes ids
	var deletelist = new Array(); //Stored the classes ids that chosen to be deleted
	var class_num;
	var form=document.getElementById("setupClasses");
	function pageLoad(){
		getExistedClasses(processExistedClasses,errProcess);

		var add=document.getElementById("add");
		add.onclick=addClass;

		var submit=document.getElementById("submit");
		$(form).on('submit',function(){
			return classesArray();
		});
	}
	function getExistedClasses(callback,errback){
		$.ajax({
			type:"GET",
			url:"../php/setupDB_classes.php",
			success:function(data){
				callback(data);
			},
			error:function(data){
				errback(data);
			}
		});
	}

	//Get the information of the existed classes
	function processExistedClasses(data){
		var existedClasses=JSON.parse(data);
		console.log("success ajax");
		$.each(existedClasses,function(index,classinfo){
			showExistedClass(index,classinfo);
		});
	}

	function errProcess(err){
		console.log("error ajax");
	}

	//To show the existed classes list
	function showExistedClass(index,classinfo){
		var class_id=classinfo.class_id;
		var class_name=classinfo.class_name;
		$("#existed").append("<div class='container' id='existed_div"+class_id+"'></div>");
		$("#existed_div"+class_id).append("<label for='existed_class_id"+class_id+"'>Class Name: </label>"); //class name
		$("#existed_div"+class_id).append("<input class='existed_classes_input' type='text' id='existed_class_id"+class_id+"' name='existed_class_id"+class_id+"' readOnly value="+class_name+">");
		//$("#existed_div"+class_id).append("<input class='btn btn-default' type='button' id='button_view"+class_id+"' name='button_view"+class_id+"' value='View Students List'>");
		$("#existed_div"+class_id).append("<input class='btn btn-default' type='button' id='button_delete"+class_id+"' name='button_delete"+class_id+"' value='Delete'>");
		/*
		**collapse show student list and delete or add students
		*/
		
		$("#existed_div"+class_id).append("<div class='card' id='card"+class_id+"'></div>");		
		$("#card"+class_id).append("<div class='card-header' role='tab' id='heading"+class_id+"'></div>");
		$("#heading"+class_id).append("<h5 class='mb-0' id='h5"+class_id+"'></h5>");
		$("#h5"+class_id).append("<a data-toggle='collapse' data-parent='#existed_div"+class_id+"' href='#collapse"+class_id+"' aria-expanded='false' aria-controls='collapse"+class_id+"'>Show Student List</a>");
		$("#card"+class_id).append("<div id='collapse"+class_id+"' class='collapse' role='tabpanel' aria-labelledby='heading"+class_id+"'></div>");
		$("#collapse"+class_id).append("<div id='edit_stu_div"+class_id+"' class='container'></div>");
		$("#edit_stu_div"+class_id).append("<table id='table"+class_id+"' class='table table-responsive'></table>");
		$("#table"+class_id).append("<tr><th>#</th><th>E-Mail</th><th>Name</th><th>Edit</th></tr>");
	
		getExistedStu(class_id,processGetExistedStu,errGetExistedStu);

		$("#existed_div"+class_id).append("<hr>");
		//$("#button_view"+class_id).click(function(){ viewStu(class_id); });
		$("#button_delete"+class_id).click(function(){
			deleteExist(class_id);
		});

		function getExistedStu(classid,processGetExistedStu,errGetExistedStu){
			$.ajax({
				type:"GET",
				data:{"class_id":classid},
				url:"../php/setup_get_stulist.php",
				success:function(data){
					processGetExistedStu(data,classid);
				},
				error:function(){
					errGetExistedStu();
				}
			});
		}

		function processGetExistedStu(data,classid){
			var stu_list=JSON.parse(data);
			console.log("processGetExistedStu classid: "+classid);
			$.each(stu_list,function(index,listinfo){
				var email=listinfo.email;
				var name=listinfo.name;
				$("#table"+class_id).append("<tr id='"+classid+"tr"+index+"'><td>"+(index+1)+"</td><td>"+email+"</td><td>"+name+"</td><td><input type='button' name='deleteStu"+classid+"' value='Delete' class='btn btn-danger' id='"+classid+"deleteStu"+index+"'></td></tr>");
				//
				$("#"+classid+"deleteStu"+index).click(function(){
					deleteStu(classid,email);
					deleteCSV(classid,email,name);
					$(this).prop('disabled',true);
				});
			});

			function deleteStu(classid,email){
				$.ajax({
					type:"GET",
					data:{"class_id":classid,"email":email},
					url:"../php/setup_delete_stu.php",
					success:function(data){
						var result=JSON.parse(data);
						if(result==1) alert("Delete "+email+" from the class.");
						if(result==0) alert("Error deleting.");
					},
					error:function(){
						alert("Error ajax in delete student.");
					}
				});
			}

			function deleteCSV(classid,email,name){
				$.ajax({
					type:"GET",
					data:{"class_id":classid,"email":email,"name":name},
					url:"../php/setup_delete_csv.php",
					success:function(data){
						var result=JSON.parse(data);
						if(result==1) alert("Delete "+email+" from the class.");
						if(result==0) alert("Error deleting.");
					},
					error:function(){
						alert("Error ajax in delete student.");
					}
				});
			}
		}

		function errGetExistedStu(){
			alert("Can not get student list.");
		}
	}

	

	//To delete the chosen class
	function deleteExist(class_id){
		alert("Delete");
		deletelist.push(class_id);
		var existed=document.getElementById("existed");
		var existed_div=document.getElementById("existed_div"+class_id);
		existed.removeChild(existed_div);	
	}

	//To add a new class
	function addClass(){
		var classes=document.getElementById("classes");
		var div=document.createElement("div");
		var label=document.createElement("label");
		var inputClass_id=document.createElement("input");
		var br=document.createElement("br");
		
		var inputClass_csv=document.createElement("input");
		var delClass=document.createElement("input");
		var hr=document.createElement("hr");

		//To show the upload file element in English
		var file_a=document.createElement("a");
		file_a.className="btn btn-primary btn-file";
		var file_span=document.createElement("span");
		file_span.className="fileupload-new";
		var file_text_span=document.createTextNode("Select File");
		file_span.appendChild(file_text_span);
		var label_csv=document.createElement("label");

		div.id="class"+i;
		label.for="class_id"+i;
		label.innerHTML=i+". Class Name :";
		inputClass_id.type="text";
		inputClass_id.class="class_id";
		inputClass_id.name="class_id"+i;
		inputClass_id.id="class_id"+i;

		classesid.push(inputClass_id.id);
		console.log(classesid);

		label_csv.for="class_csv"+i;
		label_csv.innerHTML="Upload the students csv file :";

		inputClass_csv.type="file";
		inputClass_csv.name="class_csv"+i;
		inputClass_csv.id="class_csv"+i;
		inputClass_csv.className="file-input";

		$(inputClass_csv).on('change',function(){
			console.log("onchange!");
			fileChange(this);
		});

		//Check file type and file size
		var isIE = /msie/i.test(navigator.userAgent) && !window.opera; 
		function fileChange(target,id) {
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

		file_a.appendChild(file_span);
		file_a.appendChild(inputClass_csv);

		delClass.type="button";
		delClass.value="Delete";
		delClass.name="class_del";
		delClass.id="class_del"+i;
		delClass.className="btn btn-default";
		//$("#"+delClass.id).addClass("btn btn-default btn-lg");
		delClass.onclick=function classDel() {
			alert("Delete class"+inputClass_id.id);
			var del_index=classesid.indexOf(inputClass_id.id);
			classesid.splice(del_index,1);
			classes.removeChild(div);
		};

		i=i+1;

		div.appendChild(label);
		div.appendChild(inputClass_id);
		div.appendChild(br);
		div.appendChild(label_csv);
		//div.appendChild(inputClass_csv);
		div.appendChild(file_a);
		div.appendChild(delClass);
		div.appendChild(hr);

		classes.appendChild(div);
	}

	function classesArray(){
		var flag=1;
		$("input[class='class_id']").each(function(){
			if(this.value==""){
				alert("The class name can not be empty!");
				location.reload();
				flag=0;
			}
		});

		if(flag==0) return false;
		else {
			var classarray=document.getElementById("classarray");
			classarray.value=classesid;
			console.log(classesid);

			var deletearray = document.getElementById("deletearray");
			deletearray.value=deletelist;
			console.log(deletelist);
			return true;
		}	
	}

	pageLoad();

});