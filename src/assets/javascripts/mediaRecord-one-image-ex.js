// load all images
images = [
    "imageCardGrid/card.jpg"
]
// generate slider with thumbnails
if (images.length > 1) {
    // show arrows in fig-wrap
    $('.fig-wrap').children('button').css('opacity', '1');
    // show all slider stuff
    $('<div class="thumbnail-slider"><button class="arrow-left hidden sliderNavLeft"></button><button class="arrow-right sliderNavRight"></button><div class="slider-wrap"><ul id="slider" class="slider"></ul></div></div>').appendTo('.thumbnails');
    for (var i = 0; i < images.length; i++){
        $('<li class="thumbnail"><div class="overlay-border"></div><img class="thumbnail-img" src="' + BASE_IMAGE_URL + images[i] + '" alt="' + i + '"></li>').appendTo("ul.slider");
    }
    $('<div class="custom-scrollbar"><div id="slider-bar" class="bar ui-slider"><span id="scroll" class="scroll"></span></div></div>').appendTo('.thumbnails');
    $('span.scroll').css('left', '0%');
}

// input the first image into the figure
// set the first image to be the one in the main display
// check if we have images
var cur
if (images) {
    // set the image to be the src
    window.document.getElementById('carousel-img').src = '' + BASE_IMAGE_URL + images[0] + ''
    window.document.getElementById('carousel-controls').children[0].href = '' + BASE_IMAGE_URL + images[0] + ''
    window.document.getElementById('carousel-controls').children[1].href = '' + BASE_IMAGE_URL + images[0] + ''
    window.document.getElementById('modal-image').src = '' + BASE_IMAGE_URL + images[0] + ''
    $('.thumbnail').first().addClass('selected');
    cur = 0;
}

$('.thumbnail').click(function (e) {
    e.stopPropagation();
    $('.thumbnail').removeClass('selected');
    $(this).addClass('selected');
    window.document.getElementById('carousel-img').src = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('carousel-controls').children[0].href = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('carousel-controls').children[1].href = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('modal-image').src = $(this).children('.thumbnail-img').prop('src');
    cur = parseInt($(this).children('.thumbnail-img').prop('alt'));
    
})

// track and cycle through thumbnails
var thumbs = window.document.getElementsByClassName('thumbnail');
function setThumb() {
    for (var i = 0; i < thumbs.length; i++) {
        thumbs[i].classList.remove('selected');
    }
    thumbs[cur].classList.add('selected');
}

// fig arrow nav
// this will know what img is currently displayed, and will cycle to the next/prev img based on the arrow clicked
$('.fig-wrap .arrow-left').click(function() {
    cur = cur - 1;
    setImage();
});

$('.fig-wrap .arrow-right').click(function() {
    cur = cur + 1;
    setImage();
});

function setImage () {
    if (cur < 0) {
        cur = 11;
    } else if (cur > 11) {
        cur = 0;
    }
    window.document.getElementById('carousel-img').src = '' + BASE_IMAGE_URL + images[cur] + ''
    setThumb();
}

// ~~~~~~~~~~~~~~~~~ //
// slider stuff here //
// ~~~~~~~~~~~~~~~~~ //

var scrollPos = 0;
var scrollWidth;
var innerWidth;
var realScrollWidth;
$('.thumbnail-slider .arrow-right').click(function (event) {
    event.preventDefault();
    scrollPos = scrollPos + 15;
    scrollThumbs();
    setScroll();
});

$('.thumbnail-slider .arrow-left').click(function (event) {
    event.preventDefault();
    scrollPos = scrollPos - 15
    scrollThumbs();
    setScroll();
});

$('.slider').scroll(function () {
    var fb = $('.slider');
    if (fb.scrollLeft() + fb.innerWidth() >= fb[0].scrollWidth) {
        $('.arrow-right').addClass('hidden');
    } else {
        $('.arrow-right').removeClass('hidden');
    }
    if (fb.scrollLeft() <= 20) {
        $('.arrow-left').addClass('hidden');
    } else {
        $('.arrow-left').removeClass('hidden');
    }
});


var thumbScroll;
function scrollThumbs() {
    scrollWidth = window.document.getElementById('slider').scrollWidth;
    innerWidth =  window.document.getElementById('slider').offsetWidth;
    realScrollWidth = scrollWidth - innerWidth;
    thumbScroll = (scrollPos/100) * realScrollWidth;
    $('.slider').animate({
        scrollLeft: "" + thumbScroll
    }, 10);
}

// http://whatamericaate.org/full.record.php?kid=79-2C8-1C2&page=20
// http://whatamericaate.org/js/newRecipes.js
// scrollbar functions can be found here
// http://whatamericaate.org/js/full.record.js
// difficult to reverse-engineer, but the above function is roughly what is used
// current bug: clicking on right-arrow causes looping errors
// jquery slider widget found here https://api.jqueryui.com/slider/
// The horizontal scroll position is the same as the number of pixels that are hidden from view above the scrollable area. 

// ~~~~~~~~~~~~~~~~ //
// CUSTOM SCROLLBAR //
// ~~~~~~~~~~~~~~~~ //

dragScroll(window.document.getElementById('scroll'));

function dragScroll (scr) {
    var pos1 = 0;
    var pos3 = 0;
    // move the elmt from anywhere inside the element
    scr.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e = e || window.event;
        // get mouse pos at startup
        pos3 = e.clientX;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        // calculate the new cursor position
        pos1 = pos3 - e.clientX;
        pos3 = e.clientX;
        var barWidth = window.document.getElementById('slider-bar').offsetWidth;
        scrollPos = (parseInt(scr.offsetLeft - pos1)/barWidth) * 100;
        if (scrollPos < 0) {
            scrollPos = 0;
        } else if (scrollPos > 100) {
            scrollPos = 100;
        }
        scr.style.left = (scrollPos) + "%";
        $('.slider').animate({
            scrollLeft: "" + scrollPos + "%"
        }, 10);
        scrollThumbs();
    }

    function closeDragElement() {
        // stop moving when mouse button is released
        window.document.onmouseup = null;
        window.document.onmousemove = null;
    }
}

function setScroll() {
    if (scrollPos < 0) {
        scrollPos = 0;
    } else if (scrollPos > 100) {
        scrollPos = 100;
    }
   $('.scroll').animate({
        left: "" + scrollPos + "%"
    }, 80);
}
