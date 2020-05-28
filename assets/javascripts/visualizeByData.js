var pie_age = "https://kibana.enslaved.org/app/kibana#/visualize/edit/c7b63e90-4829-11ea-803f-c5c32c8e80c2?embed=true&_g=()";
var pie_ageBS = "https://kibana.enslaved.org/app/kibana#/visualize/edit/2ac8cd60-5f1b-11ea-aee9-d7fe312c992a?embed=true&_g=()";
var bar_15 = "https://kibana.enslaved.org/app/kibana#/visualize/edit/9c6096f0-6246-11ea-9c31-37fdb38a3ce2?embed=true&_g=(filters%3A!())";
var line_ages = "https://kibana.enslaved.org/app/kibana#/visualize/edit/92b3dac0-58ca-11ea-bd95-ef0656102a8f?embed=true&_g=(filters%3A!())";
var bar_age = "https://kibana.enslaved.org/app/kibana#/visualize/edit/db186de0-58ce-11ea-bd95-ef0656102a8f?embed=true&_g=(filters%3A!())";

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

  addFields();

});

$( "#chart-type" ).change(function() {
  var type = $('#chart-type').val();
  var field = $('#chart-field').val();

  if(type == "pie" && field == "ages"){
    $('iframe').attr("src", pie_age);
  }
  else if(type == "pie" && field == "agesBySex"){
    $('iframe').attr("src", pie_ageBS);
  }
  else if(type == "bar" && field == "15yo"){
    $('iframe').attr("src", bar_15);
  }
  else if(type == "line" && field == "ages"){
    $('iframe').attr("src", line_ages);
  }
  else if(type == "bar" && field == "ages"){
    $('iframe').attr("src", bar_age);
  }
  else{
    $('iframe').attr("src", "");
  }
  addFields();
});
$( "#chart-field" ).change(function() {
  var type = $('#chart-type').val();
  var field = $('#chart-field').val();

  if(type == "pie" && field == "ages"){
    $('iframe').attr("src", pie_age);
  }
  else if(type == "pie" && field == "agesBySex"){
    $('iframe').attr("src", pie_ageBS);
  }
  else if(type == "bar" && field == "15yo"){
    $('iframe').attr("src", bar_15);
  }
  else if(type == "line" && field == "ages"){
    $('iframe').attr("src", line_ages);
  }
  else if(type == "bar" && field == "ages"){
    $('iframe').attr("src", bar_age);
  }
  else{
    $('iframe').attr("src", "");
  }

});

function addFields(){
  var type = $('#chart-type');

  $("#chart-field").empty();

  if (type.val() == "pie"){
    $('iframe').attr("src", pie_age);
    $("#chart-field").append("<option value='ages'>Ages</option>");
    $("#chart-field").append("<option value='agesBySex'>Ages - Sex</option>");
  }
  if (type.val() == "bar"){
    $('iframe').attr("src", bar_age);
    $("#chart-field").append("<option value='ages'>Ages</option>");
    $("#chart-field").append("<option value='15yo'>Ages - 15 years old</option>");
  }
  if (type.val() == "line"){
    $('iframe').attr("src", line_ages);
    $("#chart-field").append("<option value='ages'>Ages</option>");
  }
}
