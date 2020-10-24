$(document).ready(function() {
    $('.shopping-produit').on('click', function(e) {
        e.preventDefault();
        axios.get(this.href)
            .then(function(response) {
                var l = response.data.l;
                var max = response.data.max;
                $('.panier-length').text(l);
                if (max == 0) {
                    swal({
                        title: "Bienvenau chez nous",
                        text: "Vous avez attendre la quantité max!",
                        icon: "info",
                        button: "Ok!",
                    });
                } else {
                    swal({
                        title: "Bienvenau chez nous",
                        text: "Le produit est ajouté à votre panier ...!",
                        icon: "success",
                        button: "Ok!",
                    });
                }

            }).catch(function(error) {
                console.log(error);
            });
    });

});