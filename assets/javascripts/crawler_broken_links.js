$(document).ready(function(){

//update a link in the seeds file and remove it from the broken links
$('#brokenLinks').on("click",'.update',function(e){
	//update button
	e.preventDefault();
	$('#'+this.id).click(function() { return false; });
	$('#'+this.id).css("color","black");
	$('#'+this.id).css("cursor", "default");
	//delete button
	var uid=this.id;
	var did='d'+uid.substring(1);
	$('#'+did).click(function() { return false; });
	$('#'+did).css("color","black");
	$('#'+did).css("cursor", "default");
	var x=this.id;
		var ids="hid";
		x=x.substr(1);
		ids+=x;
		var key=$('#'+ids).text();
		var new_key="update"+x;
		new_key=$('#'+new_key).text();
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{old_link:key, update_link:new_key},
	        success:function(data){ },
			dataType: "JSON",
	    });//ajax
});//click


// delete a link from the broken links database
$('#brokenLinks').on("click",'.delete',function(e){
	//delete button
	e.preventDefault();
	$('#'+this.id).click(function() { return false; });
	$('#'+this.id).css("color","black");
	$('#'+this.id).css("cursor", "default");
	//update button
	var xx=this.id;
	var uid='u'+xx.substr(1);
	$('#'+uid).click(function() { return false; });
	$('#'+uid).css("color","black");
	$('#'+uid).css("cursor", "default");

	var ids="hid";
		ids+=xx.substr(1);
		var key=$('#'+ids).text();
	//alert($('#'+ids).text());
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{del_row:key},
	        success:function(data){ },
			dataType: "JSON",
	    });//ajax
});//click
});//document ready
