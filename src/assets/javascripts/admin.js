
$('#theme-select').change(function(){
    $.ajax({
        url: 'api/admin',
        method: "GET",
        data: {theme: $(this).val()},
        //async: false,
        'success': function (data) {
            console.log(data)
            window.location.reload();
        }
    });
});