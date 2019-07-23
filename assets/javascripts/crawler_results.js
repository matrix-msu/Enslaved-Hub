$(document).ready(function(){
function show_more(data)
	{

		if(data!="no more data")
		{$('#resultsWeb').append(data);}
		else {$('#no-more').show();$('#more').hide();}
	}
var idx=0;
//add a deleted keyword to the deleted keywords file
$('#resultsWeb').on("click",'.delete',function(e){
	e.preventDefault();
	$('#'+this.id).click(function() { return false; });
	$('#'+this.id).css("color","black");
	$('#'+this.id).css("cursor", "default");
	var ids="k";
		ids+=this.id;
		var key=$('#'+ids).text();
	//alert($('#'+ids).text());
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{deleted:key},
	        success:function(data){ },
			dataType: "JSON",
	    });//ajax
});//click
$('#more').on("click",function(){

if(!$('#sortDate').val())
{
	$('.result').hide();
	idx+=20;
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{MORE:idx},
	        success:function(data){show_more(data); },
			dataType: "JSON",
	    });//ajax
}
else {
	var range=$('#sortDate').val();
date=range.substring(12);
$('.result').hide();
	idx+=20;
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{idx:idx,date:date},
	        success:function(data){show_more(data); },
			dataType: "JSON",
	    });//ajax
}
});// click


// when filter is selected
$('#sortDate').on("change",function(e){

var range=this.options[e.target.selectedIndex].text;
date=range.substring(12);
$('#no-more').hide();
$('#more').show();
	$('.result').hide();
	jQuery.ajax({
	    	method:'POST',
	    	url:"ajax/crawler_jquery.php",
			data:{date:date,idx:0},
	        success:function(data){show_more(data); },
			dataType: "JSON",
	    });//ajax

});// change

});//document ready
