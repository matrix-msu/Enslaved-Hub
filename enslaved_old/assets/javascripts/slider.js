// result_array = ["ibrahima.jpg","ibrahima2.jpg","ibrahima3.jpg", "ibra4.jpeg"]; //image names (add however many you need)
// console.log(captions);
$.each(result_array,function ( index, value ) {
    $('<img class="mySlides fade" src="'+value+'">').appendTo("div.slider"); //add images to the slider
    // $('<p class="key-events-text">Cation goes here</p>').appendTo("div.slider"); //add images to the slider
    if (result_array.length > 1){
        $('<span class="dot" onclick="currentSlide('+(index+1)+')"></span>').appendTo("div.dotwrap");
    } else{
        $('div.image-pagination').css('display','none');
    }
});

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  if(slideIndex <= captions.length){$(".caption-text").text(captions[slideIndex-1])}
  // console.log(slideIndex);
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  
}

/*
//Tab Mobile Slider
$(function() {
    if ($(window).width() <= 400) {
        var $draggable = $(".full-selector").draggabilly({ axis: "x"});
        var origPos;
        $draggable.on( 'pointerDown', function() {
            origPos = ($(this).css('left')).slice(0,-2);
            var origPosi = parseInt(origPos); //either 40 or 80
        });
        $draggable.on( 'dragEnd', function() {
            var leftPos = ($(this).css('left')).slice(0,-2);
            var leftPosi = parseInt(leftPos);
            if (leftPos > 60){
                $('.selector-story-text').css('font-size','24px');
                $('.selector-story-text').css('padding-bottom','20px');
                $('.selector-map-text').css('font-size','18px');
                $(this).css('left','100px');
            } else if (leftPos < 80) {
                $('.selector-map-text').css('font-size','24px');
                $('.selector-story-text').css('font-size','18px');
                $('.selector-story-text').css('padding-bottom','26px');
                $(this).css('left','20px');
            }
        });
    }
});
*/
