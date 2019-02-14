// ~~~~~~~~~~ //
// PAGINATION //
// ~~~~~~~~~~ //

var urlParams = {
    'page': '',
    'count': '',
    'field': '',
    'direction': ''
};

function getUrlParameter(sParam) {
    let sPageURL = decodeURIComponent(window.location.search.substring(1));
    let sURLVariables = sPageURL.split('&');
    let sParameterName;

    for (let i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function newLocation() {
    var params = [];

    for (var param in urlParams) {
        if (urlParams[param] && urlParams[param] != '') {
            params.push(param + '=' + urlParams[param]);
        }
    }

    return window.location.href.split(/[?#]/)[0] + "?" + params.join("&");
}

function updateUrlParams() {
    var fieldUrlParam = getUrlParameter('field');
    var directionUrlParam = getUrlParameter('direction');
    var perPageUrlParam = getUrlParameter('count');
    var pageUrlParam = getUrlParameter('page');

    if (fieldUrlParam && fieldUrlParam != "") {
        urlParams['field'] = fieldUrlParam;
    } else {
        urlParams['field'] = '';
    }

    if (directionUrlParam && directionUrlParam != "") {
        urlParams['direction'] = directionUrlParam;
    } else {
        urlParams['direction'] = '';
    }

    if (perPageUrlParam && perPageUrlParam != "") {
        urlParams['count'] = perPageUrlParam;
    } else {
        urlParams['count'] = '';
    }

    if (pageUrlParam && pageUrlParam != "") {
        urlParams['page'] = pageUrlParam;
    } else {
        urlParams['page'] = '';
    }
}

function setPage(newPage) {
    updateUrlParams();
    urlParams['page'] = newPage;

    window.location = newLocation();
}

function scrollToAll() {
    updateUrlParams();

    if (urlParams['page'] != '' ||
            urlParams['count'] != '' ||
            urlParams['field'] != '' ||
            urlParams['direction'] != '') {
        $('html, body').scrollTop($("#all-header").offset().top - 60);
    }
}

function initializeSort() {
    var $sortOptions = $('.sort-option');

    $sortOptions.click(function() {
        var $option = $(this);
        var field = $option.data('field');
        var direction = $option.data('direction');

        updateUrlParams();
        urlParams['page'] = '';
        urlParams['field'] = field;
        urlParams['direction'] = direction;

        window.location = newLocation();
    });
}

function initializeCount() {
    var $countOptions = $('.count-option');

    $countOptions.click(function() {
        var $option = $(this);
        var count = $option.data('count');

        updateUrlParams();
        urlParams['page'] = '';
        urlParams['count'] = count;

        window.location = newLocation();
    })
}

function initializePagination() {
    var $pagContainer = $('.pagination-container');
    var $pagSelect = $pagContainer.find('.page-select li');
    var $next = $pagContainer.find('.pagination-next');
    var $prev = $pagContainer.find('.pagination-prev');

    $pagSelect.click(function() {
        var $sel = $(this);
        var newPage = $sel.data('page');

        if (newPage && newPage != "") {
            setPage(newPage);
        }
    });

    $next.click(function() {
        var newPage = $(this).data('page');

        if (newPage && newPage != "") {
            setPage(newPage);
        }
    });

    $prev.click(function() {
        var newPage = $(this).data('page');

        if (newPage && newPage != "") {
            setPage(newPage);
        }
    });
}

scrollToAll()
initializeSort();
initializeCount();
initializePagination();
