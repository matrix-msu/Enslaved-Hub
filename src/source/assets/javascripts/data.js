var allProjectRecords = JSON.parse(counts);

const fuseOptions = {
  includeScore: true,
  useExtendedSearch: true,
  keys: ['label']
};
const projectDataFuse = new Fuse(allProjectRecords, fuseOptions);

var limit=10;
var offset = 1;
var result = {};
var searchQuery = '';
var filters = '';
var lastCategory = '';
var sort = "latest";
var startYearLimit = 0;
var endYearLimit = 9999;

$(document).ready(function(){
	search(searchQuery);
});

function search(searchQuery){
    if(searchQuery != ''){
        result = projectDataFuse.search("'"+searchQuery);
    }else{
        result = returnAllRecords(allProjectRecords);
    }
	result = result.sort(compareAtoZ('label'));
	var searchCardHtml = '';
    $.each(result, function(index,item){
        var record = item['item'];
        var storyImage = '';
		var startDate = '';
		if(record.startDate) startDate = record.startDate[0];
		var endDate = '';
		if(record.endDate) endDate = record.endDate[0];

		//filter out by Dates
		var numStartDate = parseInt(startDate);
		var numEndDate = parseInt(endDate);
		if(
			!(numStartDate >= startYearLimit && numStartDate <= endYearLimit) &&
			!(numEndDate >= startYearLimit && numEndDate <= endYearLimit) &&
			!(startYearLimit >= numStartDate && startYearLimit <= numEndDate) &&
			!(endYearLimit >= numStartDate && endYearLimit <= numEndDate) &&
			!(startYearLimit == 0 && endYearLimit == 9999)
		){
			return;
		}

		var contributors = '';
		if('hasContributor' in record){
			$.each(record['hasContributor'], function(index,item){
				contributors += `<p>${item}</p>`;
			});
		}
		var exReferences = '';
		if('hasExternalReference' in record){
			$.each(record['hasExternalReference'], function(index,item){
				exReferences += `<p><a href="${item}">${item}</a></p>`;
			});
		}
		
		
		var personNum = 0; var placeNum = 0;var eventNum = 0;var sourceNum = 0;
		if(!record['instance of']) return;
		$.each(record['instance of'], function(index,item){
			var label = item[0];
			var number = item[1];
			if( label == 'Person' ){
				personNum = number;
			}else if( label == 'Place' ){
				placeNum = number;
			}else if( label == 'Event' ){
				eventNum = number;
			}else if( label == 'Entity with Provenance' ){
				sourceNum = number;
			}
		});
		var visualizeUrl = BASE_URL+"visualizedata?type=Dashboard&field=po&proj="+record.label.replaceAll(' ','+');
		var dataUrl = BASE_URL+"search/all?projects="+record.label.replaceAll(' ','+')+"&limit=20&offset=0&sort_field=label.sort&display=people";
		var placeSearchUrl = BASE_URL+"search/all?projects="+record.label.replaceAll(' ','+')+"&limit=20&offset=0&sort_field=label.sort&display=places";
		var eventSearchUrl = BASE_URL+"search/all?projects="+record.label.replaceAll(' ','+')+"&limit=20&offset=0&sort_field=label.sort&display=events";
		var sourceSearchUrl = BASE_URL+"search/all?projects="+record.label.replaceAll(' ','+')+"&limit=20&offset=0&sort_field=label.sort&display=sources";
		

		
		
        searchCardHtml += `
		<div class="card contributor">
		      <h2>${record.label}</h2>
		      <div class="details">
		        <div class="detail">
		          <h5>Dates</h5>
		          <p>${startDate}-${endDate}</p>
		        </div>
		        <div class="detail">
		          <h5>Contributor(s)</h5>
		          ${contributors}
		        </div>
		        <div class="detail">
		          <h5>Related Data Resources</h5>
		          ${exReferences}
		        </div>
		      </div>
		      <div class="bottom">
		        <div class="connectionswrap">
		          <h5>Project Connections</h5>
		          <div class="connections">
		            <div class="card-icons">
		              <a href="${dataUrl}"><img src="../assets/images/Person-dark.svg"></a>
		              <span class="tooltip">Person</span>
		              <span>${personNum}</span>
		          </div>
		          <div class="card-icons">
		            <a href="${placeSearchUrl}"><img src="../assets/images/Place-dark.svg"></a>
		            <span class="tooltip">Place</span>
		            <span>${placeNum}</span>
		          </div>
		          <div class="card-icons">
		            <a href="${eventSearchUrl}"><img src="../assets/images/Event-dark.svg"></a>
		            <span class="tooltip">Event</span>
		            <span>${eventNum}</span>
		          </div>
		          <div class="card-icons">
		            <a href="${sourceSearchUrl}"><img src="../assets/images/Source-dark.svg"></a>
		            <span class="tooltip">Source</span>
		            <span>${sourceNum}</span>
		          </div>
		        </div>
		      </div>
		      <div class="buttons">
		        <a href="${dataUrl}" class="button">View Project Data</a>
		        <a href="${visualizeUrl}" class="button">View Project Visualization</a>
		      </div>
		    </div>
		  </div>
		`;
    });
    $('#projectData').html(searchCardHtml);

	// If href includes, change text to
  	$('a[href*="https://doi.org/10.25971/"]').each(function() {
	  	$(this).html("Journal of Slavery and Data Preservation Data Article");
  	});
  	$('a[href*="https://doi.org/10.7910/DVN/"]').each(function() {
			$(this).html("Original Data in Dataverse");
		});
}


function returnAllRecords(records){
    return records.map((doc, idx) => ({
      item: doc,
      score: 1,
      refIndex: idx
  }));
}

function compareAtoZ(sortField) {
    return function( a, b ){
        if ( a.item[sortField] < b.item[sortField] ){
            return -1;
        }
        if ( a.item[sortField] > b.item[sortField] ){
            return 1;
        }
        return 0;
    }
}

$('form').submit(function(e){
    e.preventDefault();
    searchQuery = $('#data-search').val();
    startYearLimit = parseInt($('#start-year').val());
	if(!startYearLimit) startYearLimit = 0;
    endYearLimit = parseInt($('#end-year').val());
	if(!endYearLimit) endYearLimit = 9999;
    search(searchQuery);
});
