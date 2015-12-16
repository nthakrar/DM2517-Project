$(document).ready(function(){

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
					alert("You are already subscribed to " + $title_str);
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
				// alert(data);
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
	
	$(".delete_user").click(function(){
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
	
	$(".series_block").click(function(){
		
		info_series = $(this).attr("data-value");
		info_series = "info_series_"+info_series;
		
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
		alert("Querying db with: " + titlestr);
		$.ajax({
			url: "client.php",
			type: "post",
			
			data: {
					action: "query_series",
					title: titlestr
			},
			success: function(data){
				populate_modal_series_info(data);
				
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