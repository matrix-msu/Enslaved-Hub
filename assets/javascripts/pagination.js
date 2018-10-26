// ~~~~~~~~~~ //
// PAGINATION //
// ~~~~~~~~~~ //

var page = 1
var _pages = $('span.pagi-last').html();
var pages = parseInt(_pages);
var count = 8
pages = Math.round(pages/count);
console.log(pages);
var num = document.getElementsByClassName('num')

if (+page === 1) { // sets pagination on page load
    $('span.pagi-last').html(pages);
    $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
    $('span.dotsLeft').hide();
    $('span.pagi-first').addClass('active');
    $('span.one').html(2);
    $('span.two').html(3);
    $('span.three').html(4);
    $('span.four').html(5);
    $('span.five').html(6);
}
$('span#pagiRight').click(function (e) {
    e.stopPropagation();
    if (+page === +pages) {
        $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
    } else {
       page = +page + 1;
       paginate();
    }
});
$('span.dotsRight').click(function (e) {
    e.stopPropagation();
    if (+page + 10 > pages) {
        page = +pages;
        paginate();
    } else {
       page = +page + 10;
       paginate();
    }
});
$('span#pagiLeft').click(function (e) {
    e.stopPropagation();
    if (+page === 1) {
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
    } else {
       page = +page - 1;
       paginate();
    }
});
$('span.dotsLeft').click(function (e) {
    e.stopPropagation();
    if (+page - 10 < 0) {
        page = 1;
        paginate();
    } else {
       page = +page - 10;
       paginate();
    }
});
$('span.num').click(function (e) {
    e.stopPropagation();
    page = $(this).html(); // set page
    paginate();
});

function paginate () {
    if (+page === 1) {
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsLeft').hide();
        $('span.dotsRight').show();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.one').html(2);
        $('span.two').html(3);
        $('span.three').html(4);
        $('span.four').html(5);
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');
    } else if (+page > 1 && +page <= 10) {
        $('span#pagiLeft').css('opacity','','cursor','');
        $('span.dotsLeft').show();
        $('span.pagi-first').show();
        $('span.one').html(page);
        $('span.two').html(+page + 1);
        $('span.three').html(+page + 2);
        $('span.four').html(+page + 3);
        $('span.num').removeClass('active');
        $('span.one').addClass('active');
    } else if (+pages - +page > 10) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.pagi-first').show();
        $('span.dotsLeft').show();
        $('span.dotsRight').show();
        $('span.one').html(+page - 1);
        $('span.two').html(page);
        $('span.three').html(+page + 1);
        $('span.four').html(+page + 2);
        $('span.num').removeClass('active');
        $('span.two').addClass('active');
    } else if (+pages - +page === 10) {
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsRight').show();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 2);
        $('span.two').html(+page - 1);
        $('span.three').html(page);
        $('span.four').html(+page + 1);
        $('span.num').removeClass('active');
        $('span.three').addClass('active');
    } else if (+pages - +page === 9) {
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsRight').show();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 3);
        $('span.two').html(+page - 2);
        $('span.three').html(page);
        $('span.four').html(+page + 1);
        $('span.num').removeClass('active');
        $('span.three').addClass('active');
    } else if (+pages - +page === 8) {
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsRight').show();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 3);
        $('span.two').html(+page - 2);
        $('span.three').html(+page - 1);
        $('span.four').html(page);
        $('span.num').removeClass('active');
        $('span.four').addClass('active');
    } else if (+pages - +page < 8 && +page != +pages) {
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsRight').show();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 3);
        $('span.two').html(+page - 2);
        $('span.three').html(+page - 1);
        $('span.four').html(page);
        $('span.num').removeClass('active');
        $('span.four').addClass('active');
    } else if (+page === +pages) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span.dotsRight').hide();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 4);
        $('span.two').html(page - 3);
        $('span.three').html(+page - 2);
        $('span.four').html(+page - 1);
        $('span.num').removeClass('active');
        $('span.pagi-last').addClass('active');
    }
    $.ajax({
        url: BASE_URL+'ajax/stories.php',
        method: 'POST',
        data: {
            page: page,
            count: count
        },
        success: function(data){
            $('#AllStoriesContainer').html("");
            $('div#all-story-container').remove();
            $('<div id="allStories" class="container column storycard"><ul class="row" id = AllStoriesContainer></ul></div>').appendTo("div#all-story-container");
            data = JSON.parse(data);
            for (var kid in data['records'][0]){
                title =  (data['records'][0][kid]['Title'].value);
                var html = '<li><a href="'+BASE_URL+'fullstory?kid='+kid+'">';
                html += '<div class="container cards">';
                html += '<p class="card-title">'+title+'</p>';
                html += '<h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>';
                html += '</div></a></li>';
                $("#AllStoriesContainer").append(html);
            }
        },
    })
}