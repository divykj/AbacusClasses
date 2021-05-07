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
      $("#batch [data-batch-id=" + getParam("id") + "] .details-btn").click();
    }, 10);
  }

  $(".filter input").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#batch tr[data-batch-id]").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  $(".teacher-btn").on("click", function (e) {
    window.location.href =
      "teacher.php?id=" + $(this).parent().parent().data("teacherId");
  });

  $("#add-btn").on("click", function (e) {
    $modal = $("#add-modal");

    var date = "";
    var today = new Date();
    date += today.getFullYear();
    date += "-" + String(today.getMonth() + 1).padStart(2, "0");
    date += "-" + String(today.getDate()).padStart(2, "0");

    $modal.find("input[name=sdate]").val(date);
    $modal.fadeIn();
  });

  $(".details-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#details-modal");

    $modal.data("batchId", $row.data("batchId"));

    $modal.find("span[name=sdate]").text($row.data("startDate"));
    $modal.find("span[name=level]").text($row.data("level"));
    $modal.find("span[name=time]").text($row.data("time"));
    $modal.find("span[name=day]").text($row.data("day"));
    $modal.find("span[name=tname]").text($row.data("teacherName"));

    $modal.fadeIn();
  });

  $(".edit-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#edit-modal");
    $modal.find("input[name=id]").val($row.data("batchId"));
    $modal.find("input[name=level]").val($row.data("level"));
    $modal.find("input[name=time]").val($row.data("time"));
    $modal.find("select[name=day]").val($row.data("day"));
    $modal.find("select[name=tid]").val($row.data("teacherId"));
    $modal.fadeIn();
  });

  $(".delete-btn").on("click", function (e) {
    $row = $(this).parent().parent();
    $modal = $("#delete-modal");
    $modal.find("input[name=id]").val($row.data("batchId"));
    $modal.fadeIn();
  });

  $("#detail-edit-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#batch [data-batch-id=" +
        $("#details-modal").data("batchId") +
        "] .edit-btn"
    ).click();
  });

  $("#detail-delete-btn").on("click", function (e) {
    $(this).parent().parent().parent().click();
    $(
      "#batch [data-batch-id=" +
        $("#details-modal").data("batchId") +
        "] .delete-btn"
    ).click();
  });
});

function deleteBatch(e) {
  e.preventDefault();

  var formData = $("#delete-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/batch.php?action=delete",
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
          alert("Could not delete batch!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not delete batch!");
    });
  return false;
}

function updateBatch(e) {
  e.preventDefault();

  var formData = $("#update-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/batch.php?action=update",
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
          console.log(response);
          alert("Could not update batch!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not update batch!");
    });
  return false;
}

function addBatch(e) {
  e.preventDefault();

  var formData = $("#add-form").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/batch.php?action=add",
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
          alert("Could not add batch!");
          break;
      }
    })
    .always(function () {
      $("form :input").prop("disabled", false);
      loadingOverlay.remove();
    })
    .fail(function () {
      alert("Could not add batch!");
    });
  return false;
}
