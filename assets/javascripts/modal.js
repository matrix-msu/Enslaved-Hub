$(".modal").click(function(){
    $("div.modal-view").css('display','block');
    image_src = $('img[style="display: block;"]').attr('src');
    $("div.modal-image").css('background-image', 'url("'+image_src+'")');
    // $('<img class="modal-image" src="'+image_src+'">').appendTo('div.modal-view');
});

$(".modal-view").click(closeModal);
/*
function closeModal () {
    $(".config-table-modal").css('margin-top', ''); //idk what these 2 lines do
    $(".modal-wrap").css('margin-top', '');
    setTimeout(function(){ //this is good
        $('.modal-view').hide();
    }, 100);
}*/
function closeModal () {
    //$(".modal-image").remove();
    $(".modal-view").css('display', '');
}


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
$(document).on("click",".closeUnderDev", function(){
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
