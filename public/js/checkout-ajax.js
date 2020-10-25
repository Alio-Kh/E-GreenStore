$(document).ready(function() {
    document.querySelectorAll('.livraisonchecked').forEach(function(radio) {
        radio.addEventListener('change', function(e) {
            e.preventDefault();
            console.log(this);
            if (this.checked) {
                url = $('#livr_checked');
                var formD = new FormData()
                formD.append('val', this.value);
                var contentType = {
                    headers: {
                        "content-type": "multipart/form-data"
                    }
                };
                axios.post('/livr_checked', formD, contentType)
                    .then(function(response) {
                        const frais = response.data.frais;
                        const total = response.data.total;
                        $('#frais_de_livraison').text(frais);
                        $('#total_ttc').text(total);

                    }).catch(function(error) {

                    })
            }
        });
    });

});