$(document).ready(function(){
    var page = 1;
    var _pages = $('span.pagi-last').html();
    var pages = parseInt(_pages);
    // var pages = 5;
    var num = document.getElementsByClassName('num')
    console.log('js pages ',_pages);
    console.log('js num ', num);
    // if (pages == 1) { // no pagination if only 1 page
    //     $('span.dotsLeft').hide();
    // }
    if (pages < 2) { // sets pagination on page load
        // console.log('HIHIHIH');
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span.dotsLeft').hide();
        $('span.dotsRight').hide();
        $('span.pagi-first').hide();
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.pagi-last').hide();
    }
    else {
        $('span.dotsLeft').hide();
        $('span.dotsRight').hide();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.pagi-last').hide();
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.one').show();
        $('span.one').html(2);

        if (pages > 2) {
            $('span.two').show();
            $('span.two').html(3);
        }
        if (pages > 3) {
            $('span.three').show();
            $('span.three').html(4);
        }
        if (pages > 4) {
            $('span.four').show();
            $('span.four').html(5);
        }
        if (pages > 5) {
            $('span.five').show();
            $('span.five').html(6);
        }
        if (pages > 6) {
            $('span.dotsRight').show();
            $('span.pagi-last').show();
        }


        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');
    }
    $('span#pagiRight').click(function(e) {
        // e.stopPropagation();
        if (page === pages) {
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
            $('.num.active').nextAll('.num').first().click();
        }
    });
    $('span.dotsRight').click(function(e) {
        // e.stopPropagation();
        if (pages - page < 10) {
            return;
        } else {
            page = page + 10;
            paginate();
            $('.num.active').click();
        }
    });
    $('span#pagiLeft').click(function(e) {
        // e.stopPropagation();
        if (page === 1) {
            $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
            $('.num.active').prevAll('.num').first().click();
        }
    });
    $('span.dotsLeft').click(function(e) {
        // e.stopPropagation();
        if (page - 10 < 0) { // this check, and the other +10 check may not be needed
            return; // since the dots are hidden at instances when they
        } else { // would normally break the pagination
            page = page - 10;
            paginate();
            $('.num.active').click();
        }
    });
    $('span.num').click(function(e) {
        // e.stopPropagation();
        $('#close-bar').click();
        $('.record:not(:first)').remove();
        page = $(this).html(); // set page
        page = parseInt(page);
        paginate();
    });

    function paginate() {
        console.log(' ');
        console.log('page', page);

        if (page === 1) {

            $('span.dotsLeft').hide();
            // $('span.dotsRight').hide();
            $('span.pagi-first').show();
            $('span.pagi-first').html(1);
            $('span.one').hide();
            $('span.two').hide();
            $('span.three').hide();
            $('span.four').hide();
            $('span.five').hide();
            $('span.one').show();
            $('span.one').html(2);

            if (pages > 2) {
                $('span.two').show();
                $('span.two').html(3);
            }
            if (pages > 3) {
                $('span.three').show();
                $('span.three').html(4);
            }
            if (pages > 4) {
                $('span.four').show();
                $('span.four').html(5);
            }
            if (pages > 5) {
                $('span.five').show();
                $('span.five').html(6);
            }
            if (pages > 6) {
                $('span.dotsRight').show();
                $('span.pagi-last').show();
            }

            $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.num').removeClass('active');
            $('span.pagi-first').addClass('active');

        } else if (page >= 2 && page <= 5) {
            $('span.dotsLeft').hide();
            // $('span.dotsRight').hide();
            $('span.pagi-first').show();
            $('span.pagi-first').html(1);
            $('span.one').hide();
            $('span.two').hide();
            $('span.three').hide();
            $('span.four').hide();
            $('span.five').hide();
            $('span.one').show();
            $('span.one').html(2);

            if (pages > 2) {
                $('span.two').show();
                $('span.two').html(3);
            }
            if (pages > 3) {
                $('span.three').show();
                $('span.three').html(4);
            }
            if (pages > 4) {
                $('span.four').show();
                $('span.four').html(5);
            }
            if (pages > 5) {
                $('span.five').show();
                $('span.five').html(6);
            }
            if (pages > 6) {
                $('span.dotsRight').show();
                $('span.pagi-last').show();
            }
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.num').removeClass('active');
            $('#pagination-module').find('.num').eq(page - 1).addClass('active');

        // } else if ( page === 6 ){
        //     $('span#pagiLeft').css('opacity', '', 'cursor', '');
        //     $('span#pagiRight').css('opacity', '', 'cursor', '');
        //     $('span.pagi-first').show();
        //     $('span.dotsLeft').show();
        //     $('span.dotsRight').show();
        //     $('span.one').html(4);
        //     $('span.two').html(5);
        //     $('span.three').html(6);
        //     $('span.four').html(7);
        //     $('span.five').html(8);
        //     $('span.num').removeClass('active');
        //     $('span.three').addClass('active');
        } else if (pages - page >= 5) {
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.pagi-first').show();
            $('span.dotsLeft').show();
            $('span.dotsRight').show();
            $('span.one').html(page - 2);
            $('span.two').html(page - 1);
            $('span.three').html(page);
            $('span.four').html(page + 1);
            $('span.five').html(page + 2);
            $('span.num').removeClass('active');
            $('span.three').addClass('active');
        } else if (pages - page < 6 && page != pages) {
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.one').html(pages - 5);
            $('span.two').html(pages - 4);
            $('span.three').html(pages - 3);
            $('span.four').html(pages - 2);
            $('span.five').html(pages - 1);
            $('span.num').removeClass('active');
            $('#pagination-module').find('.num').eq(6 - (pages - page)).addClass('active');
            $('span.dotsRight').hide();
        } else if (page === pages) {
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span.dotsRight').hide();
            $('span.dotsLeft').show();
            $('span.one').html(page - 5);
            $('span.two').html(page - 4);
            $('span.three').html(page - 3);
            $('span.four').html(page - 2);
            $('span.five').html(page - 1);
            $('span.num').removeClass('active');
            $('span.pagi-last').addClass('active');
        }
    }
});

//
// if (page === 1) {
//     $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
//     $('span#pagiRight').css('opacity', '', 'cursor', '');
//     $('span.num').removeClass('active');
//     $('span.pagi-first').addClass('active');
// }
// else if (page === pages) {
//     $('span#pagiLeft').css('opacity', '', 'cursor', '');
//     $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
//     $('span.num').removeClass('active');
//     $('span.four').addClass('active');
//     // $('span.pagi-last').addClass('active');
// }
// else {
//     $('span#pagiLeft').css('opacity', '', 'cursor', '');
//     $('span#pagiRight').css('opacity', '', 'cursor', '');
//     $('span.num').removeClass('active');
//     $('#pagination-module').find('.num').eq(page - 1).addClass('active');
// }
// }