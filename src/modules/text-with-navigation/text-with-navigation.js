$(document).ready(function(){
	var scrollTop;
	var anchor = $('.anchor');
	var anchorPos = [];
	var navLinks = $('.text-link');
	$(document).ready(function () {
	    if (anchor) {
	        for (i = 0; i < anchor.length; i++) {
	            anchorPos.push(anchor[i].offsetTop);
	        }
	    }
	});

	$(document).scroll(function(){
	    scrollTop = $(window).scrollTop();
	    console.log('window scroll pos: ' + scrollTop);
	    console.log('anchorPos: ' + anchorPos);
	    if (scrollTop < 700) {
	        navLinks.removeClass('link-selected');
	        navLinks[0].classList.add('link-selected');
	    } else if (scrollTop >= 700 && scrollTop < 1400) {
	        navLinks.removeClass('link-selected');
	        navLinks[1].classList.add('link-selected');
	    } else if (scrollTop >= 1400 && scrollTop <= 2100) {
	        navLinks.removeClass('link-selected');
	        navLinks[2].classList.add('link-selected');
	    } else {
	        navLinks.removeClass('link-selected');
	        navLinks[3].classList.add('link-selected');
	    }
	});

	// following script borrowed from: https://css-tricks.com/snippets/jquery/smooth-scrolling/

	// Select all links with hashes
	$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
	    // On-page links
	    if (
	        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
	        location.hostname == this.hostname
	    ) {
	        // Figure out element to scroll to
	        var target = $(this.hash);
	        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	        // Does a scroll target exist?
	        if (target.length) {
	            // Only prevent default if animation is actually gonna happen
	            event.preventDefault();
	            $('html, body').animate({
	                scrollTop: target.offset().top - 100
	            }, 1000, function() {
	                // Callback after animation
	                // Must change focus!
	                var $target = $(target);
	                $target.focus();
	                if ($target.is(":focus")) { // Checking if the target was focused
	                    return false;
	                } else {
	                    $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
	                    $target.focus(); // Set focus again
	                };
	            });
	        }
	    }
	});
})
