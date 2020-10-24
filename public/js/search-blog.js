$(document).ready(function() {
    $("#buttonSearch").css('visibility', 'hidden')
    $('#search').keyup(function() {
        if ($("#search").val()) {
            $("#buttonSearch").css('visibility', 'visible')
        } else {
            $("#buttonSearch").css('visibility', 'hidden')
        }
    });
});