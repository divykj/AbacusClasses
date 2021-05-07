<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (!isset($_SESSION['user']['logged_in'])) {
    redirectTo("index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
	<main>
		Hello, <?php echo $_SESSION['user']['name']; ?>
		<button onclick="logOut(event)">Log Out</button>
	</main>

	<script src="extra/jquery-3.2.1.min.js"></script>

	<script>
		function logOut(e) {
			e.preventDefault();
			$.ajax({
					url: "ajax/logout.php",
					xhrFields: {
						withCredentials: true
					}
				})
				.done(function(response) {
					switch (response) {
						case '50':
							alert("Error");
							break;
						case '100':
							location.reload();
							break;
						default:
					}
				})
				.fail(function() {
					alert('Couldn\'t log you out!');
				});
		}
	</script>

</body>

</html>