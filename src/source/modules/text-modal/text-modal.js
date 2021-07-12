$(document).ready(function () {
    $('.canvas').css('opacity', '0');
    $('.modal-text').css('background', 'rgba(13, 18, 48, 0.0)');

	var textModalButton = document.getElementsByClassName('text-modal-text');
	var hiddenTextModal = document.getElementsByClassName('modal-text');

	for (var i=0; i < textModalButton.length; i++) {
	    textModalButton[i].addEventListener('click', showTextModal(i));
	}

	function showTextModal(i) {
	    return function(){
	        hiddenTextModal[i].style.display = 'flex';
	        setTimeout(function(){
	            $('.modal-text').css('background', 'rgba(13, 18, 48, 0.7)');
	            $('.canvas').css('opacity', '1');
	        }, 50);
	    }
	}

	$('.canvas').click(function (e) {
	    e.stopPropagation();
	});

	$('.modal-text').click(function () {
	    $('.close').trigger('click');
	});

	$('.close').click(function () {
	    $('.canvas').css('opacity', '0');
	    $('.modal-text').css('background', 'rgba(13, 18, 48, 0.0)');
	    setTimeout(function(){
	        $('.modal-text').css('display', 'none');
	    }, 400);
	});

	$('.yes').click(function (e) {
	    e.stopPropagation();
	});

	$('.no').click(function (e) {
	    e.stopPropagation();
	});

});
