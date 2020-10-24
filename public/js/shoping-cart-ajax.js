$(document).ready(function() {
    $('.dec-qtybtn').on('click', function(e) {
        e.preventDefault();
        axios.get(this.href)
            .then(function(response) {
                var qte = response.data.qte;
                var totalTtc = response.data.totalTtc;
                var tva = response.data.tva;
                var total = response.data.total;
                var idProduit = response.data.idProduit;
                var totalP = eval(($('#shoping__cart__total' + idProduit).text().split("MAD")[0].trim() / (qte + 1)) * qte);
                $('.qteProduit' + idProduit).val(qte);
                $('#shoping__cart__total' + idProduit).text(totalP.toFixed(2) + "MAD");
                $('.total').text(total.toFixed(2) + "MAD");
                $('.tva').text(tva.toFixed(2) + "MAD");
                $('.totalTtc').text(totalTtc.toFixed(2) + "MAD");
                if (qte == 1) {
                    $('#dec-qtybtn' + idProduit).hide();
                } else {
                    $('#dec-qtybtn' + idProduit).show();

                }
            }).catch(function(error) {
                console.log(error);
            });
    });

    $('.inc-qtybtn').on('click', function(e) {
        e.preventDefault();
        axios.get(this.href)
            .then(function(response) {
                var msg = response.data.msg;
                if (msg != 0) {
                    var qte = response.data.qte;
                    var totalTtc = response.data.totalTtc;
                    var tva = response.data.tva;
                    var total = response.data.total;
                    var idProduit = response.data.idProduit;
                    var totalP = eval(($('#shoping__cart__total' + idProduit).text().split("MAD")[0].trim() / (qte - 1)) * qte);
                    $('.qteProduit' + idProduit).val(qte);
                    $('#shoping__cart__total' + idProduit).text(totalP.toFixed(2) + "MAD");
                    $('.total').text(total.toFixed(2) + "MAD");
                    $('.tva').text(tva.toFixed(2) + "MAD");
                    $('.totalTtc').text(totalTtc.toFixed(2) + "MAD");
                    if (qte > 1) {
                        $('#dec-qtybtn' + idProduit).show();
                    }
                } else {
                    swal({
                        title: "Bienvenau chez nous",
                        text: "Vous avez attendre la quantité max!",
                        icon: "info",
                        button: "Ok!",
                    });
                }
            }).catch(function(error) {
                console.log(error);
            });
    });

    $('.remove-prod').on('click', function(e) {
        e.preventDefault();
        axios.get(this.href)
            .then(function(response) {
                var totalTtc = response.data.totalTtc;
                var tva = response.data.tva;
                var total = response.data.total;
                var idProduit = response.data.idProduit;
                $('.total').text(total.toFixed(2) + "MAD");
                $('.tva').text(tva.toFixed(2) + "MAD");
                $('.totalTtc').text(totalTtc.toFixed(2) + "MAD");
                $('.produit' + idProduit).hide();
            }).catch(function(error) {
                console.log(error);
            });
    });

});