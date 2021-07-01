var images = {
    1: {
        'image': 'assets/images/imageCardGrid2/card.jpg',
        'title': 'Title',
        'description': 'Slide Description. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'link': 'Link to Someplace',
        'linkLocation': '#'
    },
    2: {
        'image': 'assets/images/imageCardGrid2/card2.jpg',
        'title': 'Image 2',
        'description': 'This is the second of four images that were uploaded to test this page',
        'link': 'This is a new link!',
        'linkLocation': 'https://i.imgur.com/g3D5jNz.jpg'
    },
    3: {
        'image': 'assets/images/imageCardGrid2/card3.jpg',
        'title': 'This is the third image',
        'description': 'This is a different (shorter) description!',
        'link': 'Another new link!',
        'linkLocation': 'https://i.imgur.com/0XHcPko.jpg'
    },
    4: {
        'image': 'assets/images/imageCardGrid2/card4.jpg',
        'title': 'Last Image',
        'description': 'This is the last of the images uploaded for this demonstration!',
        'link': 'Another newer link!!!',
        'linkLocation': 'https://i.imgur.com/Cxagv.jpg'
    }
}
var dots = window.document.getElementsByClassName('dot')
var img = 1
var curDot = 0

for (var i=0; i < dots.length; i++) {
    dots[i].addEventListener('click', slideshowDotControl(i))
}

function slideshowDotControl (i) { // i represents which dot was clicked (0 -> 3)
    return function () {
        console.log('' + i)
        if (i > curDot) {
            curDot = i
            img = i + 1 // images[0] is undefined, images[1] is the first valid value
            setSlideshowImageLeft ()
        } else {
            curDot = i
            img = i + 1 // images[0] is undefined, images[1] is the first valid value
            setSlideshowImageRight ()
        }
    }
}

$('.arrow-left').click(function () {
    curDot = curDot - 1
    img = img - 1
    setSlideshowImageRight ()
});

$('.arrow-right').click(function () {
    curDot = curDot + 1
    img = img + 1
    setSlideshowImageLeft ()
});

function setSlideshowImageLeft () {
    if (img < 1) {
        img = 4
    } else if (img > 4) {
        img = 1
    }
    $('<div class="carousel-title new"><div class="title"><h1>' + images[img].title + '</h1></div><p class="description">' + images[img].description + '</p><a class="carousel-link" href="' + images[img].linkLocation + '" target="_blank">' + images[img].link + '</a></div>').appendTo('.image-carousel-fig');
    $('<img class="carousel-display new" src="' + images[img].image + '" alt="' + images[img].description + '">').appendTo('.image-carousel-fig');
    $('.new').css('left', '100%');
    transitionSlideshowRight ()
}

function setSlideshowImageRight () {
	console.log("here");
    if (img < 1) {
        img = 4
    } else if (img > 4) {
        img = 1
    }

    $('<div class="carousel-title new"><div class="title"><h1>' + images[img].title + '</h1></div><p class="description">' + images[img].description + '</p><a class="carousel-link" href="' + images[img].linkLocation + '" target="_blank">' + images[img].link + '</a></div>').appendTo('.image-carousel-fig');
    $('<img class="carousel-display new" src="' + images[img].image + '" alt="' + images[img].description + '">').appendTo('.image-carousel-fig');
    $('.new').css('left', '-150%');
    transitionSlideshoLeft ()
}

function transitionSlideshowRight () {
    $('.old').css('left', '-150%');
    setTimeout(function(){
      $('.new').css('left', '0%');
      setTimeout(function(){
        $('.old').remove();
        $('.new').addClass('old');
        $('.old').removeClass('new');
        setSlideshoDots ()
      }, 400)
    }, 10);
}

function transitionSlideshowLeft () {
    $('.old').css('left', '100%');
    setTimeout(function(){
      $('.new').css('left', '0%');
      setTimeout(function(){
        $('.old').remove();
        $('.new').addClass('old');
        $('.old').removeClass('new');
        setSlideshoDots ()
      }, 400)
    }, 10);
}

function setSlideshowDots () {
    $('.dot').removeClass('active');
    if (curDot < 0) {
        curDot = 3
    } else if (curDot > 3) {
        curDot = 0
    }
    dots[curDot].classList.add('active')
}
