<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (!isset($_SESSION['user']['logged_in'])) {
    redirectTo('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
include "parts/head.php";
?>

<body>
    <?php
include "parts/header.php";
?>
    <main>
        <?php
include "parts/" . $_SESSION['user']['type'] . "dashboard.php";
?>
    </main>
    <?php
include "parts/footer.php";
?>
</body>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<?php
include "parts/scripts.php";
?>

</html>