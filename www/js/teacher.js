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
        "#teacher [data-teacher-id=" + getParam("id") + "] .details-btn"
      ).click();
    }, 10);
  }

  $(".filter input").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#teacher tr[data-teacher-id]").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $("#add-btn").on("click", function (e) {
    $modal = $("#add-modal");
    $modal.fadeIn();
  });

  $(".details-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#details-modal");

    $modal.data("teacherId", $row.data("teacherId"));

    $modal.find("span[name=email]").text($row.data("email"));
    $modal.find("span[name=phone]").text($row.data("phone"));
    $modal.find("span[name=name]").text($row.data("name"));
    $modal.find("span[name=bname]").text($row.data("batchName"));

    $modal.fadeIn();
  });

  $(".edit-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#edit-modal");
    $modal.find("input[name=id]").val($row.data("teacherId"));
    $modal.find("input[name=email]").val($row.data("email"));
    $modal.find("input[name=phone]").val($row.data("phone"));
    $modal.find("input[name=name]").val($row.data("name"));
    $modal.find("select[name=bid]").val($row.data("batchId"));
    $modal.fadeIn();
  });

  $(".delete-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#delete-modal");
    $modal.find("input[name=id]").val($row.data("teacherId"));
    $modal.fadeIn();
  });

  $("#detail-edit-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#teacher [data-teacher-id=" +
        $("#details-modal").data("teacherId") +
        "] .edit-btn"
    ).click();
  });

  $("#detail-delete-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#teacher [data-teacher-id=" +
        $("#details-modal").data("teacherId") +
        "] .delete-btn"
    ).click();
  });
});

function deleteTeacher(e) {
  e.preventDefault();

  var formData = $("#delete-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/teacher.php?action=delete",
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
          alert("Could not delete teacher!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not delete teacher!");
    });
  return false;
}

function updateTeacher(e) {
  e.preventDefault();

  var formData = $("#update-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/teacher.php?action=update",
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
          alert("Could not update teacher!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not update teacher!");
    });
  return false;
}

function addTeacher(e) {
  e.preventDefault();

  var formData = $("#add-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/teacher.php?action=add",
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
          alert("Could not add teacher!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not add teacher!");
    });
  return false;
}
