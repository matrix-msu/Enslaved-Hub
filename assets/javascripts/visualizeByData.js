//Maranhao graphs
var bar_ef_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/dcc4ad80-9b99-11ea-ac69-1d41bbb57104?embed=true&_g=(filters:!())";
var bar_ec_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/b0f7a690-aa7f-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_em_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/f1454180-9b9d-11ea-ac69-1d41bbb57104?embed=true&_g=(filters:!())";
var pie_ef_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/44c9dad0-9ba5-11ea-ac69-1d41bbb57104?embed=true&_g=(filters:!())";
var pie_f_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/adba8ce0-aa93-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ethno_Maranhão = "https://kibana.enslaved.org/app/kibana#/visualize/edit/3922b880-ab33-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
//Louisiana graphs
var bar_ef_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/a25a3d80-abfd-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_ec_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/0e040270-ac01-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_em_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/addbf210-ac08-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_mf_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/e3241b70-ac10-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ef_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/c6b88900-ac13-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_f_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/34c6c040-ac16-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ethno_Louisiana = "https://kibana.enslaved.org/app/kibana#/visualize/edit/8442e3d0-ab24-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
//Voyages
var bar_ef_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/90a24d20-abfe-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_ec_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/a925f2d0-ac02-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_em_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/6af49b40-ac09-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ef_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/773cad10-ac14-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_f_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/f886e730-ac16-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ethno_Voyages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/374848a0-ab32-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
//Free Blacks
var pie_f_Free = "https://kibana.enslaved.org/app/kibana#/visualize/edit/4d6e21f0-ac17-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ethno_Free = "https://kibana.enslaved.org/app/kibana#/visualize/edit/9304eef0-ab32-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
//All Projects
var bar_ef_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/dadc5e20-af24-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_ec_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/7ea56240-af25-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_em_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/d4e37a70-af25-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var bar_mf_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/bff2f450-af26-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ef_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/126e90d0-af28-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_f_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/4c3e1b40-af29-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";
var pie_ethno_All = "https://kibana.enslaved.org/app/kibana#/visualize/edit/fa7da680-af29-11ea-a46c-a3979884a476?embed=true&_g=(filters:!())";

var concat_url = BASE_URL + "visualizeByData";
var url = new URL(concat_url);
var search_params = url.searchParams;

var barFields = {
  'ef': "<option value='ef'>Enslaved Female</option>",
  'ec': "<option value='ec'>Enslaved Child</option>",
  'em': "<option value='em'>Enslaved Male</option>",
  'mf': "<option value='mf'>Master Female</option>"
};
var pieFields = {
  'ef': "<option value='ef'>Enslaved Female</option>",
  'f': "<option value='f'>Female</option>",
  'ethno': "<option value='ethno'>Ethnodescriptor</option>"
}
var projects = {
  'All': "<option value='All Projects'>All Projects</option>",
  'Louisiana': "<option value='Louisiana Slave Database'>Louisiana Slave Database</option>",
  'Free': "<option value='Free Blacks Database'>Free Blacks Database</option>",
  'Voyages': "<option value='Voyages: The Trans-Atlantic Slave Trade Database'>Voyages: The Trans-Atlantic Slave Trade Database</option>",
  'Maranhão': "<option value='Maranhão Inventories Slave Database'>Maranhão Inventories Slave Database</option>"
}

$(document).ready( function() {
  $('#chart-type').select2({
      placeholder: "Select Chart Type"
  });
  $('#chart-field').select2({
      placeholder: "Select Chart Field"
  });
  $('#chart-project').select2({
      placeholder: "Select Project"
  });
  $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');


  const params = new URLSearchParams(window.location.search);
  if (params.has('type') && params.has('field') && params.has('proj')){
    var type = params.get('type');
    var field = params.get('field');
    var proj = params.get('proj');

    var fig = type.concat("_", field, "_", proj);
    fig = eval('`'+fig+'`');
    $('iframe').attr("src", window[fig]);

    addFieldsT(type, field, proj);
  }
  else{
    addFieldsT($('#chart-type').val());
  }

});

$( "#chart-type" ).change(function() {
  changeIframe();

  addFieldsT($('#chart-type').val());
});
$( "#chart-field" ).change(function() {
  changeIframe();

});

$( "#chart-project" ).change(function() {
  changeIframe();

});

function addFieldsT(type, field = "default", proj = "default"){
  $("#chart-field").empty();
  $("#chart-project").empty();

  if(field == "default" && proj == "default"){
    if (type == "bar"){
      $('iframe').attr("src", bar_ef_All);
      //Add bar fields to dropdown
      for (var key in barFields){
        $("#chart-field").append(barFields[key]);
      }

    }
    if (type == "pie"){
      $('iframe').attr("src", pie_ef_All);
      //Add pie fields to dropdown
      for (var key in pieFields){
        $("#chart-field").append(pieFields[key]);
      }
    }
    //Add projects to dropdown
    for (var key in projects){
      $("#chart-project").append(projects[key]);
    }
  }
  else{
    $("#chart-type").empty();

    if (type == "bar"){
      //Add bar fields to dropdown
      $("#chart-field").append(barFields[field]);
      for (var key in barFields){
        if(barFields[key] != barFields[field]){
          $("#chart-field").append(barFields[key]);
        }
      }
      //Add types to dropdown
      $("#chart-type").append("<option value='bar'>Bar</option>");
      $("#chart-type").append("<option value='pie'>Pie</option>");
    }
    if (type == "pie"){
      //Add pie fields to dropdown
      $("#chart-field").append(pieFields[field]);
      for (var key in pieFields){
        if(pieFields[key] != pieFields[field]){
          $("#chart-field").append(pieFields[key]);
        }
      }
      //Add types to dropdown
      $("#chart-type").append("<option value='pie'>Pie</option>");
      $("#chart-type").append("<option value='bar'>Bar</option>");
    }
    //Add projects to dropdown
    $("#chart-project").append(projects[proj]);
    for (var key in projects){
      if(projects[key] != projects[proj]){
        $("#chart-project").append(projects[key]);
      }
    }
  }
}


function changeIframe(){
  var type = $('#chart-type').val();
  var field = $('#chart-field').val();
  var proj = $('#chart-project').val().split(" ");
  proj = proj[0];
  if(proj == "Voyages:"){
    proj = "Voyages";
  }
  var fig = type.concat("_", field, "_", proj);
  fig = eval('`'+fig+'`');
  $('iframe').attr("src", window[fig]);

  //change url
  search_params.set('type', type);
  search_params.set('field', field);
  search_params.set('proj', proj);

  url.search = search_params.toString();
  var new_url = url.toString();
  window.history.replaceState(0, "", new_url);
}
