$(document).ready(function() {
    document.querySelectorAll('a.js-like-produit').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            const icon = this.querySelector('i');
            axios.get(url).then(function(response) {
                const likes = response.data.likes;
                const id = response.data.id;
                if (icon.classList.contains('fa-heart')) {
                    icon.classList.replace('fa-heart', 'fa-heart-o');
                    $('.js-liks-produit' + id).text(likes);
                    $('.js-liks-market').text(eval($('.js-liks-market').text().trim()) - 1);
                } else {
                    icon.classList.replace('fa-heart-o', 'fa-heart');
                    $('.js-liks-produit' + id).text(likes);
                    $('.js-liks-market').text(eval('1+' + $('.js-liks-market').text().trim()));
                }
            }).catch(function(error) {
                if (error.response.status == 403) {
                    const message = error.response.data.message;
                    swal({
                        title: "Opsss!",
                        text: message,
                        icon: "warning",
                        button: "Ok!",
                    });
                } else {

                }
            })


        })
    })
});