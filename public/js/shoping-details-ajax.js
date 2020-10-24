$(document).ready(function() {
    $('.shopping-produit').on('click', function(e) {
        e.preventDefault();
        axios.get(this.href)
            .then(function(response) {
                var l = response.data.l;
                var qte = response.data.qte;
                var idProduit = response.data.idProduit;
                $('#qteProduit' + idProduit).val(qte);
                $('.panier-length').text(l);
                swal({
                    title: "Bienvenau chez nous",
                    text: "le produit est ajouté à votre panier ...!",
                    icon: "success",
                    button: "Ok!",
                });
            }).catch(function(error) {
                console.log(error);
            });
    });

});