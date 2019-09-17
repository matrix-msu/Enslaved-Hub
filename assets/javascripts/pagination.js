///******************************************************************* */
/// PAGINATION
///******************************************************************* */

//Global variables that can be used in other js files
var page = 1;
var pages = 1;

/**
 * Sets the pagination HTML to it's initial numbers and hides unnecessary numbers
 *
 * \param total : Total number of cards
 * \param limit : Limit on the number of cards per page
 * \param offset : Offset from the first card (with 0 being the first card)
*/
function setPagination(total, limit, offset) {
    pages = Math.ceil(total / limit);
    page = Math.ceil(offset / limit) + 1;

    $('span.pagi-last').html(pages); //last pagination number to number of pages

    if (pages < 2) { // sets pagination on page load
        $('div.pagi-left').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span.dotsLeft').hide();
        $('span.dotsRight').hide();
        $('span.pagi-first').hide();
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.pagi-last').hide();
    } else {
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


        $('div.pagi-left').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('div.pagi-right').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');
    }
    paginate();
}

/**
 * Called either after setting the initial pagination HTML or after a change in the page number
 * has occurred. When the current page number is changed it goes through and determines
 * the new values of the numbers that aren't active and if the dots and next page buttons should
 * be on or off.
*/
function paginate() {
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
        $('span.one').hide();

        if (pages > 1) {
            $('span.one').show();
            $('span.one').html(2);
        }
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

        if(page == pages){
            $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
        }else{
            $('div.pagi-right').css('opacity', '', 'cursor', '');
        }
        $('div.pagi-left').css('opacity', '0.25', 'cursor', 'not-allowed');
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

        if(page == pages){
            $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
        }else{
            $('div.pagi-right').css('opacity', '', 'cursor', '');
        }
        $('div.pagi-left').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('#pagination').find('.num').eq(page - 1).addClass('active');
    } else if (pages - page >= 5) {
        $('div.pagi-left').css('opacity', '', 'cursor', '');
        $('div.pagi-right').css('opacity', '', 'cursor', '');
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
        $('div.pagi-left').css('opacity', '', 'cursor', '');
        $('div.pagi-right').css('opacity', '', 'cursor', '');
        $('span.one').html(pages - 5);
        $('span.two').html(pages - 4);
        $('span.three').html(pages - 3);
        $('span.four').html(pages - 2);
        $('span.five').html(pages - 1);
        $('span.num').removeClass('active');
        $('#pagination').find('.num').eq(6 - (pages - page)).addClass('active');
        $('span.dotsLeft').show();
        $('span.dotsRight').hide();
    } else if (page === pages) {
        if(page == 6){
            //Special case where there are 6 pages and the active is the last page

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
            $('div.pagi-left').css('opacity', '', 'cursor', '');
            $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span.num').removeClass('active');
            $('#pagination').find('.num.five').addClass('active');
        }
        else{
            //Last page and greater than 6
            $('div.pagi-left').css('opacity', '', 'cursor', '');
            $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
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
    //Set hidden input to value of the current page after pagination has completed
    //Allows for a listener in the other files that checks for a change in the value of the input
    if($('#pagination .current-page').val() != page){
        $('#pagination .current-page').val(page);
        $('#pagination .current-page').change(); //manually fire the change event
    }
}

//Document load set event handlers
$(document).ready(function(){

    ///******************************************************************* */
    /// PAGINATION HANDLERS
    ///******************************************************************* */

    $('div.pagi-right').click(function(e) {
        // e.stopPropagation();
        if (page === pages) {
            $('div.pagi-right').css('opacity', '0.25', 'cursor', 'not-allowed');
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
    $('div.pagi-left').click(function(e) {
        // e.stopPropagation();
        if (page === 1) {
            $('div.pagi-left').css('opacity', '0.25', 'cursor', 'not-allowed');
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
        page = $(this).html(); // set page
        page = parseInt(page);
        paginate();
    });
});
