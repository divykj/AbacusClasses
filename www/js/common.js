$(document).on("click", '[href^="#"]', function (e) {
  var id = $(this).attr("href");

  var $id = $(id);
  if ($id.length === 0) {
    return;
  }

  e.preventDefault();

  var headerH = $("header").outerHeight();

  var pos = $id.offset().top - headerH + 32;

  $("body, html").animate({ scrollTop: pos });
});

$(".modal-cover").on("click", function (e) {
  if (e.target != this) return;

  $(this).fadeOut();
});

$(".modal-cover .cancel-btn").on("click", function (e) {
  $(".modal-cover").fadeOut();
});

$(function () {
  var header = $("header");

  $(window).scroll(function () {
    if ($(window).scrollTop() < 30) {
      header.addClass("expanded");
    } else {
      header.removeClass("expanded");
    }
  });
});

function logOut(e) {
  e.preventDefault();
  $.ajax({
    url: "ajax/logout.php",
    xhrFields: {
      withCredentials: true,
    },
  })
    .done(function (response) {
      switch (response) {
        case "50":
          alert("Error");
          break;
        case "100":
          location.reload();
          break;
        default:
      }
    })
    .fail(function () {
      alert("Couldn't log you out!");
    });
}
