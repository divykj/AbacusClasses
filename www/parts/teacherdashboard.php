<h2 class="fancy span6">Dashboard</h2>
<!-- <div class="card small span6" id="actionbar">
    <a href="#stats" class="button flat">Statistics</a>
    <a href="#stats" class="button flat">Marks</a>
    <a href="#stats" class="button flat">View Marks</a>
    <a href="#stats" class="button flat">View Marks</a>
</div> -->

<!-- <div class="span6 grid" id="stats">
    <h3 class="fancy span6">Statistics</h3>
    <div class="card span6" id="marks">
        <h3>Average Batchwise Marks</h3>
        <div class="multiline chart" data-series='[[74, 82, 53, 67, 80],[63, 78, 69, 76, 82], [56, 59, 80, 85, 90]]' data-serieslabels='["Batch 1", " Batch 2", "Batch 3"]' data-labels='["Units 1", "Units 2", "Mid Terms", "Practice", "Prelims"]'></div>
    </div>
</div> -->

<h3 class="fancy span6">My Batches</h3>
<div id="batch" class="card span6">
    <table cellspacing="0" cellpadding="0">
        <!-- <tr head>
            <td colspan="3" class="actions">
                <a id="add-btn" class="button outline small">Add Batch</a>
            </td>
            <td colspan="3" class="filter">
                <label class="textfield">
                    <input type="text" name="filter" placeholder=" ">
                    <span>Filter</span>
                </label>
            </td>
        </tr> -->
        <tr head>
            <th>&nbsp;</th>
            <th>Day</th>
            <th>Time</th>
            <th>Level</th>
            <th>Students</th>
            <th></th>
        </tr>
    <?php
        try {
            $conn = dbConnect();

            $batchQuery = $conn->prepare(
                "SELECT id,
                level,
                timing AS time,
                day,
                CONCAT(LEFT(day, 3), ', ', LEFT(timing, 5)) AS timing,
                (SELECT COUNT(*) FROM student
                    WHERE batch_id = batch.id) AS students
                FROM batch
                WHERE batch.teacher_id=:tid"
            );

            $batchQuery->bindParam(":tid", $_SESSION['user']['id']);

            $batchQuery->execute();

            while ($batch = $batchQuery->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr data-batch-id="<?php echo $batch['id'] ?>" data-time="<?php echo $batch['time']?>" data-day="<?php echo $batch['day']?>" data-level="<?php echo $batch['level']?>" data-students="<?php echo $batch['students'] ?>">
                    <td><?php echo $batch['id']?></td>
                    <td><?php echo $batch['day']?></td>
                    <td><?php echo $batch['time']?></td>
                    <td><?php echo $batch['level']?></td>
                    <td><a class="students-btn button flat small"><?php echo $batch['students']?></a></td>
                    <td><a class="details-btn button flat icon"><i class="fa fa-eye"></i></a></td>
                    <!-- <td><a class="test-btn button small flat">Create Test</a> <a class="score-btn button small flat">Add Scores</a> <a class="score-btn button small flat">View Scores</a></td> -->
                </tr>
            <?php
            }

        } catch (PDOException $e) {
            echo "[".date('Y-m-d H:i:s O',time())."] [".basename(__FILE__)."] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log";
            // redirectTo('error');
            exit();
        }
    ?> 
    </table>
</div>

<script src="js/teacherdashboard.js"></script>

<div id="details-modal" class="modal-cover">
    <div class="modal-box card small">
        <h3>Batch Details</h3>
        <br>
        <section>
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
                <span>Students:</span>
                <span name="students"></span>
            </div>
            <br>
            <a class="button blue" id="add-test-btn">Add Test</a>
        </section>
    </div>
</div>
