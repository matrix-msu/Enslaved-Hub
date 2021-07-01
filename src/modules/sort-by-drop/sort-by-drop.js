

$(document).click(function() { // close things with clicked-off
    $('span.sort-by').next().removeClass('show');
    $('span.sort-by').find("img:first").removeClass('show');
});

$('div.container.main').click(function(e) {
    e.stopPropagation();
})

$("span.align-center").click(function(e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).next().toggleClass('show');
});

$("ul.results-per-page li").click(function(e) { // set the per-page value
    e.stopPropagation();
    num_of_results = $(this).find('span:first').html();
    localStorage.setItem('display_amount', num_of_results);
    location.reload();
});

var timer
$("span.view-toggle").mouseenter(function() { // show tooltips on hover
    var that = this
    timer = setTimeout(function() {
        $('span p.tooltip').removeClass('hovered');
        $(that).find("p.tooltip").addClass('hovered');
    }, 750);
}).mouseleave(function() {
    $('span p.tooltip').removeClass('hovered');
    clearTimeout(timer)
});

var view
var result
result_array = []
result_array.length = 11
$("span.grid-view").click(function gridView(e) { // grid view
    e.stopPropagation()
    if (cards === false) {
        $('tbody > tr').remove();
        $("#search-result-configure-download-row").hide();
        $("#search-result-table").hide();
        $('span.view-toggle img.hide').show();
        $('span.view-toggle img.show').hide();
        $('<div class="column"><div class="cardwrap"><ul class="row"></ul></div></div>').appendTo("div#search-result-wrap");
        result = parseInt(localStorage.getItem('display_amount'), 10)
        if (result) {
            result_array.length = result
        }
        $.each(result_array, function() {
            $('<li><a><div class="container cards"><p class="card-title">Person Name or Narrative Title</p><p><span>Gender</span>: Male</p><p><span>Born</span>: fl.1845 Virginia, United States</p><p><span>Occupation</span>: Fugitive Slave, Slave Narrative, Occupation..</p><a class="card-learn-more">View Narrative</a></div></a></li>').appendTo("ul.row");
        });
        cards = true
        view = 'grid'
        window.localStorage.setItem('cards', cards)
        window.localStorage.setItem('view', view)
        $('div.column').css('padding', '0', 'margin-top', '-30px', 'margin-bottom', '-15px');
    }
});

$("span.table-view").click(function tableView(e) { // table view
    e.stopPropagation()
    if (cards === true) {
        cards = false
        window.localStorage.setItem('cards', cards)
        $('div.column').remove();
        $('div#search-result-table').show();
        $('span.view-toggle img.hide').hide();
        $('span.view-toggle img.show').show();
        $(this).addClass("show");
        $("span.grid-view").removeClass("show");
        $("#search-result-configure-download-row").show();
        $('table').css('width', '', 'margin', '');
        var view = 'table'
        window.localStorage.setItem('view', view)
        result = parseInt(localStorage.getItem('display_amount'), 10)
        if (result) {
            result_array.length = result
        }
        $.each(result_array, function() {
            $(
                '<tr class="tr"><td class="name td-name"><span>Name LastName</span></td><td class="gender"><p><span class="first">Gender: </span>Gndr</p></td><td class="age"><p><span class="first">Age: </span>##</p></td><td class="occupation"><p><span class="first">Occupation: </span>Fugitive Slave</p></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><a href="#">View Narrative</a></td></tr>'
            ).appendTo('tbody');
        });
    }
});
