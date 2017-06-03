function showHwName(xml,k){
	  	xmlDoc = xml.responseXML;
	  	var hwname=xmlDoc.getElementsByTagName("hwname")[0].childNodes[0].nodeValue;
		var hwtime=xmlDoc.getElementsByTagName("hwtime")[0];
		var hwstarttime=hwtime.childNodes[0].childNodes[0].nodeValue;
		var hwdeadline=hwtime.childNodes[1].childNodes[0].nodeValue;
		console.log("k: "+k);
		console.log("showHwName:");
	  	console.log("hwname:"+hwname+" hwstarttime:"+hwstarttime+" hwdeadline:"+hwdeadline);

	  	var card=document.createElement("div");
	  	var card_header=document.createElement("div");
	  	var card_h=document.createElement("h4");
	  	var card_a_text=document.createTextNode(hwname);
	  	//card_h.appendChild(card_t);
	  	var card_a=document.createElement("a");
	  	var card_body=document.createElement("div");

	  	card.className="card";
		card.id="card"+k;
		card_header.className="card-header";
		card_header.setAttribute("role", "tab");
		card_header.id="heading"+k;
		card_h.className="mb-0";
		card_a.setAttribute("data-toggle","collapse");
		card_a.setAttribute("data-parent","#hw-lists");
		card_a.href="#collapse"+k;
		card_a.setAttribute("aria-expanded","false");
		card_a.setAttribute("aria-controls","collapse"+k);
		card_a.appendChild(card_a_text);
		card_body.id="collapse"+k;
		card_body.className="collapse";
		card_body.setAttribute("role","tabpanel");
		card_body.setAttribute("aria-labelledby","heading"+k);

	  	var form_hw = document.createElement("form");
		var label_starttime=document.createElement("label");
		var label_deadline=document.createElement("label");
		var input_starttime=document.createElement("input");
		var input_deadline=document.createElement("input");
		var download_list=document.createElement("input");

		form_hw.id="form_hw"+k;
		form_hw.action="../php/collect_download_form.php";
		form_hw.method="post";
		form_hw.enctype="multipart/form-data";
		
		label_starttime.for="starttime"+k;
		label_starttime.innerHTML="Starttime :";

		input_starttime.id="starttime"+k;
		input_starttime.name="starttime";
		input_starttime.type="text";
		input_starttime.value=hwstarttime;

		label_deadline.for="deadline"+k;
		label_deadline.innerHTML="Deadline :";

		input_deadline.id="deadline"+k;
		input_deadline.name="deadline";
		input_deadline.type="text";
		input_deadline.value=hwdeadline;

		download_list.id="download_list"+k;
		download_list.name="download_list";
		download_list.type="hidden";

		form_hw.appendChild(label_starttime);
		form_hw.appendChild(input_starttime);
		form_hw.appendChild(label_deadline);
		form_hw.appendChild(input_deadline);
		form_hw.appendChild(download_list);

		card_body.appendChild(form_hw);
		card_h.appendChild(card_a);
		card_header.appendChild(card_h);
		card.appendChild(card_header);
		card.appendChild(card_body);
		hw_lists.appendChild(card);
	}
