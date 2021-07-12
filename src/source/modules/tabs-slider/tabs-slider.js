$(document).ready(function(){
	var tabs = $('.tab-slider'); // get all tabs
	var displays = $('.tabs-slider-display'); // get all <main> elements
	if (displays) {
	    displays.css('display', 'none');
	    displays[0].style.display = 'block'
	}

	$('.tab-slider').click(function () {
	    $('.tab-slider').removeClass('active');
	    $(this).addClass('active');
	    setDisplay ($(this).index());
	    // scroll selected tab into view
	    var scrollTo = $(this);
	    $('.arrow-wrap-slider').animate({
	        scrollLeft: scrollTo.offset().left - $('.arrow-wrap-slider').offset().left + $('.arrow-wrap-slider').scrollLeft() - 50 // neg = element moves right
	    });
	});

	$('.arrow-slider.right').click(function(event) {
	    event.preventDefault();
		if( !$('.arrow-wrap-slider').is(':animated') ){
			$('.arrow-wrap-slider').animate({
		        scrollLeft: $('.arrow-wrap-slider').scrollLeft() + 300
		    }, 800);
		}
	});

	$('.arrow-slider.left').click(function(event) {
	    event.preventDefault();
		if( !$('.arrow-wrap-slider').is(':animated') ){
			$('.arrow-wrap-slider').animate({
		        scrollLeft: $('.arrow-wrap-slider').scrollLeft() - 300
		    }, 800);
		}
	});

	$('.arrow-wrap-slider').scroll(function() {
	    var fb = $('.arrow-wrap-slider');
	    if (fb.scrollLeft() + fb.innerWidth() >= fb[0].scrollWidth) {
	        $('.arrow-slider.right').addClass('hidden');
	    } else {
	        $('.arrow-slider.right').removeClass('hidden');
	    }
	    if (fb.scrollLeft() <= 20) {
	        $('.arrow-slider.left').addClass('hidden');
	    } else {
	        $('.arrow-slider.left').removeClass('hidden');
	    }
	});

	function setDisplay (i) {
	    displays.css('display', 'none');
	    if (!displays[i]){
	        displays[0].style.display = 'block'
	    } else {
	        displays[i].style.display = 'block'
	    }
	}

})
