<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (!isset($_SESSION['user']['logged_in'])) {
    redirectTo('index.php');
}

if ($_SESSION['user']['type'] != "teacher") {
    redirectTo('dashboard.php');
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

try {
    $conn = dbConnect();

    $testTypeQuery = $conn->prepare(
        "SELECT * FROM testtype"
    );

    $testTypeQuery->execute();
    $testTypes = $testTypeQuery->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log");
    // redirectTo('error');
    exit();
}

?>

        <h3 class="fancy span6">Add Test</h3>
        <div class="card span6">
            <form onsubmit="addTest(event)" class="grid">
                <input type="hidden" name="batchid" value="<?php echo $_GET['batch'] ?>">
                <label class="dropdown span3">
                    <select name="testtypeid" required>
                    <?php
foreach ($testTypes as $testType) {
    ?>
                            <option value="<?php echo $testType['id'] ?>"><?php echo $testType['name'] ?></option>
                        <?php
}
?>
                    </select>
                    <span>Test Type</span>
                </label>
                <label class="textfield span3">
                    <input type="text" name="date" placeholder=" " value="<?php echo date('Y-m-d') ?>" required>
                    <span>Date</span>
                </label>
                <table cellspacing="0" cellpadding="0" >
                    <tr head>
                        <th>Student</th>
                        <th>Marks</th>
                    </tr>

                    <?php
try {
    $conn = dbConnect();

    $studentQuery = $conn->prepare(
        "SELECT id, name
                                FROM student
                                WHERE batch_id = :bid"
    );

    $studentQuery->bindParam(':bid', $_GET['batch']);

    $studentQuery->execute();

    while ($student = $studentQuery->fetch(PDO::FETCH_ASSOC)) {
        ?>
                                <tr data-student-id="<?php echo $student['id'] ?>">
                                    <td><?php echo $student['name'] ?></td>
                                    <td>
                                    <label class="textfield">
                                        <input type="number" name="student<?php echo $student['id'] ?>" placeholder=" " required>
                                        <span>Marks</span>
                                    </label>
                                    </td>
                                </tr>
                            <?php
}

} catch (PDOException $e) {
    error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log");
    // redirectTo('error');
    exit();
}
?>


                </table>

                <input type="submit" class="button blue span2" value="Add Marks">
            </form>
        </div>
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