$(document).ready(function () {
    $("#new_adress").hide();
    $("#delievery").hide();
    $("#payment").hide();


    $("#diff-acc").change(function () {
        if ($("#diff-acc").is(':checked')) {
            $("#new_adress").show(1000);
        } else {
            $("#new_adress").hide(1000);
        }
    });

    $("#delievery-label").click(function () {
        if (!$("#delievery").is(':visible')) {
            $("#payment").slideUp(1000);
            $("#billing").slideUp(1000);
        }
        $("#delievery").slideToggle(1000);
    });

    $("#payment-label").click(function () {
        if (!$("#payment").is(':visible')) {
            $("#delievery").slideUp(1000);
            $("#billing").slideUp(1000);

        }
        $("#payment").slideToggle(1000);

    });

    $("#billing-label").click(function () {
        if (!$("#billing").is(':visible')) {
            $("#delievery").slideUp(1000);
            $("#payment").slideUp(1000);

        }
        $("#billing").slideToggle(1000);
    });

    $("#next-delievery").click(function () {
        $("#delievery").slideUp(1000);
        $("#payment").slideDown(1000);
    });

    $("#next-billing").click(function () {
        $("#billing").slideUp(1000);
        $("#delievery").slideDown(1000);
    });

    $("#back-payment").click(function () {
        $("#payment").slideUp(1000);
        $("#delievery").slideDown(1000);
    });


    $("#back-delievery").click(function () {
        $("#billing").slideDown(1000);
        $("#delievery").slideUp(1000);
    });

    var codeP = document.getElementById('code_postale');
    var tel = document.getElementById('tel');
    var email = document.getElementById('email');


    codeP.addEventListener('keydown', (event) => {
        if (codeP.value != '' && tel.value != '' && email.value != '') {
            $("#next-billing").removeAttr('disabled');
        } else {
            $("#next-billing").attr("disabled", true);
        }
    });

    tel.addEventListener('keydown', (event) => {
        if (codeP.value != '' && tel.value != '' && email.value != '') {
            $("#next-billing").removeAttr('disabled');
        } else {
            $("#next-billing").attr("disabled", true);
        }
    });

    email.addEventListener('keydown', (event) => {
        if (codeP.value != '' && tel.value != '' && email.value != '') {
            $("#next-billing").removeAttr('disabled');
        } else {
            $("#next-billing").attr("disabled", true);
        }
    });


});