// ~~~~~~~~~~~~~~~~~~~~~~ //
//  Search Select Boxes   //
// ~~~~~~~~~~~~~~~~~~~~~~ //

// this is used for advanced search
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
    // $('.s2-multiple').select2();
    $('b[role="presentation"]').hide();
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

    // $('.s2-multiple').on('select2:opening select2:closing', function( event ) {
    //     var $searchfield = $(this).parent().find('.select2-search__field');
    //     $searchfield.prop('disabled', true);
    // });
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