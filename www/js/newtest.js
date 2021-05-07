function addTest(e) {
  e.preventDefault();

  var formData = $("form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/addtest.php",
    data: formData,
    xhrFields: {
      withCredentials: true,
    },
    beforeSend: function () {
      $("form :input").prop("disabled", true);
      loadingOverlay = $("<div class='loading-overlay'>").append(
        $("<div>")
          .append($("<span>Loading</span>"))
          .append(
            $(
              "<span class='loading'><span>.</span><span>.</span><span>.</span></span>)"
            )
          )
      );
      $("body").append(loadingOverlay);
    },
  })
    .done(function (response) {
      switch (response) {
        case "100":
          document.location.href = "dashboard.php";
          break;
        default:
          alert("Could not add marks!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not add marks!");
    });
  return false;
}
