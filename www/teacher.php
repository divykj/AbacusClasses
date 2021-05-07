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
        <h3 class="span6 fancy">All Teachers</h3>
        <div id="teacher" class="card span6">
            <table cellspacing="0" cellpadding="0" class="action">
                <tr head>
                    <td colspan="3" class="actions">
                        <a id="add-btn" class="button outline small">Add Teacher</a>
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
                    <th>Batches</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
try {
    $conn = dbConnect();

    $teacherQuery = $conn->prepare(
        "SELECT DISTINCT
                        teacher.id,
                        teacher.name,
                        teacher.phone,
                        teacher.email,
                        (SELECT COUNT(*) FROM batch
                            WHERE teacher_id = teacher.id) AS batches
                        FROM teacher
                        LEFT JOIN batch ON teacher.id=batch.teacher_id"
    );

    $teacherQuery->execute();

    while ($teacher = $teacherQuery->fetch(PDO::FETCH_ASSOC)) {
        ?>
                        <tr data-teacher-id="<?php echo $teacher['id'] ?>" data-batches="<?php echo $teacher['batches'] ?>" data-phone="<?php echo $teacher['phone'] ?>" data-email="<?php echo $teacher['email'] ?>" data-name="<?php echo $teacher['name'] ?>">
                            <td><?php echo $teacher['id'] ?></td>
                            <td><?php echo $teacher['name'] ?></td>
                            <td><?php echo $teacher['phone'] ?></td>
                            <td><?php echo $teacher['email'] ?></td>
                            <td><?php echo $teacher['batches'] ?></td>
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
            <h3>Teacher Details</h3>
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
                <br>
                <a class="button blue" id="detail-edit-btn">Edit Teacher</a>
                <a class="button red" id="detail-delete-btn">Delete Teacher</a>
            </section>
        </div>
    </div>

    <div id="delete-modal" class="modal-cover">
        <div class="modal-box card small">
            <h4>Do you want to delete this teacher?</h4>
            <br>
            <form id="delete-form" onsubmit="deleteTeacher(event)" method="post">
                <input type="hidden" name="id" value="0">
                <a class="button flat cancel-btn">Cancel</a>
                <button type="submit" class="button red">Delete</a>
            </form>
        </div>
    </div>

    <div id="edit-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>Update Teacher</h3>
            <form id="update-form" onsubmit="updateTeacher(event)" method="post">
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
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button blue" type="submit">Update</button>
                </section>
            </form>
        </div>
    </div>

    <div id="add-modal" class="modal-cover">
        <div class="modal-box card small">
            <h3>New Teacher</h3>
            <form id="add-form" onsubmit="addTeacher(event)" method="post">
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
                    </label><br><br>
                    <a class="button flat cancel-btn">Cancel</a>
                    <button class="button" type="submit">Add Teacher</button>
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