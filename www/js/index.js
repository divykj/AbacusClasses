$(function () {
  $("#login-cover").on("click", function (e) {
    if (e.target != this) return;

    $("#login-cover").fadeOut();
  });

  $("#login-btn").on("click", function (e) {
    $("#login-cover").fadeIn();
    $("#login-form input[name=email]").focus();
  });

  var intialDelay = 1000;
  var stepDelay = 600;
  var stepTime = 500;

  $("svg g g").css("transition-duration", stepTime + "ms");

  window.drawNumber = (number) => {
    var digits = number.toString().split("").reverse();
    for (var i = 0, len = digits.length; i < len; i++) {
      digits[i] = parseInt(digits[i], 10);
    }
    digits = digits.concat(Array(7).fill(0)).slice(0, 7);

    $(this)
      .delay(intialDelay)
      .queue((next) => {
        $(".p0 g").removeClass("move");
        $(
          ".p0 .b" + (digits[0] % 5) + ", .p0 .t" + Math.floor(digits[0] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p1 g").removeClass("move");
        $(
          ".p1 .b" + (digits[1] % 5) + ", .p1 .t" + Math.floor(digits[1] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p2 g").removeClass("move");
        $(
          ".p2 .b" + (digits[2] % 5) + ", .p2 .t" + Math.floor(digits[2] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p3 g").removeClass("move");
        $(
          ".p3 .b" + (digits[3] % 5) + ", .p3 .t" + Math.floor(digits[3] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p4 g").removeClass("move");
        $(
          ".p4 .b" + (digits[4] % 5) + ", .p4 .t" + Math.floor(digits[4] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p5 g").removeClass("move");
        $(
          ".p5 .b" + (digits[5] % 5) + ", .p5 .t" + Math.floor(digits[5] / 5)
        ).addClass("move");
        next();
      })
      .delay(stepDelay)
      .queue((next) => {
        $(".p6 g").removeClass("move");
        $(
          ".p6 .b" + (digits[6] % 5) + ", .p6 .t" + Math.floor(digits[6] / 5)
        ).addClass("move");
        next();
      });
  };

  window.animateAbacus = () => {
    drawNumber(Math.floor(Math.random() * 90000000 + 10000000));
  };

  animateAbacus();
  animateAbacus();
  animateAbacus();
  animateAbacus();
  animateAbacus();
});

function validateLogIn() {
  var email = $("#login-form input[name=email]").val().trim(),
    password = $("#login-form input[name=password]").val().trim();

  if (email == "" || password == "") {
    alert("All fields are required!");
  } else if (
    !/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/.test(email) &&
    email != "admin"
  ) {
    alert("Invalid email!");
  } else {
    return true;
  }
  return false;
}

function logIn(e) {
  e.preventDefault();
  if (validateLogIn()) {
    var formData = $("#login-form").serialize();
    $.ajax({
      type: "POST",
      url: "ajax/login.php",
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
          case "0":
            window.location.href = "dashboard.php";
            break;
          case "1":
            alert("Email does not exist!");
            break;
          case "2":
            alert("Please check your password!");
            break;
          case "50":
            alert("error");
            break;
          case "100":
            window.location.href = "dashboard.php";
            break;
          case "101":
            window.location.href = "dashboard.php";
            break;
          case "102":
            window.location.href = "dashboard.php";
            break;
        }
      })
      .always(function () {
        $("form :input").prop("disabled", false);
        loadingOverlay.remove();
      })
      .fail(function () {
        alert("Couldn't log you in!");
      });
  }
  return false;
}
