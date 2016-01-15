$(document).ready(function(){
	// $(".series_image").css("width","50%");
	// $(".series_image").height(250);
	var carousel_image_srcs=[];
	var carousel_series_title=[];
	$("div.jumbotron").css('cursor', 'pointer');
	$("div.jumbotron").click(function(){
		window.open("http://xml.csc.kth.se/~thakrar/DM2517-Project/index.php", "_self");
	});
	$(".series_block").each(function(){
		attr_src = $(this).find("img.series_image").attr('src');
		if(attr_src!="images/default_image.png"){
			carousel_image_srcs.push(attr_src);
			carousel_series_title.push($(this).attr("data-value"));
		}
	});
	makeCarousel(carousel_image_srcs, carousel_series_title );
	
	$("#btn_calendar").click(function(){
		$("#usercatalog").hide();
		$("#seriescatalog").hide();
		$("#calendar").show();
		$.ajax({
			url:"client.php",
			type:"post",
			data:{
				action: "calendar"
			},
			
			success: function(data){
				var skip = false;
				
				var obj = jQuery.parseJSON(data);
				$.each(obj, function(key, value){
					var title = key;
					$("#calendar").append("<h2>"+title+"</h2>");
					$("#calendar").append("<div data-value='" + title +"'>");
					
					$.each(value, function(kkey, vvalue){
						console.log(kkey);
						skip = false;
						// $("p[data-value='episode']").insertAfter($("p[data-value='date']"));
						
						$.each(vvalue, function(kkkey, vvvalue){
							console.log(kkkey);
							
							$.each(vvvalue, function(kkkkey, vvvvalue){
								
								
									if(vvvvalue.toLowerCase().indexOf("720p")>=0 || vvvvalue.toLowerCase().indexOf("repack")>=0){
										skip = true;
									}else{
										if(!skip){
											console.log(key + ", " + vvvvalue);
											if(kkkey=="date"){
												index = vvvvalue.indexOf(":") -3;
												vvvvalue = vvvvalue.substring(0, index);
												$("#calendar > div[data-value='"+title+"']").append("<p data-value='date'>" + vvvvalue + "</p>");
											}else{
												$("#calendar > div[data-value='"+title+"']").append("<p data-value='episode'>" + vvvvalue + "</p>");
											}
											
										}
										
									}
																
							});
							
							
						});
						
					});
					
					len = $("div[data-value='" + key +"'] p").length;
					console.log("length: " + len);
					
					$("div[data-value='" + title +"'] p").each(function(){
						$(this).insertBefore($(this).prev());
					});
					$("div[data-value='" + title +"'] p:nth-child(odd)").css('font-weight', 'bold');
					
					for(i = 0; i < len*2; i+=2){
						console.log(i);
						var div_row=$("<div class='row'>");
						var div_col1=$("<div class='col-md-2'>");
						var div_col2=$("<div class='col-md-4'>");
						
						var div1= $("div[data-value='" + title +"'] p").eq(i);
						var div2= $("div[data-value='" + title +"'] p").eq(i+1);
						
						div_col1.append(div1);
						div_col2.append(div2);
						
						div_row.append(div_col1);
						div_row.append(div_col2);
						
						$("div[data-value='" + title +"']").append(div_row);
					
					}
				});
				
			},			
			error:function(data){
				
			}
				
		});
	});
	$("#admin_add_series").click(function(){
		$.ajax({
			url: "client.php",
			type: "post",
			data: {
				action: "add_series"
			},
			success: function(data){
				alert("Successfully added all series");
				window.location.reload(true);
			}
		});
	});
	$("#admin_manage_series").click(function(){
		var div = $(this).attr("value");
		$("#"+div).show();
		$("#admin_all_users_div").hide();
	});
	$("#admin_manage_users").click(function(){
		var div = $(this).attr("value");
		$("#"+div).show();
		$("#admin_all_series_div").css("display","none");
	});
	$("#exportexcel").click(function(){
		alert("export_excel");
		$.ajax({
			url: "user.php",
			type: "post",
			data: {
				action: "export_excel"
			},
			success: function(data){
				download((new XMLSerializer()).serializeToString(data));
		
			}
		});
	});
	$("#admin_export_excel").click(function(){
		alert("export_excel");
		$.ajax({
			url: "admin.php",
			type: "post",
			data: {
				action: "export_excel"
			},
			success: function(data){
				download((new XMLSerializer()).serializeToString(data));
			
			}
		});
	});
	
	function download(xml){
		var filename = "excel_output";
		var u = "data:text/xml;charset=utf-8," + xml;
		window.open(u);
		
		/*
		var element = document.createElement('a');
		element.setAttribute('href', 'data:text/plain;charset=utf-8,' + xml);
		// element.setAttribute('download',filename);

		element.style.display = 'none';
		document.body.appendChild(element);

		element.click();

		document.body.removeChild(element);
		*/
		
	}

	
	$("#editprofile").click(function(){
		$("#seriescatalog").hide();
		$("#usercatalog").show();
		$("#calendar").hide();
		
		$.ajax({
			url: "client.php",
			type: "post",
			data: {
				action: "edit_profile"
			},
			success: function(data){
				
				var xmlDoc = $.parseXML(data);
				$xml  = $( xmlDoc );
				$email = $xml.find("email");
				$fname = $xml.find("firstname");
				$lname = $xml.find("lastname");
				$("#edit_firstname").val($fname.text());
				$("#edit_lastname").val($lname.text());
				$("#edit_email").val($email.text());
				$("#edit_firstname").focus();
			}
			
		});
	});
	
	$("#edit_profile_updatebtn").click(function(){
		firstname = $("#edit_firstname").val();
		lastname = $("#edit_lastname").val();
		email = $("#edit_email").val();
		if(firstname.length >0 && lastname.length >0 && email.length >0 ){
			$.ajax({
				url: "client.php",
				data: {
					action: "update_profile",
					new_firstname : firstname,
					new_lastname : lastname,
					new_email : email
				},
				type: "post",
				success: function(data){
					alert(data);
					window.location.reload(true);
				}
				
			});
		}else{
			alert("Cannot update profile with empty values...");
		}
		
	});
	$(".subscribe_btn").click(function(){

		title_str = $(this).attr("data-title");
		
		$.ajax( 
		{
			url: "client.php",
			data:{
					action: "subscribe",
					title : title_str
				},
			type:	"post",
			success: function(data){
				if(data=='not logged in'){
					alert("You must log in to subscribe");
					// BootstrapDialog.alert("what");
					
				}else if(data=='already subscribed'){
					alert("You are already subscribed to " + title_str);
				}else{
					alert(data);
					
				}
			},
			error: function(request, errorThrown){
				alert("error: " + errorThrown);
			}

			});
	});
	$(".unsubscribe_btn").click(function(){
		title_str = $(this).attr("data-title");
		alert("Are you sure you want to unsubscribe to " + title_str + "?");
		
		$.ajax(
		{
			url: "client.php",
			type: "post",
			data: {
					action: "unsubscribe",
					title: title_str
			},
			success: function(data){
				alert(data);
				window.location.reload(true);
			},
			error: function(data){
				alert(data);
			}
		});
	});
	toUpperCase($("#user_id_name"));
	toUpperCase($("#user_firstname"));
	toUpperCase($("#user_lastname"));
	$("#user_firstname").css("margin-right", "5px");
	
	$(".remove_user").click(function(){
		userid = $(this).attr("data-value")
		// alert("function.js: " + $(this).attr("data-value"));
		//userid= $(this).attr("data-value");
		
		$.ajax(
			{
				url:"client.php",
				data:{
						action:"remove_user",
						user_id: userid
				},
				type: "post",
				success: function(data){
					alert(data);
					window.location.reload(true);
				},
				error: function(data){
					alert(data);
				}
					
			});
	});
	$("#admin_remove_all_users").click(function(){
		$.ajax({
				url:"client.php",
				data:{
						action:"remove_all_users",
						
				},
				type: "post",
				success: function(data){
					alert(data);
					window.location.reload(true);
				},
				error: function(data){
					alert(data);
				}
		});
	});
	$(".remove_series").click(function(){
		title_str = $(this).attr("data-title")
		// alert("function.js: " + $(this).attr("data-value"));
		//userid= $(this).attr("data-value");
		
		$.ajax(
			{
				url:"client.php",
				data:{
						action:"remove_series",
						title: title_str
				},
				type: "post",
				success: function(data){
					alert(data);
					window.location.reload(true);
				},
				error: function(data){
					alert(data);
				}
					
			});
	});
	$("#admin_remove_all_series").click(function(){
		$.ajax(
			{
				url:"client.php",
				data:{
						action:"remove_all_series",
				},
				type: "post",
				success: function(data){
					alert(data);
					window.location.reload(true);
				},
				error: function(data){
					alert(data);
				}
					
			});
	});
	
	$(".series_block").click(function(e){
		if($(e.target).hasClass('subscribe_btn')){
            e.preventDefault();
            return;
        }
		info_series = $(this).attr("data-value");
		info_series = "info_series_"+info_series;
		console.log(info_series);
		$("div[data-value='"+info_series+"'").css('display', 'block');
		
		
	});
	
	
	
	$(".close_info_series").click(function(){
		$(".info_queried_series").hide();
		$(".info_queried_series_block").empty();
		$(".info_series").hide();
	});
	
	$("#search_query").autocomplete({
		
		source: function(request, response){
			$.ajax({
				url: "includes/autocompletion.php",
				type: "post",
				dataType: "json",
				data: {
					title_startswith: request.term
				},
				success: function( data ) {
					
					 response( $.map( data, function( item ) {
						return {
							label: item,
							value: item
						}
					}));
				}				
			});
			
		},
		minLenght:0,
		autoFocus: true
	});
	
	$("#search_button").click(function(){
		titlestr = $("#search_query").val();
		// alert("Querying db with: " + titlestr);
		$.ajax({
			url: "client.php",
			type: "post",
			
			data: {
					action: "query_series",
					title: titlestr
			},
			success: function(data){
				if(data=="[]"){
					alert("No hit on " + titlestr);
				}else{
					populate_modal_series_info(data);
				}
				
				
				
			},
			error: function(){
				alert(data);
			}
			
		});
	});
});


 function populate_modal_series_info(data_json){
	// var data = $.parseJSON(data_json);
	var json = $.parseJSON(data_json);
	title="";
	$.each(json, function(i,val){
		$.each(val,function(k,v){
			  // alert(k+" : "+ v);  
			if(k=="title"){
				title= v;
				$(".info_queried_series_block").append("<p id='info_queried_series_title'>" + v + "</p>");
			}
			if(k=="plot"){
				$(".info_queried_series_block").append("<p id='info_queried_series_plot'>" + k +": " + v + "</p>");
			}
			if(k=="poster"){
				v = v.substring(34);
				$(".info_queried_series_block").append("<p id='info_queried_series_poster'>" + "<img src='http://www.csc.kth.se/~arefm/DM2517/projekt/images/" + v + "'></img></p>");
			}
			
		});
	});
	
	
	$(".info_queried_series > .subscribe_btn").attr("data-title", title);

	$(".info_queried_series").show();
 }
 function toUpperCase(name_dom){
	name=name_dom.text();
	firstLetter = name.substring(0,1);
	firstLetter = firstLetter.toUpperCase();
	tail = name.substring(1);
	name = firstLetter+tail;
	name_dom.html(name);
	
 }
function makeCarousel(carousel_image_srcs, carousel_series_title){
		
	var div_carousel_inner = $('<div class="carousel-inner" role="listbox">');
	
	var item= $('<div class="item active">');
	var img = $('<img src="'+carousel_image_srcs[0]+'" alt="">');
	var div_carousel_caption= $('<div class="carousel-caption">');
	var h3 = $('<h3>');
	// h3.append(carousel_series_title[0]);
	
	div_carousel_caption.append(h3);
	item.append(img);
	item.append(div_carousel_caption);
	div_carousel_inner.append(item);
	
	//Loop
	for(i = 1; i<10; i++){
		var item= $('<div class="item">');
		var img = $('<img src="'+carousel_image_srcs[i]+'" alt="">');
		var div_carousel_caption= $('<div class="carousel-caption">');
		var h3 = $('<h3>');
		// h3.append(carousel_series_title[i]);
		
		div_carousel_caption.append(h3);
		item.append(img);
		item.append(div_carousel_caption);
		div_carousel_inner.append(item);
	}
	$("#myCarousel").append(div_carousel_inner);
	
	
	
}