imagesSlider = [
    "imageCardGrid/card.jpg",
    "imageCardGrid/card2.jpg",
    "imageCardGrid/card3.jpg",
    "imageCardGrid/card4.jpg",
    "imageCardGrid/card5.jpg",
    "imageCardGrid/card6.jpg",
    "imageCardGrid/card7.jpg",
    "imageCardGrid/card8.jpg",
    "imageCardGrid/card9.jpg",
    "imageCardGrid/card10.jpg",
    "imageCardGrid/card11.jpg",
    "imageCardGrid/card12.jpg",
    "imageCardGrid/card.jpg",
    "imageCardGrid/card2.jpg",
    "imageCardGrid/card3.jpg",
    "imageCardGrid/card4.jpg",
    "imageCardGrid/card5.jpg",
    "imageCardGrid/card6.jpg",
    "imageCardGrid/card7.jpg",
    "imageCardGrid/card8.jpg",
    "imageCardGrid/card9.jpg",
    "imageCardGrid/card10.jpg",
    "imageCardGrid/card11.jpg",
    "imageCardGrid/card12.jpg",
    "imageCardGrid/card.jpg",
    "imageCardGrid/card2.jpg",
    "imageCardGrid/card3.jpg",
    "imageCardGrid/card4.jpg",
    "imageCardGrid/card5.jpg",
    "imageCardGrid/card6.jpg",
    "imageCardGrid/card7.jpg",
    "imageCardGrid/card8.jpg",
    "imageCardGrid/card9.jpg",
    "imageCardGrid/card10.jpg",
    "imageCardGrid/card11.jpg",
    "imageCardGrid/card12.jpg"
]

if (imagesSlider.length > 1) {
    $('.fig-wrap').children('button').css('opacity', '1');
    $('<div class="thumbnail-slider"><button class="arrow-left hidden sliderNavLeft"></button><button class="arrow-right sliderNavRight"></button><div class="slider-wrap"><ul id="slider" class="slider"></ul></div></div>').appendTo('.thumbnails');
    for (var i = 0; i < imagesSlider.length; i++){
        $('<li class="thumbnail"><div class="overlay-border"></div><img class="thumbnail-img" src="' + BASE_IMAGE_URL + imagesSlider[i] + '" alt="' + i + '"></li>').appendTo("ul.slider");
    }
    $('<div class="custom-scrollbar"><div id="slider-bar" class="bar ui-slider"><span id="scroll" class="scroll"></span></div></div>').appendTo('.thumbnails');
    $('span.scroll').css('left', '0%');
}

var cur // tracks current image being viewed
if (imagesSlider) {
    // set the image to be the src
    window.document.getElementById('carousel-img').src = '' + BASE_IMAGE_URL + imagesSlider[0] + ''
    window.document.getElementById('carousel-controls').children[0].href = '' + BASE_IMAGE_URL + imagesSlider[0] + ''
    window.document.getElementById('carousel-controls').children[1].href = '' + BASE_IMAGE_URL + imagesSlider[0] + ''
    window.document.getElementById('modal-image').src = '' + BASE_IMAGE_URL + imagesSlider[0] + ''
    $('.thumbnail').first().addClass('selected');
    cur = 0
}

// ~~~~~~~~~~~~~~~~ //
// END INIT SECTION //
// ~~~~~~~~~~~~~~~~ //

// ~~~~~~~~~~~~~~~ //
// FIG NAV SECTION //
// ~~~~~~~~~~~~~~~ //

// this allows the user to click the left/right buttons in the figure
//  and scroll to the next/previous image in the list of thumbnails

$('.fig-wrap .arrow-left').click(function () {
    cur = cur - 1
    setImage ()
});

$('.fig-wrap .arrow-right').click(function () {
    cur = cur + 1
    setImage ()
});

// modal arrow nav
$('.modal-wrap .arrow-left').click(function (e) {
    e.stopPropagation();
    $('.fig-wrap .arrow-left').trigger('click');
});

$('.modal-wrap .arrow-right').click(function (e) {
    e.stopPropagation();
    $('.fig-wrap .arrow-right').trigger('click');
});

var thumbs = window.document.getElementsByClassName('thumbnail')
function setImage () {
    if (cur < 0) {
        cur = imagesSlider.length - 1;
    } else if (cur > imagesSlider.length - 1) {
        cur = 0;
    }
    for (var i = 0; i < thumbs.length; i++) {
        thumbs[i].classList.remove('selected');
    }
    window.document.getElementById('carousel-img').src = '' + BASE_IMAGE_URL + imagesSlider[cur] + '';
    window.document.getElementById('modal-image').src = '' + BASE_IMAGE_URL + imagesSlider[cur] + '';
    thumbs[cur].classList.add('selected');
}

// ~~~~~~~~~~~ //
// END FIG NAV //
// ~~~~~~~~~~~ //

// ~~~~~~~~~~~~~~~~~ //
// THUMBNAIL CONTROL //
// ~~~~~~~~~~~~~~~~~ //

var selectedThumb;
var viewWidth;
var maxScroll;
var currentThumbScrollPos;
var scrollWidth; // can't set this value here as it will return the value prior to the init steps of this script
var scrollVal;
$('.thumbnail').click(function (e) {
    e.stopPropagation();

    $('.thumbnail').removeClass('selected');
    $(this).addClass('selected'); // styles updated

    window.document.getElementById('carousel-img').src = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('carousel-controls').children[0].href = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('carousel-controls').children[1].href = $(this).children('.thumbnail-img').prop('src');
    window.document.getElementById('modal-image').src = $(this).children('.thumbnail-img').prop('src'); // fig img updated

    cur = parseInt($(this).children('.thumbnail-img').prop('alt')); // tell script where we are
    currentThumbScrollPos = window.document.getElementById('slider').scrollLeft; // current thumbnail scroll position

    // scroll selected thumbnail into view
    scrollVal = $(this).offset().left - $('.slider').offset().left + currentThumbScrollPos;
    scroll();
});

$('.sliderNavRight').click(function (event) {
    event.preventDefault();
    currentThumbScrollPos = window.document.getElementById('slider').scrollLeft; // current thumbnail scroll position
    scrollVal = currentThumbScrollPos + 250;
    scroll();
});

$('.sliderNavLeft').click(function (event) {
    event.preventDefault();
    currentThumbScrollPos = window.document.getElementById('slider').scrollLeft; // current thumbnail scroll position
    scrollVal = currentThumbScrollPos - 250;
    scroll();
});

function scroll() {
    scrollWidth = window.document.getElementById('slider').scrollWidth;    // max scroll width thumbs (static value)
    viewWidth = window.document.getElementById('slider').offsetWidth;     // view width thumbs
    maxScroll = scrollWidth - viewWidth;                                // true scroll distance = maxScroll
    thumbScroll_Percent = (scrollVal/maxScroll) * 100;
    if (scrollVal > maxScroll) {
        scrollVal = maxScroll;
    } else if (scrollVal < 0) {
        scrollVal = 0;
    }
    $('.slider').animate({
        scrollLeft: scrollVal
    }, 80);
    setTimeout(function(){
        updateScroller();
    }, 90)
}

function updateScroller() {
    if (thumbScroll_Percent > 100) {
        thumbScroll_Percent = 100;
    } else if (thumbScroll_Percent < 0) {
        thumbScroll_Percent = 0;
    }
    $('.scroll').animate({
        left: "" + thumbScroll_Percent + "%"
    }, 80);
}


// on slide, update scroller
// issue is, when arrow is clicked, or thumbnail is selected, the slider slides and triggers this function
//  this is an issue, because I can't tell the scroller to scroll when the slider is scrolled
//  I can't tell the scroller to scroll when the slider is scrolled because another function handles the scroller scrolling
//  if I make this .scroll function control the slider, there will then be issues with the scroller scrolling the slider because
//      user moves scroller
//      scroller scrolls slider
//      scrolling slider triggers .scroll which then controls the scroller and undoes the scrolling the user has done
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
   /* setTimeout(function(){
        // after timeout, verify that scrollPos of thumbs = scrollPos of scroller

        // get scrollPos of thumbs, convert to %
        scrollWidth = window.document.getElementById('slider').scrollWidth      // max scroll width thumbs (static value)
        viewWidth = window.document.getElementById('slider').offsetWidth        // view width thumbs
        maxScroll = scrollWidth - viewWidth                                     // true scroll distance = maxScroll
        currentThumbScrollPos = window.document.getElementById('slider').scrollLeft // current thumbnail scroll position
        thumbScroll_Percent = Math.round((currentThumbScrollPos/maxScroll) * 100)
        // get scrollPos of scroller, convert to %
        var check = Math.round((($('.scroll').offset().left - $('.bar').offset().left) / 1000) * 100)
        // compare
        if (check != thumbScroll_Percent) {
            // address as needed
            $('.scroll').animate({
                left: thumbScroll_Percent + '%'
            }, 200);
        }
    }, 1000*1)*/
});

// ~~~~~~~~~~~~~~~~~~~~~ //
// END THUMBNAIL CONTROL //
// ~~~~~~~~~~~~~~~~~~~~~ //

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

function dragScroll(scr) {
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
        var scrollPos_Percent = (parseInt(scr.offsetLeft - pos1)/barWidth) * 100;
        if (scrollPos_Percent < 0) {
            scrollPos_Percent = 0;
        } else if (scrollPos_Percent > 100) {
            scrollPos_Percent = 100;
        }
        scr.style.left = (scrollPos_Percent) + "%";
        // unpack %  --> convert it to px value
        // % = (val/maxVal)*100
        // val = (%/100)*maxVal
        scrollWidth = window.document.getElementById('slider').scrollWidth;      // max scroll width thumbs (static value)
        viewWidth = window.document.getElementById('slider').offsetWidth;        // view width thumbs
        maxScroll = scrollWidth - viewWidth ;                                    // true scroll distance = maxScroll
        scrollVal = (scrollPos_Percent/100) * maxScroll;
        $('.slider').animate({
            scrollLeft: scrollVal
        }, 10);
    }

    function closeDragElement() {
        // stop moving when mouse button is released
        window.document.onmouseup = null;
        window.document.onmousemove = null;
    }
}
