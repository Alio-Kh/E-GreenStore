$(document).ready(function() {
    document.querySelector('.AjaxForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formD = new FormData()
        formD.append('commentaire', $('#commentaire').val());
        formD.append('produit', $('#produit').val());
        var contentType = {
            headers: {
                "content-type": "multipart/form-data"
            }
        };
        axios.post($(".AjaxForm").attr('action'), formD, contentType)
            .then(function(response) {
                const $error = response.data.error;
                if ($error == "notClient") {
                    $('.commentaire').text("");
                    $('.commentaire').append('<div id="error"class="alert alert-danger" role="alert"><strong>Vous n\'êtes pas un client !!</strong>');
                } else {
                    if ($error == "empty") {
                        $('.commentaire').text("");
                        $('.commentaire').append('<div id="error"class="alert alert-danger" role="alert"><strong>le commentaire est vide!!</strong>');
                    } else {
                        const commentaire = response.data.commentaire;
                        const nom = response.data.nom;
                        const prenom = response.data.prenom;
                        $('.commentaires').append("<h6>" + nom + " " + prenom + "</h6>");
                        if ($error != "empty") {
                            $('.commentaires').append("<p>" + commentaire + "</p>");
                            $('#commentaire').val("");
                        }
                        $('.commentaires').append('<div id="border" class="bordered_1px"></div>');
                        $('.commentaire').text("");
                        var nbrc = eval('1+' + $('#nbrCommentaires').text().trim());
                        $('#nbrCommentaires').text(nbrc);
                    }
                }
            }).catch(function(error) {
                console.log(error);
            });
    });
});