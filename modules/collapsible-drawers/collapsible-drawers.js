$(document).ready(function(){
	var count = 1
	$('.drawer').click(function () {
	  count = count + 1
	  if (count % 2 == 0) {
	    $('.drawer-body').css('opacity', '1');
	    $(this).addClass('open');
	    $(this).children('img').attr('src', 'assets/images/minus-circle.svg');
	    $(this).children('.heading').text('Click me to close me');
	    $(this).children('.drawer-body').animate({
	      height: 100
	    }, 50);
	  } else {
	    $(this).children('.drawer-body').css('opacity', '0')
	    var thisDraw = $(this);
	    setTimeout(function(){
	        thisDraw.children('.drawer-body').css('height', '');
	        thisDraw.children('img').attr('src', 'assets/images/plus-circle.svg');
	        thisDraw.children('.heading').text('Click me to open me');
	        thisDraw.removeClass('open');
	    }, 250);
	  }
	})
})
