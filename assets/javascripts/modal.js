$(".modal").click(function(){
    $("div.modal-view").css('display','block');
    image_src = $('img[style="display: block;"]').attr('src');
    $("div.modal-image").css('background-image', 'url('+image_src+')');
    //$('<img class="modal-image" src="'+image_src+'">').appendTo('div.modal-view');
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