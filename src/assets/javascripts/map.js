$(document).ready(function(){

    var planes = [
		["7C6B07",7.52281,10.19780],
		["7C6B38",0.337489,13.801316],
		["7C6CA1",6.039168,-5.490676],
		["7C6CA2",6.213946,-9.57759],
		["C81D9D",-1.332311,10.355176],
		["C82009",-16.396891,12.596387],
		["C82081",6.476002,19.32002],
		["C820AB",13.739056,-15.264941],
		["C820B6",23.066786,-15.045215]
        ];

    var map = L.map('map-large').setView([0, 15], 3);
    map.scrollWheelZoom.disable();
    map.on('focus', () => { map.scrollWheelZoom.enable(); });
    map.on('blur', () => { map.scrollWheelZoom.disable(); });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var connection_icons = '<div class="connections"><div class="card-icons"><img src="../assets/images/Person-light.svg" alt="person icon"><span>10</span></div><div class="card-icons"><img src="../assets/images/Event-light.svg" alt="event icon"><span>10</span></div><div class="card-icons"><img src="../assets/images/Source-light.svg"><span>10</span></div><div class="card-icons"><img src="../assets/images/Project-light.svg"><span>10</span></div></div>';
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
        iconUrl: '../assets/images/MapMarker.svg',
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

    $('.leaflet-bottom.leaflet-right').append('<div class="leaflet-fullscreen"><img src="../assets/images/Fullscreen.svg" alt="fullscreen"/></div>');

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
