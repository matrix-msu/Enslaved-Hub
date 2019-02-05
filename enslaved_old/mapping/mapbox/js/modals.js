/*==========================================================================
* 1) When site features are completed, remove class='openComingSoon' from
*    all relevant elements on page. Example: When technical.php is made,
*    remove the 'openComingSoon' class from the link to technical.php in
*    the aboutNav.php include.
* 2) When site is complete, delete this file, remove all includes of the
*    modals.php (should be one for every page), delete all modals.js
*    scripts (should be one for every page), remove relevant CSS section,
*    and clean up all 'openComingSoon' class instances.
* 3) Also: remove the js.cookie.js script from the head include
==========================================================================*/
/*==========================================================================
* Below is the code for opening and closing the comingSoon modal.
* Anything with class='openComingSoon' opens the modal on click event
* Anything with class='closeComingSoon' closes the modal on click event
* diplayModal is the CSS class that displays the modal (obvious, I know)
==========================================================================*/
$('.openComingSoon').click(function() {
	$('#comingSoon').addClass('displayModal');
	$('body').addClass('modal-open');
	return false;
});

$('.closeComingSoon').click(function() {
	$('#comingSoon').removeClass('displayModal');
	$('body').removeClass('modal-open');
});
/*==========================================================================
* Below is the code for opening and closing the underDev modal.
* The underDev modal is automatically displayed on page load unless
* the user checked the "DONT SHOW AGAIN" box in which case a cookie is
* saved and set to expire 7 days from creation
* Anything with class='closeUnderDev' closes the modal on click event and
* creates the cookie if the user has checked the "DONT SHOW AGAIN" box
==========================================================================*/
window.onload = function() {
	if ($('#home').length > 0 && Cookies.get('show_modal') != 'false') {
		$('#underDev').addClass('displayModal');
		$('body').addClass('modal-open');
	}
};
$('.closeUnderDev').click(function() {
	if (document.getElementById('modalCheckboxInput').checked === true) {
		Cookies.set('show_modal', 'false', { expires: 7 });
	}
	$('#underDev').removeClass('displayModal');
	$('body').removeClass('modal-open');
	return false;
});

$('.modal-content').scroll(function(){
	if ($(this).scrollTop() > 0) {
		$('.modal-x-btn').addClass('modal-x-btn-bdr');
	}
	else {
		$('.modal-x-btn').removeClass('modal-x-btn-bdr');
	}
});
