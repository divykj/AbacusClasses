$(function () {
  getParam = function (name) {
    var results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
      window.location.href
    );
    if (results == null) {
      return null;
    }
    return decodeURI(results[1]) || 0;
  };

  if (getParam("id") != null) {
    setTimeout(function () {
      $(
        "#student [data-student-id=" + getParam("id") + "] .details-btn"
      ).click();
    }, 10);
  }

  $(".filter input").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#student tr[data-student-id]").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $(".batch-btn").on("click", function (e) {
    window.location.href =
      "batch.php?id=" + $(this).parent().parent().data("batchId");
  });

  $("#add-btn").on("click", function (e) {
    $modal = $("#add-modal");
    $modal.fadeIn();
  });

  $(".details-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#details-modal");

    $modal.data("studentId", $row.data("studentId"));

    $modal.find("span[name=email]").text($row.data("email"));
    $modal.find("span[name=phone]").text($row.data("phone"));
    $modal.find("span[name=name]").text($row.data("name"));
    $modal.find("span[name=bname]").text($row.data("batchName"));

    $modal.fadeIn();
  });

  $(".edit-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#edit-modal");
    $modal.find("input[name=id]").val($row.data("studentId"));
    $modal.find("input[name=email]").val($row.data("email"));
    $modal.find("input[name=phone]").val($row.data("phone"));
    $modal.find("input[name=name]").val($row.data("name"));
    $modal.find("select[name=bid]").val($row.data("batchId"));
    $modal.fadeIn();
  });

  $(".delete-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#delete-modal");
    $modal.find("input[name=id]").val($row.data("studentId"));
    $modal.fadeIn();
  });

  $("#detail-edit-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#student [data-student-id=" +
        $("#details-modal").data("studentId") +
        "] .edit-btn"
    ).click();
  });

  $("#detail-delete-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#student [data-student-id=" +
        $("#details-modal").data("studentId") +
        "] .delete-btn"
    ).click();
  });
});

function deleteStudent(e) {
  e.preventDefault();

  var formData = $("#delete-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/student.php?action=delete",
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
          document.location.reload();
          break;
        default:
          alert("Could not delete student!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not delete student!");
    });
  return false;
}

function updateStudent(e) {
  e.preventDefault();

  var formData = $("#update-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/student.php?action=update",
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
      console.log(response);
      switch (response) {
        case "100":
          document.location.reload();
          break;
        default:
          alert("Could not update student!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not update student!");
    });
  return false;
}

function addStudent(e) {
  e.preventDefault();

  var formData = $("#add-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/student.php?action=add",
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
          document.location.reload();
          break;
        default:
          alert("Could not add student!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not add student!");
    });
  return false;
}
