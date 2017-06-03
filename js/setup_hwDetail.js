$(document).ready(function(){
	var i=1; //number of homework
	var hw_num;
	var input_classid=document.getElementById("classid");
	var classid=input_classid.value;
	function pageLoad(){
		getClassName(classid);
		//console.log("classid in pageload:"+classid);
		var addhw=document.getElementById("addhw");
		addhw.onclick=addHW;
	}

	//Shows the class name in h2 element
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

	//Add classes
	//Use collapse to have a better view
	function addHW(){	
		var j=1;//file number	
		var filesid = new Array();

		var homeworks=document.getElementById("homeworks");

		var card=document.createElement("div");
		var card_header=document.createElement("div");
		var h_title=document.createElement("h4");
		var a_title=document.createElement("a");
		var a_text=document.createTextNode("Homework "+i);
		var card_body=document.createElement("div");

		card.className="card";
		card.id="card"+i;
		card_header.className="card-header";
		card_header.setAttribute("role", "tab");
		card_header.id="heading"+i;
		h_title.className="mb-0";
		a_title.setAttribute("data-toggle","collapse");
		a_title.setAttribute("data-parent","#homeworks");
		a_title.href="#collapse"+i;
		a_title.setAttribute("aria-expanded","false");
		a_title.setAttribute("aria-controls","collapse"+i);
		a_title.appendChild(a_text);
		card_body.id="collapse"+i;
		card_body.className="collapse";
		card_body.setAttribute("role","tabpanel");
		card_body.setAttribute("aria-labelledby","heading"+i);

		//var form_hw=document.createElement("form");
		var div_hw=document.createElement("div");
		var del_hw=document.createElement("input");
		var add_file=document.createElement("input");
		var files=document.createElement("div");

		var label_hw_name=document.createElement("label");
		var hw_name=document.createElement("input");

		var hw_time=document.createElement("div");

		var label_hw_starttime=document.createElement("label");
		var hw_starttime=document.createElement("input");

		var label_hw_deadline=document.createElement("label");
		var hw_deadline=document.createElement("input");
		var save_hw=document.createElement("input");
		var class_id=document.createElement("input");
		var hw_id=document.createElement("input");
		var files_id=document.createElement("input");
		var br=document.createElement("br");
		var hr=document.createElement("hr");

		class_id.id="class_id";
		class_id.name="class_id";
		class_id.type="hidden";
		class_id.value=classid;

		hw_id.id="hw_id"+i; //record submitted hw id
		hw_id.name="hw_id";
		hw_id.type="hidden";
		//hw_id.type="text";
		hw_id.value="hw_id"+i;

		files_id.id="files_id"+i;
		files_id.name="files_id";
		files_id.type="hidden";
		
		div_hw.id="div_hw"+i;

		del_hw.id="delhw"+i;
		del_hw.name="delhw"+i;
		del_hw.type="button";
		del_hw.value="Delete homework.";
		del_hw.className="btn btn-danger";
		del_hw.onclick=function delHW(){
			alert("Delete homework "+div_hw.id);
			homeworks.removeChild(card);
		}

		add_file.id="add_file"+i;
		add_file.name="add_file"+i;
		add_file.type="button";
		add_file.value="Add files requirements";
		add_file.className="btn btn-primary";

		files.id="files"+i;
		files.className="container";

		label_hw_name.for=hw_name.id;
		label_hw_name.innerHTML="Homework name: ";

		hw_name.id="hw_name"+i;
		hw_name.name="hw_name";
		hw_name.type="text";

		hw_time.id="hw_time"+i;

		hw_starttime.id="hw_starttime"+i;
		hw_starttime.name="hw_starttime";
		hw_starttime.type="date";
		label_hw_starttime.for=hw_starttime.id;
		label_hw_starttime.innerHTML="Start time: ";

		hw_deadline.id="hw_deadline"+i;
		hw_deadline.name="hw_deadline";
		hw_deadline.type="date";
		label_hw_deadline.for=hw_deadline.id;
		label_hw_deadline.innerHTML="Deadline: ";

		save_hw.id="save_hw"+i;
		save_hw.name="save_hw";
		//save_hw.type="submit";
		save_hw.type="button";
		save_hw.value="Save";
		save_hw.className="btn btn-success";
		save_hw.addEventListener("click",function(){
			$("#"+div_hw.id+" input[type=text]").prop("readonly",true);
			$("#"+div_hw.id+" input[type=date]").prop("readonly",true);
			$("#"+div_hw.id+" input[type=button]").prop("disabled",true);
			processSaveHw(classid,hw_id.value,hw_name.value,hw_starttime.value,hw_deadline.value,filesid);
		});

		hw_time.appendChild(label_hw_starttime);
		hw_time.appendChild(hw_starttime);
		hw_time.appendChild(br);
		hw_time.appendChild(label_hw_deadline);
		hw_time.appendChild(hw_deadline);
		div_hw.appendChild(label_hw_name);
		div_hw.appendChild(hw_name);
		div_hw.appendChild(hw_time);
		div_hw.appendChild(add_file);
		div_hw.appendChild(files);
		div_hw.appendChild(class_id);
		div_hw.appendChild(hw_id);
		div_hw.appendChild(files_id);
		div_hw.appendChild(del_hw);
		div_hw.appendChild(save_hw);
		div_hw.appendChild(hr);

		card_body.appendChild(div_hw);

		h_title.appendChild(a_title);
		card_header.appendChild(h_title);
		card.appendChild(card_header);
		card.appendChild(card_body);
		homeworks.appendChild(card);

		//A homework may request several files to be uploaded.
		//Click add file button to add files requirements.
		add_file.onclick=function addFile(){
			var k=i-1;

			var div_file=document.createElement("div");
			var del_file=document.createElement("input");
			//var file_type=document.createElement("input");
			var file_type=document.createElement("select");

			var option_choose=document.createElement("option");
			option_choose.value="0";
			var text_choose=document.createTextNode("--Choose File Type--");
			option_choose.appendChild(text_choose);
			file_type.appendChild(option_choose);

			var option_php=document.createElement("option");
			option_php.value=".php";
			var text_php=document.createTextNode(".php");
			option_php.appendChild(text_php);
			file_type.appendChild(option_php);

			var option_js=document.createElement("option");
			option_js.value=".js";
			var text_js=document.createTextNode(".js");
			option_js.appendChild(text_js);
			file_type.appendChild(option_js);

			var option_css=document.createElement("option");
			option_css.value=".css";
			var text_css=document.createTextNode(".css");
			option_css.appendChild(text_css);
			file_type.appendChild(option_css);

			var option_html=document.createElement("option");
			option_html.value=".html";
			var text_html=document.createTextNode(".html");
			option_html.appendChild(text_html);
			file_type.appendChild(option_html);

			var option_c=document.createElement("option");
			option_c.value=".c";
			var text_c=document.createTextNode(".c");
			option_c.appendChild(text_c);
			file_type.appendChild(option_c);

			var option_cpp=document.createElement("option");
			option_cpp.value=".cpp";
			var text_cpp=document.createTextNode(".cpp");
			option_cpp.appendChild(text_cpp);
			file_type.appendChild(option_cpp);

			var option_java=document.createElement("option");
			option_java.value=".java";
			var text_java=document.createTextNode(".java");
			option_java.appendChild(text_java);
			file_type.appendChild(option_java);

			var option_py=document.createElement("option");
			option_py.value=".py";
			var text_py=document.createTextNode(".py");
			option_py.appendChild(text_py);
			file_type.appendChild(option_py);

			var option_doc=document.createElement("option");
			option_doc.value=".doc";
			var text_doc=document.createTextNode(".doc");
			option_doc.appendChild(text_doc);
			file_type.appendChild(option_doc);

			var file_size=document.createElement("input");
			var label_file_type=document.createElement("label");
			var label_file_size=document.createElement("label");
			div_file.id=k+"file"+j;
			div_file.name=k+"file"+j;

			filesid.push(div_file.id);
			console.log(filesid);

			del_file.id=i+"del_file"+j;
			del_file.name=i+"del_file"+j;
			del_file.type="button";
			del_file.value="Delete";
			del_file.className="btn btn-danger btn-sm";
			del_file.onclick=function delFile(){
				alert("Delete file "+div_file.id);

				var delfile_index=filesid.indexOf(div_file.id);
				console.log(delfile_index);
				filesid.splice(delfile_index,1);
				console.log(filesid);

				files.removeChild(div_file);
			};

			file_type.id=k+"file_type"+j;
			file_type.name="file_type"+j;
			//file_type.type="text";
			//file_type.value="test";

			label_file_type.for=file_type.id;
			label_file_type.innerHTML="File type: ";

			file_size.id=k+"file_size"+j;
			file_size.name="file_size"+j;
			file_size.type="text";

			label_file_size.for=file_size.id;
			label_file_size.innerHTML="File size:(KB) ";

			j=j+1;
			div_file.appendChild(label_file_type);
			div_file.appendChild(file_type);
			div_file.appendChild(label_file_size);
			div_file.appendChild(file_size);
			div_file.appendChild(del_file);
			files.appendChild(div_file);
		};

		i=i+1;
	}

	//Send the homework requirements to php file and save the requirements in a XML file
	function processSaveHw(classid,hw_id,hw_name,hw_starttime,hw_deadline,filesid){
		var files_length=filesid.length;
		var file_info=new Array();
		var files_info=new Array();
		for(var i=0;i<files_length;i++){
			var file_id=filesid[i];
			var file_type_id=filesid[i];
			var file_size_id=filesid[i];
			file_type_id=file_type_id.replace("file","file_type");
			file_size_id=file_size_id.replace("file","file_size");
			var file_type_value=document.getElementById(file_type_id).value;
			var file_size_value=document.getElementById(file_size_id).value;
			file_info=[file_id,file_type_value,file_size_value];
			console.log("file_info: "+file_info);
			files_info.push(file_info);
		}
		$.ajax({
			type:"GET",
			data:{"class_id":classid,"hw_id":hw_id,"hw_name":hw_name,"hw_starttime":hw_starttime,"hw_deadline":hw_deadline,"files_info":JSON.stringify(files_info)},
			url:"../php/setup_hwDetail.php",
			success:function(){
				//callback(data);
				alert("Saved!");
			},
			error:function(){
				alert("Error ajax!");
			}
		});
	}

	pageLoad();
});