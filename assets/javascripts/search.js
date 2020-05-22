// Advanced Search select2 functions
$(document).ready(function() {
    $('#status').select2({
        placeholder: "Select Status"
    });
    // $('#origin').select2({
    //     placeholder: "Select Origin"
    // });
    $('#sex').select2({
        placeholder: "Select Sex"
    });
    $('#occupation').select2({
        placeholder: "Select Occupation"
    });
    $('#event-type').select2({
        placeholder: "Select Event Type"
    });
    $('#place-type').select2({
        placeholder: "Select Place Type"
    });
    $('#country').select2({
        placeholder: "Select Country"
    });
    $('#source-type').select2({
        placeholder: "Select Source Type"
    });
    $('#project').select2({
        placeholder: "Select Project"
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
    $('#event-from').select2({
        placeholder: "Enter Start Year"
    });
    $('#event-to').select2({
        placeholder: "Enter End Year"
    });

    //Basic search page
    $('#life-event').select2({
        placeholder: "Select Life Event"
    });

    $('b[role="presentation"]').hide();
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

    // TODO::not sure what this is doing but it's just throwing an error
    // $(".s2-multiple").on('select2:select', function(e){
        // var id = e.params.data.id;
        // console.log(id)
        // var option = $(e.target).children(`[value=${id}]`);
        // option.detach();
        // $(e.target).append(option).change();
      // });

    // Fill dates from elasticsearch
    $.ajax({
        url: BASE_URL + 'api/getDateRange',
        method: "GET",
        'success': function (data) {
            data = JSON.parse(data);
            dates = []
            $.each(data, function(_, date) {
                dates.push(date['value_as_string'])
            });
            var min = Math.min.apply(Math, dates);
            var max = Math.max.apply(Math, dates);

            // Doing this for safety purposes
            if (min <= max) {
                for (var i = min; i <= max; i++) {
                    $("#event-from").append("<option value='"+i+"'>"+i+"</option>");
                    $("#event-to").append("<option value='"+i+"'>"+i+"</option>");
                }
            }
        }
    });
});

//On form submit "removes" the empty inputs so they don't show up in the $_GET
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

//Called by the Date input on Basic Search so that user can only enter numbers
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

//Combines the dates entered to make a date range
function combineDates() {
    if($('select#person-from').val() !== '' || $('select#person-to').val() !== ''){
        var personDate = $('select#person-from').val() + '-' + $('select#person-to').val();
    }
    if($('select#event-from').val() !== '' || $('select#event-to').val() !== ''){
        var eventDate = $('select#event-from').val() + '-' + $('select#event-to').val();
    }
    if($('select#place-from').val() !== '' || $('select#place-to').val() !== ''){
        var placeDate = $('select#place-from').val() + '-' + $('select#place-to').val();
    }
    $('.person-date-range').val(personDate);
    $('.event-date-range').val(eventDate);
    $('.place-date-range').val(placeDate);
}

function combineAgeRange() {
    if($('input#age-from').val() !== '' || $('input#age-to').val() !== ''){
        $('.age-range').val($('input#age-from').val() + '-' + $('input#age-to').val());
        $('input#age-to').val('')
        $('input#age-from').val('')
    }
}

//Calls the removeEmpty function and combineDates function
function handleSubmit(){
    combineDates();
    combineAgeRange();
    removeEmpty();
}


//Autocomplete function using vanilla js. Example from W3
function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
			/*check if the item starts with the same letters as the text field value:*/
			if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
				/*create a DIV element for each matching element:*/
				b = document.createElement("DIV");
				/*make the matching letters bold:*/
				//b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				b.innerHTML = arr[i];
				/*insert a input field that will hold the current array item's value:*/
				b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
				/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function(e) {
					/*insert the value for the autocomplete text field:*/
					inp.value = this.getElementsByTagName("input")[0].value;
					/*close the list of autocompleted values,
					(or any other open lists of autocompleted values:*/
					closeAllLists();
				});
				a.appendChild(b);
			}
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
        } else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
        } else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
				/*and simulate a click on the "active" item:*/
				if (x) x[currentFocus].click();
			}
        }
    });
    function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
			x[i].classList.remove("autocomplete-active");
		}
    }
    function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
      	for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			}
		}
	}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
}
