$(document).ready(function () {
  document
    .querySelector(".subscribersForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      var formData = new FormData();
      formData.append("email", $("#subscriberEmail").val());
      var type = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      axios
        .post($(".subscribersForm").attr("action"), formData, type)
        .then(function (response) {
          const $error = response.data.error;
          if ($error == "empty") {
            swal({
              title: "Bienvenu",
              text: "Maintenant vous etes abonnés à notre newsletter",
              icon: "success",
              button: "Ok!",
            });
          } else {
            const error = response.data.error;
            swal({
              title: "Fait attention",
              text: error,
              icon: "warning",
              button: "Ok!",
            });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    });
});
