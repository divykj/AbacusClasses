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

    $teacherQuery = $conn->prepare(
        "SELECT id, name
                    FROM teacher"
    );

    $teacherQuery->execute();
    $teachers = $teacherQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log");
    redirectTo('error');
    exit();
}

?>
        <h3 class="span6 fancy">All Batches</h3>
        <div id="batch" class="card span6">
            <table cellspacing="0" cellpadding="0" class="action">
                <tr head>
                    <td colspan="3" class="actions">
                        <a id="add-btn" class="button outline small">Add Batch</a>
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
                    <th>Timing</th>
                    <th>Level</th>
                    <th>Students</th>
                    <th>Teacher</th>
                    <th></th>
                </tr>
                <?php
try {
    $conn = dbConnect();

    $batchQuery = $conn->prepare(
        "SELECT batch.id,
                        start_date,
                        level,
                        CONCAT(LEFT(day, 3), ', ', LEFT(timing, 5)) AS timing,
                        timing AS time,
                        day,
                        (SELECT COUNT(*) FROM student
                            WHERE batch_id = batch.id) AS students,
                        teacher_id AS tid,
                        teacher.name AS tname
                        FROM batch
                        INNER JOIN teacher ON batch.teacher_id = teacher.id"
    );

    $batchQuery->execute();

    while ($batch = $batchQuery->fetch(PDO::FETCH_ASSOC)) {
        ?>
                        <tr data-batch-id="<?php echo $batch['id'] ?>" data-teacher-id="<?php echo $batch['tid'] ?>" data-teacher-name="<?php echo $batch['tname'] ?>" data-time="<?php echo $batch['time'] ?>" data-day="<?php echo $batch['day'] ?>" data-level="<?php echo $batch['level'] ?>" data-start-date="<?php echo $batch['start_date'] ?>">
                            <td><?php echo $batch['id'] ?></td>
                            <td><?php echo $batch['timing'] ?></td>
                            <td><?php echo $batch['level'] ?></td>
                            <td><?php echo $batch['students'] ?></td>
                            <td><a class="teacher-btn button flat small"><?php echo $batch['tname'] ?></a></td>
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
            <h3>Batch Details</h3>
            <br>
            <section>
                <div class="field">
                    <span>Start Date:</span>
                    <span name="sdate"></span>
                </div>
                <div class="field">
                    <span>Day:</span>
                    <span name="day"></span>
                </div>
                <div class="field">
                    <span>Time:</span>
                    <span name="time"></span>
                </div>
                <div class="field">
                    <span>Level:</span>
                    <span name="level"></span>
                </div>
                <div class="field">
                    <span>Teacher:</span>
                    <span name="tname"></span>
                </div>
                <br>
                <a class="button blue" id="detail-edit-btn">Edit Batch</a>
                <a class="button red" id="detail-delete-btn">Delete Batch</a>
            </section>
        </div>
    </div>

    <div id="delete-modal" class="modal-cover">
        <div class="modal-box card small">
            <h4>Do you want to delete this batch?</h4>
            <br>
            <form id="delete-form" onsubmit="deleteBatch(event)" method="post">
                <input type="hidden" name="id" value="0">
                <a class="button flat cancel-btn">Cancel</a>
                <button type="submit" class="button red">Delete</a>
            </form>
        </div>
    </div>

    <div id="edit-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>Update Batch</h3>
            <form id="update-form" onsubmit="updateBatch(event)" method="post">
                <section>
                    <input type="hidden" name="id" value="0">
                    <label class="dropdown">
                        <select name="day" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <span>Day</span>
                    </label><br>
                    <label class="textfield">
                        <input type="text" name="time" placeholder=" " required>
                        <span>Time</span>
                    </label><br>
                    <label class="textfield">
                        <input type="number" name="level" min=1 max=8 placeholder=" " required>
                        <span>Level</span>
                    </label><br>
                    <label class="dropdown">
                        <select name="tid" required>
                            <?php
foreach ($teachers as $teacher) {
    ?>
                                <option value="<?php echo $teacher['id'] ?>"><?php echo $teacher['name'] ?></option>
                            <?php
}
?>
                        </select>
                        <span>Teacher</span>
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button blue" type="submit">Update</button>
                </section>
            </form>
        </div>
    </div>

    <div id="add-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>New Batch</h3>
            <form id="add-form" onsubmit="addBatch(event)" method="post">
                <section>
                    <label class="textfield">
                        <input type="text" name="sdate" placeholder=" " value="yyyy-mm-dd" required>
                        <span>Start Date</span>
                    </label>
                    <label class="dropdown">
                        <select name="day">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <span>Day</span>
                    </label><br>
                    <label class="textfield">
                        <input type="text" name="time" placeholder=" " required>
                        <span>Time</span>
                    </label><br>
                    <label class="textfield">
                        <input type="number" name="level" min=1 max=8 placeholder=" " required>
                        <span>Level</span>
                    </label><br>
                    <label class="dropdown">
                        <select name="tid">
                            <?php
foreach ($teachers as $teacher) {
    ?>
                                <option value="<?php echo $teacher['id'] ?>"><?php echo $teacher['name'] ?></option>
                            <?php
}
?>
                        </select>
                        <span>Teacher</span>
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button" type="submit">Add Batch</button>
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