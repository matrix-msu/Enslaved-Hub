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

//**********************************************************  get more seeds
function show_more(data)
	{

		if(data!="no more data")
		{$('#crawlSeeds').append(data);}
		else {$('#no-more-seeds').show();$('#moreSeeds').hide();}
	}
var idx=0;
$('#moreSeeds').on("click",function(){

	$('.resultSeeds').hide();
	idx+=20;
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{moreSeeds:idx},
	        success:function(data){show_more(data); },
			dataType: "JSON",
	    });//ajax

});// click
//**************************************************************  more seeds end

//*************************************************************** update a seed from seeds tab
$('#crawlSeeds').on("click",'.update',function(e){

	//update button
	e.preventDefault();
	$('#'+this.id).click(function() { return false; });
	$('#'+this.id).css("color","black");
	$('#'+this.id).css("cursor", "default");
	//delete button
	var usid=this.id;
	var bass=usid.substring(2);
	var dsid='ds'+bass;
	$('#'+dsid).click(function() { return false; });
	$('#'+dsid).css("color","black");
	$('#'+dsid).css("cursor", "default");
	seed_id=bass;
	name=$('#nm'+bass).text();
	title=$('#tt'+bass).text();
	url=$('#ur'+bass).text();
	twitter=$('#tw'+bass).text();
	rss=$('#rs'+bass).text();

	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{update_seed_id:seed_id,name:name,title:title,rss:rss,url:url,twitter:twitter},
	        success:function(data){ },
			dataType: "JSON",
	    });//ajax
});//click
//*************************************************************** update a seed from seeds tab end



//*************************************************************** update a seed from seeds tab
$('#crawlSeeds').on("click",'.delete',function(e){

	//update button
	e.preventDefault();
	$('#'+this.id).click(function() { return false; });
	$('#'+this.id).css("color","black");
	$('#'+this.id).css("cursor", "default");
	//delete button
	var dsid=this.id;
	var bass=dsid.substring(2);
	var usid='us'+bass;
	$('#'+usid).click(function() { return false; });
	$('#'+usid).css("color","black");
	$('#'+usid).css("cursor", "default");
	seed_id=bass;
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{delete_seed_id:seed_id},
	        success:function(data){ },
			dataType: "JSON",
	    });//ajax
});//click
//*************************************************************** update a seed from seeds tab end



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
