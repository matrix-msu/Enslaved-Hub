$(document).ready(function(){

    var planes = [
		["7C6B07",-40.99497,174.50808],
		["7C6B38",-41.30269,173.63696],
		["7C6CA1",-41.49413,173.5421],
		["7C6CA2",-40.98585,174.50659],
		["C81D9D",-40.93163,173.81726],
		["C82009",-41.5183,174.78081],
		["C82081",-41.42079,173.5783],
		["C820AB",-42.08414,173.96632],
		["C820B6",-41.51285,173.53274]
        ];

    var map = L.map('map-large').setView([-41.3058, 174.82082], 8);
    map.scrollWheelZoom.disable();
    map.on('focus', () => { map.scrollWheelZoom.enable(); });
    map.on('blur', () => { map.scrollWheelZoom.disable(); });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    var connection_icons = '<div class="connections"><div class="card-icons"><img src="./assets/images/Person-light.svg"><span>10</span></div><div class="card-icons"><img src="./assets/images/Event-light.svg"><span>10</span></div><div class="card-icons"><img src="./assets/images/Source-light.svg"><span>10</span></div><div class="card-icons"><img src="./assets/images/Project-light.svg"><span>10</span></div></div>';
    // create popup contents
    var customPopup = '<h2>Place Name</h2>'+connection_icons+'<p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut …</p><div class="view-record"><p>View Place Record<div class="view-arrow"></div></p></div>';

    // specify popup options 
    var customOptions =
        {
        'maxWidth': '250',
        'className' : 'custom'
        }

    // Change the marker icon
    var orangeIcon = L.icon({
        iconUrl: 'assets/images/MapMarker.svg',
        //shadowUrl: 'leaf-shadow.png',
    
        iconSize:     [37, 37], // size of the icon
        //shadowSize:   [50, 64], // size of the shadow
        //iconAnchor:   [22, 80], // point of the icon which will correspond to marker's location
        //shadowAnchor: [4, 62],  // the same for the shadow
        //popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    //Create Markers
    for (var i = 0; i < planes.length; i++) {
        marker = new L.marker([planes[i][1],planes[i][2]], {icon: orangeIcon})
            .bindPopup(customPopup, customOptions)
            .addTo(map)
            .on('popupopen', function (popup) {
                if ( $('.leaflet-popup-pane .leaflet-popup').size() > 1){
                    console.log("yuh");
                    if ( $('.leaflet-popup-content-wrapper .view-record').size() >= 3){
                        $('.leaflet-popup-content-wrapper .view-record').not(':last').remove();
                    }
                    else{
                        $('.leaflet-popup-content .view-record').appendTo('.leaflet-popup-content-wrapper');
                    }
                }
                else{
                    if ( $('.leaflet-popup-content-wrapper .view-record').size() >= 2){
                        $('.leaflet-popup-content-wrapper .view-record').not(':last').remove();
                    }
                    else{
                        $('.leaflet-popup-content .view-record').appendTo('.leaflet-popup-content-wrapper');
                    }
                }
            });
    }

    $('.leaflet-bottom.leaflet-right').children().remove();

    $('.leaflet-bottom.leaflet-right').append('<div class="leaflet-fullscreen"><img src="./assets/images/Fullscreen.svg"/></div>');

    $('.leaflet-fullscreen').on("click", function(){
        if($('.mapwrap').hasClass("mapfullscreen")){
            $('.mapwrap').removeClass('mapfullscreen');
        }
        else{
            $('.mapwrap').addClass('mapfullscreen');
            map.invalidateSize();
        }
    });
    // //Marker 1
    // L.marker([51.5, -0.09], {icon: orangeIcon}).addTo(map)
    //     .bindPopup(customPopup,customOptions)
    //     .openPopup()
    //     .on('popupopen', function (popup) {
    //         if( !($('.leaflet-popup-content-wrapper').has('.view-record')) ){
    //             $('.leaflet-popup-content .view-record').appendTo('.leaflet-popup-content-wrapper');
    //         }else{
    //             $('.leaflet-popup-content .view-record').remove();
    //         }
    //     });
});
