var modals = document.getElementsByClassName('modal');
var hidden_modals = document.getElementsByClassName('modal-view');
var modalImage = $('img.modal-img-view');
var height;
var avail_columns = [];
var selected_columns = [];

for (var i=0; i < modals.length; i++) {
    modals[i].addEventListener('click', showModal(i));
}

function showModal(i) {
    return function(){

        populateColumnOptions(display);

        $(document.body).css('overflow', 'hidden');
        hidden_modals[i].style.display = 'block';
        hidden_modals[i].style.height = '100%';
        setTimeout(function(){
            $('.modal-view').css('background', 'rgba(13, 18, 48, 0.7)');
            setTimeout(function(){
                hidden_modals[i].childNodes[1].style.marginTop = "15px";
                height = modalImage.innerHeight();
            }, 100);
        }, 100);
    }
}

// scroll wheel
$(document).ready(function () {
    $('#modal-image').bind('mousewheel', function (e) {
        if (e.originalEvent.wheelDelta /120 > 0) {
            $('.plus').trigger('click'); // scroll up
        } else {
            $('.minus').trigger('click'); // scroll down
        }
    })
});


$('#modal-img').click(function (e) {
    e.stopPropagation();
})

//clicks on dark background but not child element(modal)
$(".modal-view").click(function(){
    closeModal();
}).children().click(function(e) {
    return false;
});

$("#modal-dim-background-img").click(closeModal);

$('div.close').click(closeModal);

function closeModal () {
    $(document.body).css('overflow', '');
    $(".config-table-modal").css('margin-top', '');
    $(".modal-wrap").css('margin-top', '');
    setTimeout(function(){
        $('.modal-view').css('background', 'rgba(13, 18, 48, -0.3)');
        setTimeout(function(){
            $(".modal-view").css('display', '');
            $(".modal-view").css('height', '');
        }, 150);
    }, 100);
}

$('.maximize').click(function () {
    $('.modal').trigger('click');
})

// Zoom / Unzoom buttons //
$('.plus').click(function () {
    modalImage.css('max-width', 'unset');
    height = height + 50;
    modalImage.css('height', height);
});
$('.minus').click(function () {
    modalImage.css('max-width', 'unset');
    height = height - 50;
    modalImage.css('height', height);
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
// TABLE MODAL HANDLED BELOW HERE //
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

// get all options from left and right columns
// var left_col = window.document.getElementsByClassName('left');
// var right_col = window.document.getElementsByClassName('right');

function populateColumnOptions(type) {
    $.ajax({
        url: BASE_URL + "api/getColumns",
        type: "GET",
        data: {
            'type': type
        },
        'success': function (data) {
            var columns = JSON.parse(data);
            for (var col in columns) {
                if(!avail_columns.includes(columns[col])){
                  $('#available-cols').append(`<li class="left" value=${columns[col]}>${columns[col]}</li>`);
                  avail_columns.push(columns[col]);
                }
            }

        }
    });
}




// Columns


// select thing from left column
var selected_items = [];
$('#available-cols').on('click', 'li', function (e) {
    e.stopPropagation();
    $(this).css('background-color', 'rgba(194,79,60,0.15)');
    $(this).css('color', '#C24F3C')
    $(this).css('border-radius', '7px')
    selected_items.push( $(this).html() );
});

// select things on the right column
var deselected_items = [];
$('#selected-cols').on('click', 'li', function (e) {
    e.stopPropagation();
    $(this).css('background-color', 'rgba(194,79,60,0.15)');
    $(this).css('color', '#C24F3C')
    $(this).css('border-radius', '7px')
    $(this).addClass('selected');
    deselected_items.push( $(this).html() );
});

// move selected things from left column to right column
$('div.arrow-wrap > img:first-child').click(function (e) {
    e.stopPropagation();
    for ( var i = 0; i < selected_items.length; i++) {
      if(!selected_columns.includes(selected_items[i])){
        window.document.getElementById('selected-cols').insertAdjacentHTML('beforeend', '<li class="right">' + selected_items[i] + '</li>');
        $(`#available-cols li:contains(${selected_items[i]})`).remove();
        selected_columns.push(selected_items[i]);
      }
    }
    selected_items.length = 0;
});

// move selected things from right to left
$('div.arrow-wrap > img:last-child').click(function (e) {
    e.stopPropagation();
    for ( var i = 0; i < deselected_items.length; i++) {
        window.document.getElementById('available-cols').insertAdjacentHTML('beforeend', '<li class="left">' + deselected_items[i] + '</li>');
        $(`#selected-cols li:contains(${deselected_items[i]})`).remove();
        // remove deselected items from selected items array
        for ( var j = 0; j < selected_columns.length; j++){
          if(deselected_items[i] == selected_columns[j]){
            selected_columns.splice(j, 1);
          }
        }
    }
    deselected_items.length = 0;
});

// http://www.slavevoyages.org/voyage/search
// http://www.slavevoyages.org/static/scripts/voyage/voyage-search.js
// ctrl-f 'function move_var(direction)' to find where this is handled

// move selected items down when down arrow is clicked
// first img in html is 'move-down' arrow
$('img.down').click(function (e) {
    e.stopPropagation();
    // move down
    var $deselected_items = $('#selected-cols li.selected');
    $deselected_items.last().next().after($deselected_items);
});

// move selected items up when up arrow is clicked
// last img in html is the 'move-up' arrow
$('img.up').click(function (e) {
    e.stopPropagation();
    // move up
    var $deselected_items = $('#selected-cols li.selected');
    $deselected_items.first().prev().before($deselected_items);
});

// deselect all elements when clicking off of items
$('.config-table-modal').click(function () {
    $('#selected-cols > li').css('background', '');
    $('#available-cols > li').css('background', '');
    $('#selected-cols > li').css('color', '');
    $('#available-cols > li').css('color', '');
    $('#selected-cols > li').removeClass('selected');
    $('#available-cols > li').removeClass('selected');
});

$('.update-columns-button').click(function(e) {
    // console.log(selected_items);
    // console.log(other_items);
    // closeModal();
})

// ~~~~~~~~~~~~~~~ //
// DRAGGABLE MODAL //
// ~~~~~~~~~~~~~~~ //

// https://www.w3schools.com/howto/howto_js_draggable.asp
// Make the DIV element draggagle:
if (window.document.getElementById('modal-image')) {
    dragElement(document.getElementById(("modal-image")));

    function dragElement(elmnt) {
      var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      if (document.getElementById(elmnt.id + "modal-image")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "modal-image").onmousedown = dragMouseDown;
      } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
      }

      function dragMouseDown(e) {
        e = e || window.event;
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        elmnt.style.right = 'unset';
        elmnt.style.bottom = 'unset';
      }

      function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }
}
