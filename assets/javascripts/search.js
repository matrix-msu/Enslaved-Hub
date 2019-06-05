// USED for Advance Search (Not sure what advance search, so I'm going to leave it as it is)
$(document).ready(function() {
    $('#status').select2({
        placeholder: "Select Status"
    });
    $('#origin').select2({
        placeholder: "Select Origin"
    });
    $('#sex').select2({
        placeholder: "Select Sex"
    });
    $('#ethno').select2({
        placeholder: "Search & Select Ethnodescriptor"
    });
    $('#occupation').select2({
        placeholder: "Select Occupation"
    });
    $('#type').select2({
        placeholder: "Select Event Type"
    });
    $('#city').select2({
        placeholder: "Select City"
    });
    $('#state').select2({
        placeholder: "Select Province,State,Colony"
    });
    $('#region').select2({
        placeholder: "Select Region"
    });
    $('#country').select2({
        placeholder: "Select Country"
    });
    $('#country').select2({
        placeholder: "Select Country"
    });
    $('.date-from').select2({
        placeholder: "From"
    });
    $('.date-to').select2({
        placeholder: "To"
    });
    $('#startYear').select2({
        placeholder: "Select or Input the Start Year"
    });
    $('#endYear').select2({
        placeholder: "Select or Input the End Year"
    });

    //Basic search page
    $('#life-event').select2({
        placeholder: "Select Life Event"
    });
    
    $('b[role="presentation"]').hide();
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

    $(".s2-multiple").on('select2:select', function(e){
        var id = e.params.data.id;
        var option = $(e.target).children('[value='+id+']');
        option.detach();
        $(e.target).append(option).change();
      });
});

function removeEmpty() {
    var form = $('form');
    var allInputs = form.find('input');
    var allSelects = form.find('select');
    var input,select, i, j;

    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !input.value) {
            input.setAttribute('name', '');
        }
    }
    for(j = 0; select = allSelects[j]; j++) {
        if(select.getAttribute('name') && !select.value) {
            select.setAttribute('name', '');
        }
    }
}

function validate(evt) {
    var theEvent = evt || window.event;
  
    // Handle paste
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }