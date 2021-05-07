<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (isset($_SESSION['user']['logged_in'])) {
    if ($_SESSION['user']['type'] != "admin") {
        redirectTo("dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
	<main>
		<form id="login-form" onsubmit="LogIn(event)" method="post">
			<section>
				<label for="email">
					<span>Email:</span>
					<input type="text" name="email" required>
				</label><br>
				<label for="password">
					<span>Password:</span>
					<input type="password" name="password" required>
				</label><br>
				<label for="remember">
					<input type="checkbox" name="remember" id="remember" value="remember" checked="checked">
					<span>Remember Me</span>
				</label><br>
				<button id="login-submit" type="submit">Log In</button>
			</section>
		</form>
		<br><br>
		<form id="signup-form" onsubmit="Register(event)" method="post">
			<section>
				<label for="type">
					<input type="radio" name="type" value="student" checked> Student<br>
					<input type="radio" name="type" value="teacher"> Teacher<br>
				</label>
				<label for="name">
					<span>Name:</span>
					<input type="text" name="name" required>
				</label><br>
				<label for="email">
					<span>Email:</span>
					<input type="text" name="email" required>
				</label><br>
				<label for="phone">
					<span>Phone:</span>
					<input type="text" name="phone" required>
				</label><br>
				<button id="signup-submit" type="submit">Register</button>
			</section>
		</form>
	</main>

	<script src="extra/jquery-3.2.1.min.js"></script>


	<script>
		function validateLogIn() {
			var email = $("#login-form input[name=email]").val().trim(),
				password = $("#login-form input[name=password]").val().trim();

			if (email == "" || password == "") {
				alert("All fields are required!");
			} else if (!/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/.test(email)) {
				alert("Invalid email!");
			} else {
				return true;
			}
			return false;
		}

		function LogIn(e) {
			e.preventDefault();
			if (validateLogIn()) {
				var formData = $("#login-form").serialize();
				$.ajax({
						type: 'POST',
						url: 'ajax/login.php',
						data: formData,
						xhrFields: {
							withCredentials: true
						},
						beforeSend: function() {
							$("form :input").prop("disabled", true);
							loadingOverlay = $("<div class='loading-overlay'>")
								.append($("<div>")
									.append($("<span>Loading</span>"))
									.append($("<span class='loading'><span>.</span><span>.</span><span>.</span></span>)"))
								);
							$('body').append(loadingOverlay);
						},
					})
					.done(function(response) {
						switch (response) {
							case '0':
								window.location.href = "dashboard.php";
								break;
							case '1':
								alert('Email does not exist!');
								break;
							case '2':
								alert('Please check your password!');
								break;
							case '50':
								alert("error");
								break;
							case '100':
								window.location.href = "dashboard.php";
								break;
							case '101':
								window.location.href = "dashboard.php";
								break;
							case '102':
								window.location.href = "dashboard.php";
								break;
						}
						console.log(response);
					})
					.always(function() {
						$("form :input").prop("disabled", false);
						loadingOverlay.remove();
					})
					.fail(function() {
						alert('Couldn\'t log you in!');
					});
			}
			return false;
		}

		function validateSignUp() {
			var name = $("#signup-form input[name=name]").val().trim();
			var email = $("#signup-form input[name=email]").val().trim();
			var password = $("#signup-form input[name=phone]").val().trim();

			if (name == "" || email == "" || password == "") {
				alert("All fields are required!");
			} else if (!(/^[a-zA-Z ]{1,50}$/.test(name))) {
				alert("Name should only contain alphabets and should be less than 20 letters.");
			} else if (!/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/.test(email)) {
				alert("Your email-id is not valid.");
			} else if (!/^[0-9]{10}$/.test(password)) {
				alert("Password contains invalid character.");
			} else {
				return true;
			}
			return false;
		}

		function Register(e) {
			e.preventDefault();
			if (validateSignUp()) {
				var formData = $("#signup-form").serialize();
				var type = $("#signup-form input[name=type]:checked").val().trim();
				$.ajax({
						type: 'POST',
						url: 'ajax/add' + type + '.php',
						data: formData,
						beforeSend: function() {
							$("#signup-form :input").prop("disabled", true);
							loadingOverlay = $("<div class='loading-overlay'>")
								.append($("<div>")
									.append($("<span>Loading</span>"))
									.append($("<span class='loading'><span>.</span><span>.</span><span>.</span></span>)"))
								);
							$('body').append(loadingOverlay);
						}
					})
					.done(function(response) {
						switch (response) {
							case '0':
								window.location.href = "dashboard.php";
								break;
							case '1':
								alert('Email already registered!');
								break;
							case '50':
								alert("Error");
								break;
							case '100':
								alert('Successfully registered!');
								break;
						}
					})
					.always(function() {
						$("#signup-form :input").prop("disabled", false);
						loadingOverlay.remove();
					})
					.fail(function() {
						alert('Couldn\'t sign you up!');
					});
			}
			return false;
		}
	</script>

</body>

</html>
