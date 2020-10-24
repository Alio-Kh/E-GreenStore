$(document).ready(function () {
    // $('.').on('click', function(e) {
    $(".shipping").change(function (e) {
        if ($(".shipping").is(':checked')) {
            console.log('ezfez');
            e.preventDefault();
            axios.get(this.href)
                .then(function (response) {
                    console.log(response);

                }).catch(function (error) {
                    console.log(error);
                });
        }
    });

    // if ($('.shipping').is(':checked')) {

    // }

});