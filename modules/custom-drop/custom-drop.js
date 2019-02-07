$(".active-result:first").addClass('selected');
$(".active-result").addClass('search-match');

$(".chosen-choices").click(function(){
    $(".chosen-drop").toggle();
});


$(document).click(function(e){
	console.log($(e.target));
    if(
		$(e.target).attr('id') !== 'chosen-search' &&
		!$(e.target).hasClass('active-result') &&
		!$(e.target).hasClass('search-choice-close') &&
		!$(e.target).hasClass('search-choice')
	){
		$('.chosen-drop').css('display', 'none');
	}
});
$('.chosen-results').on('click', 'li', function(){
	if( $(this).hasClass('disabled') ){
		return;
	}
	$(this).addClass('disabled').css('color', 'black');
	var itemString =
	'<li class="search-choice" value="test">'+
		'<span>'+$(this).html()+'</span>'+
		'<a class="search-choice-close"><img src="assets/images/x-dark.svg" height="8" width="8"></a>'+
	'</li>';
	$(itemString).insertBefore('.search-field');
});
$('ul.chosen-choices').on('click', 'li', function(){
	if( $(this).hasClass('search-field') ){
		return;
	}
	var text = $(this).find('span').html();
	$(this).remove();
	$('.chosen-results').find('li:contains('+text+')').removeClass('disabled').css('color', '');
});
$('#chosen-search').on('keydown', function(e){
	e.stopPropagation();
	var code = e.keyCode || e.which;
	if( code == 13 ){ //enter
		$('.chosen-results')
			.find('li.selected')
			.not('.disabled')
			// .filter(function () {
	    	// 	return this.style.display == 'block'
			// })
			// .first()
			.click();
	}else if( code == 8 && $(this).val() == '' ){ //backspace
		var length = $('.chosen-choices').find('li').length;
		if( length > 1 ){
			$('.chosen-choices').find('li').eq(length-2).find('.search-choice-close').click();
		}
	}else if( code == 38 ){ //up arrow
		var currentSelected = $('.chosen-results').find('.selected').prev();
		if( !$(currentSelected).hasClass('active-result') || !$(currentSelected).hasClass('search-match') ){
		   currentSelected = $('.chosen-results').find('.selected').prevUntil('.search-match').last().prev();
		}
		if( $(currentSelected).hasClass('active-result') ){
		   $('.chosen-results').find('.selected').removeClass('selected')
		   $(currentSelected).addClass('selected');
		}
	}else if( code == 40 ){ //down arrow
		var currentSelected = $('.chosen-results').find('.selected').next();
		if( !$(currentSelected).hasClass('active-result') || !$(currentSelected).hasClass('search-match') ){
			currentSelected = $('.chosen-results').find('.selected').nextUntil('.search-match').last().next();
		}
		if( $(currentSelected).hasClass('active-result') ){
			$('.chosen-results').find('.selected').removeClass('selected')
			$(currentSelected).addClass('selected');
		}
	}
});
$('#chosen-search').on('input', function(e){

	//}else{
		$('#no-resutls').remove();
		$('.chosen-drop').css('display', 'block');
		var searchText = $(this).val().toLowerCase();
		var anyMatched = false;
		$('.chosen-results').find('li').removeClass('selected').removeClass('search-match');
		$('.chosen-results').find('li').each(function(){
			if( $(this).html().toLowerCase().includes(searchText) ){
				$(this).css('display', 'block').addClass('search-match');
				if( !anyMatched ){ //first match
					$(this).addClass('selected');
				}
				anyMatched = true;
			}else{
				$(this).css('display', 'none');
			}
		})
		if( !anyMatched ){
			$('.chosen-results').append('<li id="no-resutls" class="active-result disabled">No Results for "' +searchText+ '"</li>');
		}
	//}
});
