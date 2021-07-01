
media_record = []
media_record.length = 12
images = [
    "nature-placeholder-1.jpg",
    "nature-placeholder-2.jpg",
    "nature-placeholder-3.jpg",
    "nature-placeholder-4.jpg",
    "nature-placeholder-1.jpg",
    "nature-placeholder-2.jpg",
    "nature-placeholder-3.jpg",
    "nature-placeholder-4.jpg",
    "nature-placeholder-1.jpg",
    "nature-placeholder-2.jpg",
    "nature-placeholder-3.jpg",
    "nature-placeholder-4.jpg"
]

$(document).ready(function() {
    $('<div class="column"><div class="cardwrap"><ul class="row"></ul></div></div>').appendTo("main.media-records");
    for (var i = 0; i < media_record.length; i++) {
        $('<li><a><img src=' + BASE_URL + 'assets/images/' + images[i] + ' alt="media record image"><div class="container cards"><p class="card-title">Record Title goes here like this</p><p><span>Metadata Title:</span> Metadata Content would go here</p><p><span>Metadata Title:</span> Metadata Content would go here</p></div></a></li>').appendTo("ul.row");
    }
});
