<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (isset($_SESSION['user']['logged_in'])) {
    if ($_SESSION['user']['type'] != "admin") {
        redirectTo('dashboard.php');
    }

} else {
    redirectTo('index.php');
}
?>

<?php
try {
    $conn = dbConnect();

    $batchQuery = $conn->prepare(
        "SELECT id, CONCAT(LEFT(batch.day, 3), ', ', LEFT(batch.timing, 5), ', Level ', batch.level) AS bname
                    FROM batch"
    );

    $batchQuery->execute();
    $batches = $batchQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log");
    // redirectTo('error');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "parts/head.php";?>

<body>
    <?php include "parts/header.php";?>
    <main>
        <h3 class="span6 fancy">All Students</h3>
        <div id="student" class="card span6">
            <table cellspacing="0" cellpadding="0" class="action">
                <tr head>
                    <td colspan="3" class="actions">
                        <a id="add-btn" class="button outline small">Add Student</a>
                    </td>
                    <td colspan="3" class="filter">
                        <label class="textfield">
                            <input type="text" name="filter" placeholder=" ">
                            <span>Filter</span>
                        </label>
                    </td>
                </tr>
                <tr head>
                    <th>&nbsp;</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Batch</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
try {
    $conn = dbConnect();

    $studentQuery = $conn->prepare(
        "SELECT student.id,
                        student.name,
                        student.phone,
                        student.email,
                        batch.id AS bid,
                        CONCAT(LEFT(batch.day, 3), ', ', LEFT(batch.timing, 5), ', Level ', batch.level) AS bname
                        FROM student
                        INNER JOIN batch ON student.batch_id = batch.id"
    );

    $studentQuery->execute();

    while ($student = $studentQuery->fetch(PDO::FETCH_ASSOC)) {
        ?>
                        <tr data-student-id="<?php echo $student['id'] ?>" data-batch-id="<?php echo $student['bid'] ?>" data-batch-name="<?php echo $student['bname'] ?>" data-phone="<?php echo $student['phone'] ?>" data-email="<?php echo $student['email'] ?>" data-name="<?php echo $student['name'] ?>">
                            <td><?php echo $student['id'] ?></td>
                            <td><?php echo $student['name'] ?></td>
                            <td><?php echo $student['phone'] ?></td>
                            <td><?php echo $student['email'] ?></td>
                            <td><a class="batch-btn button flat small"><?php echo $student['bname'] ?></a></td>
                            <td><a class="details-btn button flat icon"><i class="fa fa-eye"></i></a> <a class="edit-btn button flat icon"><i class="fa fa-pencil"></i></a> <a class="delete-btn button flat icon"><i class="fa fa-trash-o"></i></a></td>
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
        </div>
    </main>
    <?php
include "parts/footer.php";
?>

    <div id="details-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>Student Details</h3>
            <br>
            <section>
                <div class="field">
                    <span>Name:</span>
                    <span name="name"></span>
                </div>
                <div class="field">
                    <span>Phone:</span>
                    <span name="phone"></span>
                </div>
                <div class="field">
                    <span>Email:</span>
                    <span name="email"></span>
                </div>
                <div class="field">
                    <span>Batch:</span>
                    <span name="bname"></span>
                </div>
                <br>
                <a class="button blue" id="detail-edit-btn">Edit Student</a>
                <a class="button red" id="detail-delete-btn">Delete Student</a>
            </section>
        </div>
    </div>

    <div id="delete-modal" class="modal-cover">
        <div class="modal-box card small">
            <h4>Do you want to delete this student?</h4>
            <br>
            <form id="delete-form" onsubmit="deleteStudent(event)" method="post">
                <input type="hidden" name="id" value="0">
                <a class="button flat cancel-btn">Cancel</a>
                <button type="submit" class="button red">Delete</a>
            </form>
        </div>
    </div>

    <div id="edit-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>Update Student</h3>
            <form id="update-form" onsubmit="updateStudent(event)" method="post">
                <section>
                    <input type="hidden" name="id" value="0">
                    <label class="textfield">
                        <input type="text" name="name" placeholder=" " required>
                        <span>Name</span>
                    </label><br>
                    <label class="textfield">
                        <input type="number" name="phone" placeholder=" " required>
                        <span>Phone No</span>
                    </label><br>
                    <label class="textfield">
                        <input type="text" name="email" placeholder=" " required>
                        <span>Email</span>
                    </label><br>
                    <label class="dropdown">
                        <select name="bid" required>
                            <?php
foreach ($batches as $batch) {
    ?>
                                <option value="<?php echo $batch['id'] ?>"><?php echo $batch['bname'] ?></option>
                            <?php
}
?>
                        </select>
                        <span>Batch</span>
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button blue" type="submit">Update</button>
                </section>
            </form>
        </div>
    </div>

    <div id="add-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>New Student</h3>
            <form id="add-form" onsubmit="addStudent(event)" method="post">
                <section>
                    <label class="textfield">
                        <input type="text" name="name" placeholder=" " required>
                        <span>Name</span>
                    </label><br>
                    <label class="textfield">
                        <input type="number" name="phone" placeholder=" " required>
                        <span>Phone No</span>
                    </label><br>
                    <label class="textfield">
                        <input type="text" name="email" placeholder=" " required>
                        <span>Email</span>
                    </label><br>
                    <label class="dropdown">
                        <select name="bid" required>
                            <?php
foreach ($batches as $batch) {
    ?>
                                <option value="<?php echo $batch['id'] ?>"><?php echo $batch['bname'] ?></option>
                            <?php
}
?>
                        </select>
                        <span>Batch</span>
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button" type="submit">Add Student</button>
                </section>
            </form>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<?php
include "parts/scripts.php";
?>

</html>