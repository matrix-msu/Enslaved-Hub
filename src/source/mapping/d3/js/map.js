/////////////////////////////////////////////////////////////
//global variables passed through from map.php:
//  mapResults
/////////////////////////////////////////////////////////////
'use strict';
// if the browser is mobile, variable isMobile is then true

var isMobile = false;
(function(a) {
  if (/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) isMobile = true;
})(navigator.userAgent || navigator.vendor || window.opera, 'http://detectmobilebrowser.com/mobile');

//finds the difference between two arrays
Array.prototype.diff = function(a) {
  return this.filter(function(i) {
    return a.indexOf(i) < 0;
  });
};

// Set to true when zooming to region level, then false after state is processed
// This is used to prevent zooming to region level and then state in one click
var currentlyZooming = false;

//make an object that has the regions for the keys and a list of the states as the elements
var regions = {
  south: {
    states: [
      'Delaware',
      'Maryland',
      'Virginia',
      'West Virginia',
      'Kentucky',
      'North Carolina',
      'South Carolina',
      'Tennessee',
      'Georgia',
      'Florida',
      'Alabama',
      'Mississippi',
      'Arkansas',
      'Louisiana',
      'Texas'
    ],
    count: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    americaEats: 0,
    cookbooks: 0,
    documents: 0,
    essays: 0,
    photography: 0
  },
  southWest: {
    states: [
      'Texas',
      'Oklahoma',
      'New Mexico',
      'Arizona',
      'California'
    ],
    count: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    americaEats: 0,
    cookbooks: 0,
    documents: 0,
    essays: 0,
    photography: 0
  },
  farWest: {
    states: [
      'Alaska',
      'California',
      'Nevada',
      'Utah',
      'Colorado',
      'Wyoming',
      'Montana',
      'Idaho',
      'Oregon',
      'Washington'
    ],
    count: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    americaEats: 0,
    cookbooks: 0,
    documents: 0,
    essays: 0,
    photography: 0
  },
  middleWest: {
    states: [
      'North Dakota',
      'South Dakota',
      'Nebraska',
      'Kansas',
      'Missouri',
      'Iowa',
      'Minnesota',
      'Wisconsin',
      'Illinois',
      'Indiana',
      'Ohio',
      'Michigan'
    ],
    count: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    americaEats: 0,
    cookbooks: 0,
    documents: 0,
    essays: 0,
    photography: 0
  },
  northEast: {
    states: [
      'Maine',
      'New Hampshire',
      'Vermont',
      'Massachusetts',
      'Rhode Island',
      'Connecticut',
      'New York',
      'New Jersey',
      'Pennsylvania'
    ],
    count: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    americaEats: 0,
    cookbooks: 0,
    documents: 0,
    essays: 0,
    photography: 0
  },
};

//Object containing objects that have the associated zoom coordinates for their single state selection
var stateList = {
  newHampshire: {
    x: -3500,
    y: -175,
    s: 4.7,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  vermont: {
    x: -3450,
    y: -200,
    s: 4.7,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  maine: {
    x: -3525,
    y: -50,
    s: 4.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  rhodeIsland: {
    x: -5550,
    y: -725,
    s: 7,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  newYork: {
    x: -2800,
    y: -225,
    s: 4,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  pennsylvania: {
    x: -2650,
    y: -400,
    s: 4,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  newJersey: {
    x: -3700,
    y: -600,
    s: 5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  connecticut: {
    x: -5000,
    y: -650,
    s: 6.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  massachusetts: {
    x: -3600,
    y: -350,
    s: 4.8,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  florida: {
    x: -2050,
    y: -1100,
    s: 3.3,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  delaware: {
    x: -3800,
    y: -750,
    s: 5.2,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  maryland: {
    x: -3200,
    y: -700,
    s: 4.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  virginia: {
    x: -2600,
    y: -625,
    s: 4.0,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  westVirginia: {
    x: -2300,
    y: -550,
    s: 3.7,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  northCarolina: {
    x: -2250,
    y: -700,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  tennessee: {
    x: -1900,
    y: -700,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  arkansas: {
    x: -1550,
    y: -850,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  georgia: {
    x: -2100,
    y: -900,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  southCarolina: {
    x: -2250,
    y: -800,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  kentucky: {
    x: -1900,
    y: -600,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  alabama: {
    x: -1950,
    y: -900,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  louisiana: {
    x: -1650,
    y: -1000,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  mississippi: {
    x: -1750,
    y: -925,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  michigan: {
    x: -1850,
    y: -200,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  sp: {
    x: -1850,
    y: -200,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  ohio: {
    x: -2050,
    y: -450,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  indiana: {
    x: -1850,
    y: -450,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  illinois: {
    x: -1750,
    y: -450,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  wisconsin: {
    x: -1750,
    y: -225,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  missouri: {
    x: -1600,
    y: -575,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  iowa: {
    x: -1550,
    y: -375,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  minnesota: {
    x: -1550,
    y: -100,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  kansas: {
    x: -1300,
    y: -550,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  nebraska: {
    x: -1200,
    y: -400,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  southDakota: {
    x: -1250,
    y: -200,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  northDakota: {
    x: -1250,
    y: -75,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  montana: {
    x: -750,
    y: 0,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  idaho: {
    x: -550,
    y: -50,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  oregon: {
    x: -300,
    y: -50,
    s: 3.4,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  washington: {
    x: -300,
    y: 100,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  oklahoma: {
    x: -850,
    y: -450,
    s: 2.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  texas: {
    x: -800,
    y: -660,
    s: 2.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  newMexico: {
    x: -450,
    y: -500,
    s: 2.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  wyoming: {
    x: -800,
    y: -250,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  colorado: {
    x: -850,
    y: -500,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  utah: {
    x: -650,
    y: -425,
    s: 3.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  arizona: {
    x: -325,
    y: -500,
    s: 2.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  nevada: {
    x: -100,
    y: -300,
    s: 2.5,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  california: {
    x: -50,
    y: -220,
    s: 2.25,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  alaska: {
    x: 100,
    y: -1070,
    s: 3,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  },
  hawaii: {
    x: -950,
    y: -1875,
    s: 4,
    count: 0,
    cookbooks: 0,
    documents: 0,
    americaEats: 0,
    essays: 0,
    advertisements: 0,
    Ads: 0,
    Ephemera: 0,
    AdsAndEphemera: 0,
    photography: 0
  }
};

console.log(mapResults);
var documentsControl = ['Correspondence', 'Essay', 'Field Editorial Copy', 'Form',
  'Instructions', 'List', 'Memorandum', 'Report', 'Pamphlet', 'Research'
];

//loop through the mapResults json Object and get the total number of items for each state/region
for (var kid in mapResults) {
  //make the region camelCase
  var camelCaseRegion = mapResults[kid].Region
    .replace(/\s(.)/g, function($1) {
      return $1.toUpperCase();
    })
    .replace(/\s/g, '')
    .replace(/^(.)/, function($1) {
      return $1.toLowerCase();
    });
  //southwest and northeast don't work properly because of how they're named in the database
  if (camelCaseRegion === 'southwest') {
    camelCaseRegion = 'southWest';
  } else if (camelCaseRegion === 'northeast') {
    camelCaseRegion = 'northEast';
  }
  // Add to the count
  if (camelCaseRegion in regions) {
    regions[camelCaseRegion].count++;

    if (mapResults[kid]['America Eats'] == 'TRUE') {
      regions[camelCaseRegion]['americaEats']++;
    }

    var docAdded = false;
    for (var formatIndex in mapResults[kid]['Original Format']) {
      var format = mapResults[kid]['Original Format'][formatIndex];
      if (format == 'Essay') {
        regions[camelCaseRegion].essays++;
        regions[camelCaseRegion].documents++;
        docAdded = true;
      } else if (format == 'Community Cookbook') {
        regions[camelCaseRegion].cookbooks++;
      } else if (format == 'Advertising') {
        regions[camelCaseRegion].Ads++;
      } else if (format == 'Ephemera') {
        regions[camelCaseRegion].Ephemera++;
      } else if (format == 'Photograph') {
        regions[camelCaseRegion].photography++;
      } else if (documentsControl.indexOf(format) != -1 && docAdded == false) {
        regions[camelCaseRegion].documents++;
        docAdded = true;
      } else {
        // console.log(format);
      }
      if (mapResults[kid]['Original Format'].indexOf('Ephemera') !== -1 && mapResults[kid]['Original Format'].indexOf('Advertising') !== -1) {
        regions[camelCaseRegion].AdsAndEphemera++;
      }
    }
    regions[camelCaseRegion].advertisements = regions[camelCaseRegion].Ads + regions[camelCaseRegion].Ephemera - regions[camelCaseRegion].AdsAndEphemera;
  }
  //loop through the regions object
  for (var currentRegion in regions) {}
  //loop though each state in the list of states the kid belongs to
  for (var index = 0; index < mapResults[kid].Map.length; index++) {
    var camelCaseState = mapResults[kid].Map[index]
      .replace(/\s(.)/g, function($1) {
        return $1.toUpperCase();
      })
      .replace(/\s/g, '')
      .replace(/^(.)/, function($1) {
        return $1.toLowerCase();
      });
    //get state count
    if (camelCaseState in stateList) {
      stateList[camelCaseState].count += 1;

      if (mapResults[kid]['America Eats'] == 'TRUE') {
        stateList[camelCaseState]['americaEats']++;
      }

      var docAdded = false;
      for (var formatIndex in mapResults[kid]['Original Format']) {
        var format = mapResults[kid]['Original Format'][formatIndex];
        if (format == 'Essay') {
          stateList[camelCaseState].essays++;
          stateList[camelCaseState].documents++;
          docAdded = true;
        } else if (format == 'Community Cookbook') {
          stateList[camelCaseState].cookbooks++;
        } else if (format == 'Advertising') {
          stateList[camelCaseState].Ads++;
        } else if (format == 'Ephemera') {
          stateList[camelCaseState].Ephemera++;
        } else if (format == 'Photograph') {
          stateList[camelCaseState].photography++;
        } else if (documentsControl.indexOf(format) != -1 && docAdded == false) {
          stateList[camelCaseState].documents++;
          docAdded = true;
        } else {
          // console.log(format);
        }
        if (mapResults[kid]['Original Format'].indexOf('Ephemera') !== -1 && mapResults[kid]['Original Format'].indexOf('Advertising') !== -1) {
          stateList[camelCaseState].AdsAndEphemera++;
        }
      }
      stateList[camelCaseState].advertisements = stateList[camelCaseState].Ads + stateList[camelCaseState].Ephemera - stateList[camelCaseState].AdsAndEphemera;
    }
  }
}


////////////////////////////////////////////////////////////
//Selected regions stuff
////////////////////////////////////////////////////////////
var selectedRegions = d3.set([]);

var updateSelectedAreasList = function(areaType) {
  var selectedRegionsList = [];
  var selectedStatesList = [];

  if (areaType == "regions") {
    for (var i = 0; i <= selectedRegions.values().length - 1; i++) {
      selectedRegionsList.push(selectedRegions.values()[i].split(/(?=[A-Z])/).join(''));
    }
    return selectedRegionsList;
  } else if (areaType == "states") {
    for (var i = 0; i <= selectedStates.values().length - 1; i++) {
      selectedStatesList.push(selectedStates.values()[i].slice(0, -1).split(/(?=[A-Z])/).join(''));
    }
    return selectedStatesList;
  }
};

var regionColors = d3.map();
regionColors.set('northEast', '#303a56');
regionColors.set('south', '#303a56');
regionColors.set('middleWest', '#303a56');
regionColors.set('farWest', '#303a56');
regionColors.set('southWest', '#303a56');

/////////////////////////////////////////////////////////////
//Selected States Stuff
/////////////////////////////////////////////////////////////
var selectedStates = d3.set([]);

var zoomState = 0;

var lastClick = "";

var map = d3.select('#map');

var hoverOnRegion = function(region) {
  if (!selectedRegions.has(region) && zoomState === 0) {
    var regionDescription = $('<div class="' + region + 'DescriptionWrapper">' +
      '<h3 class="' + region + 'Description regionTitle">' + region.split(/(?=[A-Z])/).join(' ') + '</h3>' +
      '<span class="' + region + 'Description">' + regions[region].count + ' Records' + '</span></div>');
    d3.selectAll('.' + region)
      .style('fill', '#D34F37')
      .style('stroke', '#FFF9EF');
    if (region === 'south' || region === 'southWest') {
      d3.select('#westTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#eastTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    }
    if (region === 'farWest' || region === 'southWest') {
      d3.select('#northCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#southCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    }
    document.getElementById('zoom').classList.remove('hidden');
    $('#regionWrapper').append(regionDescription);
  }
};

var hoverOffRegion = function(region) {
  if (!selectedRegions.has(region) && zoomState === 0) {
    $('.' + region + 'Description').remove();
    $('.' + region + 'DescriptionWrapper').remove();
    d3.selectAll('.' + region)
      .style('fill', regionColors.get(region))
      .style('stroke', '#FFFAF3');
    if (region === 'south' || region === 'southWest') {
      d3.select('#westTexas_')
        .style('fill', regionColors.get(region))
        .style('stroke', '#FFFAF3');
      d3.select('#eastTexas_')
        .style('fill', regionColors.get(region))
        .style('stroke', '#FFFAF3');
    }
    if (region === 'southWest' || region === 'farWest') {
      d3.select('#northCalifornia_')
        .style('fill', regionColors.get(region))
        .style('stroke', '#FFFAF3');
      d3.select('#southCalifornia_')
        .style('fill', regionColors.get(region))
        .style('stroke', '#FFFAF3');
    }
    //put back the highlight on on the states that would have it taken away because they're part of two regions
    if (selectedRegions.has('southWest') && (region === 'south' || region === 'farWest')) {
      d3.select('#northCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#southCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#westTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#eastTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    } else if (selectedRegions.has('south') && selectedRegions.has('farWest') && region === 'southWest') {
      d3.select('#westTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#eastTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#northCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#southCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    } else if (selectedRegions.has('south') && region === 'southWest') {
      d3.select('#westTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#eastTexas_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    } else if (selectedRegions.has('farWest') && region === 'southWest') {
      d3.select('#northCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      d3.select('#southCalifornia_')
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
    }
    if (selectedRegions.size() === 0) {
      document.getElementById('zoom').classList.add('hidden');
    }
  }
};

var selectRegion = function(region) {
  if (!$('#zoom').hasClass('hidden') || isMobile) {
    if (selectedRegions.has(region) && zoomState === 0) { //removing the region
      //take care of the hover dependencies on mobile
      if (isMobile) {
        hoverOffRegion(region);
      }
      //take care of the rest of the states in the region
      d3.selectAll('.' + region)
        .style('fill', regionColors.get(region))
        .style('stroke', '#FFFAF3');
      //remove the parts of states that are technically parts of other regions
      if ((region === 'south' && !selectedRegions.has('southWest')) || (region === 'southWest' && !selectedRegions.has('south'))) {
        d3.select('#westTexas_')
          .style('fill', regionColors.get(region))
          .style('stroke', '#FFFAF3');
        d3.select('#eastTexas_')
          .style('fill', regionColors.get(region))
          .style('stroke', '#FFFAF3');
      } else if (region === 'south' || region === 'southWest') {
        d3.select('#westTexas_')
          .style('fill', '#D34F37')
          .style('stroke', '#FFF9EF');
        d3.select('#eastTexas_')
          .style('fill', '#D34F37')
          .style('stroke', '#FFF9EF');
      }
      if ((region === 'southWest' && !selectedRegions.has('farWest')) || (region === 'farWest' && !selectedRegions.has('southWest'))) {
        d3.select('#northCalifornia_')
          .style('fill', regionColors.get(region))
          .style('stroke', '#FFFAF3');
        d3.select('#southCalifornia_')
          .style('fill', regionColors.get(region))
          .style('stroke', '#FFFAF3');
      } else if (region === 'southWest' || region === 'farWest') {
        d3.select('#northCalifornia_')
          .style('fill', '#D34F37')
          .style('stroke', '#FFF9EF');
        d3.select('#southCalifornia_')
          .style('fill', '#D34F37')
          .style('stroke', '#FFF9EF');
      }
      selectedRegions.remove(region);
      $('.' + region + 'Description').remove();
      $('.' + region + 'DescriptionWrapper').remove();
      if (selectedRegions.size() === 0) {
        $('#zoom').addClass('hidden');
        $('.zoomInButton').addClass('hidden');
      }
    } else if (!selectedRegions.has(region) && zoomState === 0) { //adding the region
      //take care of the hover dependencies on mobile
      if (isMobile) {
        hoverOnRegion(region);
      }
      selectedRegions.add(region);
      if (selectedRegions.size() > 1) {
        for (var r in regions) {
          if (region != r && selectedRegions.has(r)) {
            selectRegion(r);
          }
        }
      }
      d3.selectAll('.' + region)
        .style('fill', '#D34F37')
        .style('stroke', '#FFF9EF');
      $('.zoomInButton').removeClass('hidden');
      // Zoom on click
      $('.zoomInButton').click();
      // Prevent selectState from zooming from this click - should take another
      currentlyZooming = true;
    }
  }
};

var hoverOnState = function(state, region) {
  if (state == "sp_") {
    state = "michigan_";
  }
  if (!selectedStates.has(state) && !isMobile && zoomState === 1 && selectedRegions.has(region)) {
    var stateDescription = '<div class="' + state + 'DescriptionWrapper">' +
      '<h3 class="' + state + 'Description regionTitle">' + state.split(/(?=[A-Z])/).join(' ').slice(0, -1) + '</h3>' +
      '<span class="' + state + 'Description">' + stateList[state.slice(0, -1)].count + ' Records' + '</span></div>';
    d3.select('#' + state)
      .style('fill', '#D34F37');
    if (state == "michigan_") {
      d3.select('#sp_')
        .style('fill', '#D34F37');
    }
    var stateWrapper = document.getElementById('stateWrapper');
    stateWrapper.classList.remove('hidden');
    stateWrapper.insertAdjacentHTML('beforeend', stateDescription);
  }
};

var hoverOffState = function(state, region) {
  if (state == "sp_") {
    state = "michigan_";
  }
  if (!selectedStates.has(state) && !isMobile && zoomState === 1 && selectedRegions.has(region)) {
    $('.' + state + 'Description').remove();
    $('.' + state + 'DescriptionWrapper').remove();
    if (selectedStates.empty()) {
      $('#stateWrapper').addClass('hidden');
    }
    d3.select('#' + state)
      .style('fill', '#242B46');
    if (state == "michigan_") {
      d3.select('#sp_')
        .style('fill', '#242B46');
    } else if (state == "sp_") {
      d3.select('#michigan_')
        .style('fill', '#242B46');
    }
  }
};

var selectState = function(state, region) {
  if (state == "sp_") {
    state = "michigan_";
  }
  if (selectedStates.has(state) && zoomState === 1 && selectedRegions.has(region)) { //removing the state
    d3.select('#' + state)
      .style('fill', '#242B46');
    if (state == "michigan_") {
      d3.select('#sp_')
        .style('fill', '#242B46');
    }
    $('.' + state + 'Description').remove();
    $('.' + state + 'DescriptionWrapper').remove();
    selectedStates.remove(state);
    if (selectedStates.empty()) {
      $('.zoomInButton').addClass('hidden');
      $('#stateWrapper').addClass('hidden');
    }
  } else if (!selectedStates.has(state) && zoomState === 1 && selectedRegions.has(region) && !currentlyZooming) { //adding the state
    var stateDescription = $('<div class="' + state + 'DescriptionWrapper">' +
        '<h3 class="' + state + 'Description regionTitle">' + state.split(/(?=[A-Z])/).join(' ').slice(0, -1) + '</h3>' +
        '<span class="' + state + 'Description">' + stateList[state.slice(0, -1)].count + ' Records' + '</span></div>')
      .hide()
      .fadeIn('fast');
    selectedStates.add(state);
    if (selectedStates.size() === 1) {
      $('.zoomInButton').removeClass('hidden');
    }
    if (selectedStates.size() > 1) {
      regions[region].states.forEach(function(s) {
        s = s.toLowerCase() + '_';
        if (state != s && selectedStates.has(s)) {
          selectState(s, region);
        }
      })
    }
    if ($('.' + state + 'Description').length === 0) {
      $('#stateWrapper')
        .removeClass('hidden')
        .append(stateDescription);
    }
    d3.select('#' + state)
      .style('fill', '#D34F37');
    if (state == "michigan_") {
      d3.select('#sp_')
        .style('fill', '#D34F37');
    }
    // Zoom on click
    $('.zoomInButton').click();
  }
};

////////////////////////////////////////////////////////////////
//Creating, appending, and deleting the description
////////////////////////////////////////////////////////////////
var createDescription = function(selectedAreasList, areaType, region) {


  var countList = getCounts(selectedAreasList, areaType);

  // if (areaType == "states") {
  //   $('.itemList').remove();
  // }
  // If only done on states, zooming out leaves two lists, which is confusing, I think
  $('.itemList').remove();

  // Construct base URL part
  var url = "search.results.php?" + areaType + "=" + selectedAreasList[0];
  if (region != null) {
    url += "&regions=" + region;
  }

  // to not show filters for which there are none, breaking it up this way
  var appendText = '<div class="itemList">';
  if (countList['advertisements'] != 0) {
    appendText += '<a href="' + url + '&format=advertising" class="zoomLinks">' + 'Advertising (' + countList['advertisements'] + ')' + '<div class="carrot carrot-sm"></div> </a >';
  }
  if (countList['America Eats'] != 0) {
    appendText += '<a href="' + url + '&format=americaeats" class="zoomLinks">' + 'America Eats (' + countList['America Eats'] + ')' + '<div class="carrot carrot-sm"></div> </a >';
  }
  if (countList['cookbooks'] != 0) {
    appendText += '<a href="' + url + '&format=cookbooks" class="zoomLinks">' + 'Cookbooks (' + countList['cookbooks'] + ')' + '<div class="carrot carrot-sm"></div> </a >';
  }
  // if (countList['documents'] != 0) {
  //   appendText += '<a href="' + url + '&format=documents" class="zoomLinks">' + 'Documents (' + countList['documents'] + ')' + '<div class="carrot carrot-sm"></div> </a >';
  // }
  if (countList['photography'] != 0) {
    appendText += '<a href="' + url + '&format=photography" class="zoomLinks">' + 'Photography (' + countList['photography'] + ')' + '<div class="carrot carrot-sm"></div> </a >';
  }
  appendText += '</div';
  $('#zoom').append(appendText);

  /*$('#zoom').append('<div class="itemList">' +
    '<a href="' + url + '&format=advertising" class="zoomLinks">' + 'Advertising (' + countList['advertisements'] + ')' + '<div class="carrot carrot-sm"></div> </a >' +
    '<a href="' + url + '&format=americaeats" class="zoomLinks">' + 'America Eats (' + countList['America Eats'] + ')' + '<div class="carrot carrot-sm"></div> </a >' +
    '<a href="' + url + '&format=cookbooks" class="zoomLinks">' + 'Cookbooks (' + countList['cookbooks'] + ')' + '<div class="carrot carrot-sm"></div> </a >' +
    '<a href="' + url + '&format=documents" class="zoomLinks">' + 'Documents (' + countList['documents'] + ')' + '<div class="carrot carrot-sm"></div> </a >' +
    //'<a href="browse.php" class="zoomLinks">' + 'Essays (' + countList['essays'] +')' + '<div class="carrot carrot-sm"></div> </a >' +
    '<a href="' + url + '&format=photography" class="zoomLinks">' + 'Photography (' + countList['photography'] + ')' + '<div class="carrot carrot-sm"></div> </a >');*/
};

var appendDescription = function() {
  var selectedRegionsList = updateSelectedAreasList("regions");
  if (zoomState === 1) {
    createDescription(selectedRegionsList, "regions");
  } else if (zoomState === 2) {
    var selectedStatesList = updateSelectedAreasList("states");

    createDescription(selectedStatesList, "states", selectedRegionsList[0]);
  }
  if (selectedRegions.size() === 1 || selectedRegions.size() === 5) {
    $('.regionsSelected').addClass('oneRegion');
  } else if (selectedRegions.size() === 2) {
    $('.regionsSelected').addClass('twoRegions');
  }
};

var removeDescription = function() {
  $('#stateWrapper').addClass('hidden');
  $('.regionsSelected').remove();
  $('.itemList').remove();
};

var getCounts = function(selectedAreasList, areaType) {
  var numAdvertisements = 0;
  var numAmericaEats = 0;
  var numCookbooks = 0;
  var numDocuments = 0;
  //var numEssays = 0;
  var numPhotographs = 0;
  var countList = [];
  if (areaType == "regions") {
    for (var i = 0; i < selectedAreasList.length; i++) {
      numAdvertisements += regions[selectedAreasList[i]].advertisements;
      numAmericaEats += regions[selectedAreasList[i]].americaEats;
      numCookbooks += regions[selectedAreasList[i]].cookbooks;
      numDocuments += regions[selectedAreasList[i]].documents;
      //numEssays += regions[selectedAreasList[i]].essays;
      numPhotographs += regions[selectedAreasList[i]].photography;
    }
  } else if (areaType == "states") {
    for (var i = 0; i < selectedAreasList.length; i++) {
      numAdvertisements += stateList[selectedAreasList[i]].advertisements;
      numAmericaEats += stateList[selectedAreasList[i]].americaEats;
      numCookbooks += stateList[selectedAreasList[i]].cookbooks;
      numDocuments += stateList[selectedAreasList[i]].documents;
      //numEssays += stateList[selectedAreasList[i]].essays;
      numPhotographs += stateList[selectedAreasList[i]].photography;
    }
  }
  countList['advertisements'] = numAdvertisements;
  countList['America Eats'] = numAmericaEats;
  countList['cookbooks'] = numCookbooks;
  countList['documents'] = numDocuments;
  //countList['essays'] = numEssays;
  countList['photography'] = numPhotographs;

  return countList;
};

////////////////////////////////////////////////////////////
//Zooming
////////////////////////////////////////////////////////////
var zoomInButton = d3.select('.zoomInButton')
  .on('click', function() {
    if (zoomState === 0) { //Zooming in on a region or regions - handled below
      if (selectedRegions.has('southWest')) {
        //unhide the whole states
        d3.select('#texas_').classed('southWest', true).classed('special', false);
        d3.select('#california_').classed('southWest', true).classed('special', false);
        //hide the split states
        d3.select('#eastTexas_').classed('south', false).classed('special', true);
        d3.select('#westTexas_').classed('southWest', false).classed('special', true);
        d3.select('#southCalifornia_').classed('southWest', false).classed('special', true);
        d3.select('#northCalifornia_').classed('farWest', false).classed('special', true);
      } else if (selectedRegions.has('south') && selectedRegions.has('farWest')) {
        d3.select('#texas_').classed('south', true).classed('special', false);
        d3.select('#california_').classed('farWest', true).classed('special', false);
      } else if (selectedRegions.has('south')) {
        d3.select('#texas_').classed('south', true).classed('special', false);
      } else if (selectedRegions.has('farWest')) {
        d3.select('#california_').classed('farWest', true).classed('special', false);
      }
      stateActions('#california');
      stateActions('#texas');
      regionZoom();
    } else if (zoomState === 1) { //Zooming in on a state or states
      d3.select('#eastTexas_').classed('south', true).classed('special', false);
      d3.select('#westTexas_').classed('southWest', true).classed('special', false);
      d3.select('#southCalifornia_').classed('southWest', true).classed('special', false);
      d3.select('#northCalifornia_').classed('farWest', true).classed('special', false);
      if (selectedStates.size() === 1) {
        var stateData = stateList[selectedStates.values()[0].slice(0, -1)];
        zoomIn(stateData.x, stateData.y, stateData.s);
      } else {
        zoomIn(NaN, NaN, 0.01);
      }
    }
  });

var zoomOutButton = d3.select('.zoomOutButton')
  .on('click', function() {
    if (zoomState === 1) {
      for (var i = selectedStates.size() - 1; i >= 0; i--) {
        selectedStates.remove(selectedStates.values()[i]);
      }
      if (selectedRegions.has('southWest')) {
        d3.select('#texas_').classed('southWest', false).classed('special', true);
        d3.select('#california_').classed('southWest', false).classed('special', true);
        d3.select('#eastTexas_').classed('south', true).classed('special', false);
        d3.select('#westTexas_').classed('southWest', true).classed('special', false);
        d3.select('#southCalifornia_').classed('southWest', true).classed('special', false);
        d3.select('#northCalifornia_').classed('farWest', true).classed('special', false);
      } else if (selectedRegions.has('south') && selectedRegions.has('farWest')) {
        d3.select('#texas_').classed('south', false).classed('special', true);
        d3.select('#california_').classed('farWest', false).classed('special', true);
      } else if (selectedRegions.has('south')) {
        d3.select('#texas_').classed('south', false).classed('special', true);
      } else if (selectedRegions.has('farWest')) {
        d3.select('#california_').classed('farWest', false).classed('special', true);
      }
      map.transition()
        .duration(750)
        .attr('transform',
          'translate(' + 0 + ',' + 0 + ')' +
          'scale(1)');
      resetZoom();
      resetHighlight();
      zoomState--;
      removeDescription();
      // Deselect regions on zoom out as requested
      for (var i = selectedRegions.size() - 1; i >= 0; i--) {
        selectRegion(selectedRegions.values()[i]);
      }
    } else if (zoomState === 2) {
      //add the hidden states back
      d3.select('#eastTexas_').classed('south', true).classed('special', false);
      d3.select('#westTexas_').classed('southWest', true).classed('special', false);
      d3.select('#southCalifornia_').classed('southWest', true).classed('special', false);
      d3.select('#northCalifornia_').classed('farWest', true).classed('special', false);
      //$('.zoomInButton').removeClass('hidden');  //Put the zoom in button back here because of the zoom
      $('#regionWrapper').removeClass('hidden');
      //$('#stateWrapper').removeClass('stateWrapperZoom');
      regionZoom();
      // Deselect states for zoom back out
      for (var i = selectedStates.size() - 1; i >= 0; i--) {
        var s = selectedStates.values()[i]; // temp variable to get things to work right for some reason
        selectedStates.remove(s);
        $('#stateWrapper').addClass('hidden');
        setTimeout(function() {
          $('.' + s + 'Description').remove();
          $('.' + s + 'DescriptionWrapper').remove();
          $('#stateWrapper').removeClass('stateWrapperZoom');
        }, 1000);
      }
      removeHighlight();
    }
  });

var zoomIn = function(x, y, s) {
  if (!isNaN(x) && !isNaN(y)) {
    map.transition()
      .duration(750)
      .attr('transform',
        'translate(' + x + ',' + y + ')' +
        'scale(' + s + ')');
  } else if (zoomState === 1 && (isNaN(x) && isNaN(y))) {
    //get old variables
    var previousTransform = document.getElementById('map').getAttribute('transform');
    var xyArray = /\(\s*([^\s,)]+)[ ,]([^\s,)]+)/.exec(previousTransform); //[1] == x, [2] == y
    var scaleString = previousTransform.slice(previousTransform.indexOf('scale'));
    var scaleArray = /\(\s*([^\s,)]+)[ ,]([^\s,)]+)/.exec(scaleString); //[1] == s
    //set old variables
    var oldx = parseFloat(xyArray[1]),
      oldy = parseFloat(xyArray[2]),
      olds = 1;
    if (scaleArray) {
      olds = parseFloat(scaleArray[1]);
    }
    s += olds;
    map.transition()
      .duration(750)
      .attr('transform',
        'translate(' + oldx + ',' + oldy + ')' +
        'scale(' + s + ')');
  }
  if (zoomState === 0 || zoomState === 1) {
    zoomState += 1;
    removeHighlight();
  } else {
    resetHighlight();
    zoomState -= 1;
  }
  appendDescription();

  setZoom();
};

//removes the highlight on selected objects and decreases the opacity on the unselected objects
var removeHighlight = function() {
  if (zoomState === 1) { //regions
    var unselectedRegions = regionColors.keys().diff(selectedRegions.values());
    for (var i = unselectedRegions.length - 1; i >= 0; i--) {
      d3.selectAll('.' + unselectedRegions[i])
        .style('fill', '#D0D6DD')
        .style('stroke', '#D0D6DD')
        .style('cursor', 'auto');
    }
    for (var j = selectedRegions.size() - 1; j >= 0; j--) {
      var region = selectedRegions.values()[j];
      d3.selectAll('.' + region)
        .style('fill', '#242B46');
    }
  } else if (zoomState === 2) { //states
    var unselectedStates = getUnselectedStates();
    for (var i = unselectedStates.length - 1; i >= 0; i--) {
      d3.select('#' + unselectedStates[i])
        .style('fill', '#D0D6DD')
        .style('stroke', '#D0D6DD')
        .style('cursor', 'auto');
    }
    for (var k = selectedStates.size() - 1; k >= 0; k--) {
      var state = selectedStates.values()[k];
      if (selectedRegions.has($('#' + state).attr('class'))) {
        d3.select('#' + state)
          .style('fill', '#242B46')
          .style('cursor', 'initial');
      }
    }
    if (selectedStates.values().indexOf("michigan_") !== -1) {
      d3.select('#sp_')
        .style('fill', '#242B46')
        .style('cursor', 'initial');
    } else {
      d3.select('#sp_')
        .style('fill', '#D0D6DD')
        .style('cursor', 'auto');
    }
  }
};

//puts the highlight back on the selected elements and restores the opacity to 1 on everything else
var resetHighlight = function() {
  if (zoomState === 1) { //regions
    var unselectedRegions = regionColors.keys().diff(selectedRegions.values());
    for (var i = unselectedRegions.length - 1; i >= 0; i--) {
      d3.selectAll('.' + unselectedRegions[i])
        .style('fill', '#303a56')
        .style('stroke', '#FFF9EF')
        .style('cursor', 'pointer');
    }
    for (var i = selectedRegions.size() - 1; i >= 0; i--) {
      var region = selectedRegions.values()[i];
      d3.selectAll('.' + region)
        .style('fill', '#D34F37');
    }
    //take care of special cases
    if (selectedRegions.has('south') || selectedRegions.has('southWest')) {
      d3.select('#westTexas_')
        .style('fill', '#D34F37');
      d3.select('#eastTexas_')
        .style('fill', '#D34F37');
    }
    if (selectedRegions.has('farWest') || selectedRegions.has('southWest')) {
      d3.select('#northCalifornia_')
        .style('fill', '#D34F37');
      d3.select('#southCalifornia_')
        .style('fill', '#D34F37');
    }
  } else if (zoomState === 2) { //states
    var unselectedStates = getUnselectedStates();
    for (var i = unselectedStates.length - 1; i >= 0; i--) { //loop through unselected states and reset the color
      if (selectedRegions.has($('#' + unselectedStates[i]).attr('class'))) {
        d3.select('#' + unselectedStates[i])
          .style('fill', '#D0D6DD')
          .style('cursor', 'pointer');
      }
    }
    for (var i = selectedStates.size() - 1; i >= 0; i--) {
      var state = selectedStates.values()[i];
      d3.select('#' + state)
        .style('fill', '#242b46')
        .style('cursor', 'pointer');
    }
    if (selectedRegions.has('middleWest')) {
      d3.select('#sp_')
        .style('fill', '#242B46')
        .style('cursor', 'initial');
    }
    if (selectedStates.values().indexOf('michigan_') !== -1) {
      d3.select('#sp_')
        .style('fill', '#D34F37')
        .style('cursor', 'pointer');
    }
  }
};

//Finds the unselected states
var getUnselectedStates = function() {
  var unselectedStates = [];
  //loop through the full state list
  for (var i = Object.keys(stateList).length - 1; i >= 0; i--) {
    //if the current state isn't selected...
    if (selectedStates.values().indexOf(Object.keys(stateList)[i] + '_') === -1) {
      //... add it to the unselected states array
      unselectedStates.push(Object.keys(stateList)[i] + '_');
    }
  }
  return unselectedStates;
};

var setZoom = function() {
  document.getElementById('zoom').classList.add('zoomRegion');
  //document.getElementById('description').classList.add('hidden-desc');
  $(".description").addClass('desc-fade-out');
  $(".description").removeClass("desc-fade-in");
  $('.zoomOutButton').removeClass('hidden');
  $('.zoomInButton').addClass('zoomedInButton');
  // document.getElementById('map-container').classList.add('zoomedMapContainer');
  $('#stateWrapper').removeClass('hidden')
    .removeClass('stateZoom');
  if (selectedStates.empty()) {
    $('.zoomInButton').addClass('hidden');
  }
  if (zoomState === 2) { //zooming in on a state or multiple states
    document.getElementById('regionWrapper').classList.add('hidden'); //hide the region field
    $('.zoomInButton').addClass('hidden'); //hide that zoomIn Button
    document.getElementById('stateWrapper').classList.add('stateWrapperZoom');
  }
};

var resetZoom = function() {
  document.getElementById('zoom').classList.remove('zoomRegion');
  //document.getElementById('description').classList.remove('hidden-desc');
  $(".description").addClass("desc-fade-in");
  $(".description").removeClass("desc-fade-out");
  $('.zoomOutButton').addClass('hidden');
  $('.zoomInButton').removeClass('zoomedInButton');
  // Commented out line below because we are now deslecting states and regions on zoom out
  //$('.zoomInButton').removeClass('hidden');
  document.getElementById('map-container').classList.remove('zoomedMapContainer');
  $('#stateWrapper').children('div').remove();
  document.getElementById('regionWrapper').classList.remove('hidden');
};

////////////////////////////////////////////////////
// States
///////////////////////////////////////////////////
var stateActions = function(state) {
  if (state == "sp_") {
    state = "michigan_";
  }
  var stateIn = $(state).children('path').attr('id');
  var regionIn = $(state).children('path').attr('class');
  d3.select(state)
    .on('mouseover', function() {
      if (zoomState === 1) {
        hoverOnState(stateIn, regionIn);
      }
    })
    .on('mouseout', function() {
      if (zoomState === 1) {
        hoverOffState(stateIn, regionIn);
      }
    })
    .on('click', function() {
      if (zoomState === 1) {
        selectState(stateIn, regionIn);
        // Clear boolean flag to allow zooming next click
        currentlyZooming = false;
      }
    });
  // Touch events
  if (isMobile) {
    $(state).on('tap', function(event) {
      event.preventDefault();
      if (zoomState === 1) {
        selectState(stateIn, regionIn);
      }
    });
  }
};

$(document).ready(function() {
  for (var key in stateList) {
    stateActions('#' + key);
  }
  //stateActions('#Sp');
});

////////////////////////////////////////////////////
// Regions
///////////////////////////////////////////////////
var regionActions = function(region) {
  d3.selectAll(region)
    .on('mouseover', function() {
      hoverOnRegion($(this).attr('class'));
    })
    .on('mouseout', function() {
      hoverOffRegion($(this).attr('class'));
    })
    .on('click', function() {
      selectRegion($(this).attr('class'));
    });
  if (isMobile) {
    $(region).on('tap', function(event) {
      event.preventDefault();
      selectRegion(region.slice(1));
    });
  }
};

$(document).ready(function() {
  regionActions('.northEast');
  regionActions('.south');
  regionActions('.middleWest');
  regionActions('.farWest');
  regionActions('.southWest');
});

/////////////////////////////////////////////////
// Zoom in functions
/////////////////////////////////////////////////
var regionZoom = function() {
  if (selectedRegions.has('northEast')) {
    northEastZoom();
  } else if (selectedRegions.has('south')) {
    southZoom();
  } else if (selectedRegions.has('middleWest')) {
    middleWestZoom();
  } else if (selectedRegions.has('farWest')) {
    farWestZoom();
  } else if (selectedRegions.has('southWest')) {
    if (selectedRegions.size() === 1) {
      zoomIn(-40, -50, 1.05);
    }
  }
};

function northEastZoom() {
  if (selectedRegions.size() === 1) {
    zoomIn(-1800, -50, 2.7);
  } else if (selectedRegions.size() === 2) {
    if (selectedRegions.has('south')) {
      zoomIn(-300, -20, 1.1);
    } else if (selectedRegions.has('farWest')) {
      zoomIn(-100, -20, 1.1);
    } else if (selectedRegions.has('middleWest')) {
      zoomIn(-600, -40, 1.7);
    } else if (selectedRegions.has('southWest')) {
      zoomIn(0, 10, 0.9);
    }
  } else if (selectedRegions.size() === 3) {
    if (selectedRegions.has('south')) {
      if (selectedRegions.has('middleWest')) {
        zoomIn(-100, 10, 1.05);
      } else if (selectedRegions.has('southWest')) {
        zoomIn(0, -10, 1);
      } else if (selectedRegions.has('farWest')) {
        zoomIn(-50, -30, 1.1);
      }
    } else if (selectedRegions.has('middleWest')) {
      if (selectedRegions.has('southWest')) {
        zoomIn(-10, -10, 1);
      } else if (selectedRegions.has('farWest')) {
        zoomIn(-100, -10, 1.1);
      }
    } else if (selectedRegions.has('southWest')) {
      if (selectedRegions.has('farWest')) {
        zoomIn(-10, -10, 1);
      }
    }
  } else if (selectedRegions.size() === 4) {
    if (!selectedRegions.has('south')) {
      zoomIn(-10, -10, 1);
    } else if (!selectedRegions.has('southWest')) {
      zoomIn(-10, -10, 1);
    } else if (!selectedRegions.has('middleWest')) {
      zoomIn(-10, -10, 1);
    } else if (!selectedRegions.has('farWest')) {
      zoomIn(-10, -10, 1);
    }
  } else if (selectedRegions.size() === 5) {
    zoomIn(-10, -10, 1);
  }
}

function southZoom() {
  if (selectedRegions.size() === 1) {
    zoomIn(-550, -250, 1.5);
  } else if (selectedRegions.size() === 2) {
    if (selectedRegions.has('middleWest')) {
      zoomIn(-200, -20, 1.15);
    } else if (selectedRegions.has('southWest')) {
      zoomIn(-20, -100, 1.1);
    } else if (selectedRegions.has('farWest')) {
      zoomIn(-50, -30, 1.09);
    }
  } else if (selectedRegions.size() === 3 || selectedRegions.size() === 4) {
    zoomIn(-10, -10, 1);
  }
}

function middleWestZoom() {
  if (selectedRegions.size() === 1) {
    zoomIn(-700, -60, 2);
  } else if (selectedRegions.size() === 2) {
    if (selectedRegions.has('farWest')) {
      zoomIn(-100, -20, 1.1);
    } else if (selectedRegions.has('southWest')) {
      zoomIn(0, 10, 0.9);
    }
  } else if (selectedRegions.size() === 3) {
    if (selectedRegions.has('farWest')) {
      if (selectedRegions.has('southWest')) {
        zoomIn(-10, -10, 1);
      }
    }
  }
}

function farWestZoom() {
  if (selectedRegions.size() === 1) {
    zoomIn(80, -10, 1.1);
  } else if (selectedRegions.size() === 2) {
    if (selectedRegions.has('southWest')) {
      zoomIn(0, 25, 0.9);
    }
  }
}

$(window).contextmenu(function(e) {
  if ($(".zoomOutButton.hidden").length == 0) {
    e.preventDefault();
    $(".zoomOutButton").click();
  }
});
