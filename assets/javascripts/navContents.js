$(document).ready(function () {
    // Update json cache files for Navigations and webpages
    $.ajax({
        url: BASE_URL + "api/getWebPages",
        type: "GET",
        data: {update: true},
        'success': function (data) {
            data = JSON.parse(data);

            if(data === "updated") console.log("webpages and navcontent cache files updated successfully");
            else if(data === "similar") console.log("webpages and navcontent cache files are up to date");
            else console.log("Failed to update webpages and navcontent cache files");
        }
    });
});
