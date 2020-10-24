$(document).ready(function() {
    document.querySelector('.AjaxForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formD = new FormData()
        formD.append('commentaire', $('#commentaire').val());
        formD.append('blog', $('#blog').val());
        var contentType = {
            headers: {
                "content-type": "multipart/form-data"
            }
        };
        axios.post($(".AjaxForm").attr('action'), formD, contentType)
            .then(function(response) {
                const $error = response.data.error;
                if ($error == "empty") {
                    $('.commentaire').text("");
                    $('.commentaire').append('<div id="error"class="alert alert-danger" role="alert"><strong>le commentaire est vide!!</strong>');
                } else {
                    const commentaire = response.data.commentaire;
                    const nom = response.data.nom;
                    const prenom = response.data.prenom;
                    const user = response.data.user;
                    $('.commentaires').append("<h5><i class='fa fa-user'></i>" + nom + " " + prenom + "<small>(" + user + ")</small></h5>");
                    if ($error != "empty") {
                        $('.commentaires').append("<p>" + commentaire + "</p>");
                        $('#commentaire').val("");
                    }
                    $('.commentaires').append('<div id="border" class="bordered_1px"></div>');
                    $('.commentaire').text("");
                }

            }).catch(function(error) {
                console.log(error);
            });
    });
});