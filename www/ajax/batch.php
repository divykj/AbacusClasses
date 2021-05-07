<?php

// ____Response Codes____
//    0: Not logged in as admin
//    1: Invalid data
//     50: Server Error
//    100: Success

require_once '../includes/functions.inc.php';

session_start();

if (isset($_SESSION['user']['logged_in'])) {
    if ($_SESSION['user']['type'] != "admin") {
        die("0");
    }
    //Not logged in as admin
} else {
    die("0"); //Not logged in as admin
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            addBatch();
            break;
        case 'update':
            updateBatch();
            break;
        case 'delete':
            deleteBatch();
            break;
    }
}

function addBatch()
{
    if (
        isset($_POST['day']) &&
        isset($_POST['time']) &&
        isset($_POST['level']) &&
        isset($_POST['tid']) &&
        isset($_POST['sdate'])
    ) {

        // Normalize inputs
        $tid = testInput($_POST['tid']);
        $level = testInput($_POST['level']);
        $time = testInput($_POST['time']);
        $day = ucwords(testInput($_POST['day']));
        $sdate = testInput($_POST['sdate']);

        $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

        if (
            preg_match('/^[0-9]{4}\-[0-1][0-9]\-[0-3][0-9]+$/', $sdate) &&
            preg_match('/^[0-9]+$/', $tid) &&
            preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $time) &&
            preg_match('/^[1-8]$/', $level) &&
            in_array($day, $days)
        ) {

            try {

                $conn = dbConnect();

                $teacherQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM teacher
                        WHERE id=:id
                    ) AS x"
                );

                $teacherQuery->bindParam(':id', $tid);

                $teacherQuery->execute();

                if (!$teacherQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1"); // Teacher not exists
                }

                $insertBatchQuery = $conn->prepare(
                    "INSERT INTO
                batch (teacher_id, timing, level, day, start_date)
                VALUES (:tid, :time, :level, :day, :sdate)"
                );

                $insertBatchQuery->bindParam(':tid', $tid);
                $insertBatchQuery->bindParam(':level', $level);
                $insertBatchQuery->bindParam(':time', $time);
                $insertBatchQuery->bindParam(':day', $day);
                $insertBatchQuery->bindParam(':sdate', $sdate);

                $insertBatchQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function updateBatch()
{
    if (
        isset($_POST['id']) &&
        isset($_POST['day']) &&
        isset($_POST['time']) &&
        isset($_POST['level']) &&
        isset($_POST['tid'])
    ) {

        // Normalize inputs
        $id = testInput($_POST['id']);
        $tid = testInput($_POST['tid']);
        $level = testInput($_POST['level']);
        $time = testInput($_POST['time']);
        $day = ucwords(testInput($_POST['day']));

        $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

        if (
            preg_match('/^[0-9]+$/', $id) &&
            preg_match('/^[0-9]+$/', $tid) &&
            preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $time) &&
            preg_match('/^[1-8]$/', $level) &&
            in_array($day, $days)
        ) {

            try {

                $conn = dbConnect();
                $batchIdQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM batch
                        WHERE id=:id
                    ) AS x"
                );

                $batchIdQuery->bindParam(':id', $id);

                $batchIdQuery->execute();

                if (!$batchIdQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1"); // Batch not exists
                }

                $teacherQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM teacher
                        WHERE id=:id
                    ) AS x"
                );

                $teacherQuery->bindParam(':id', $id);

                $teacherQuery->execute();

                if (!$teacherQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1"); // Teacher not exists
                }

                $updateBatchQuery = $conn->prepare(
                    "UPDATE batch
                    SET level=:level,
                        day=:day,
                        timing=:time,
                        teacher_id=:tid
                    WHERE id=:id"
                );

                $updateBatchQuery->bindParam(':level', $level);
                $updateBatchQuery->bindParam(':day', $day);
                $updateBatchQuery->bindParam(':time', $time);
                $updateBatchQuery->bindParam(':tid', $tid);
                $updateBatchQuery->bindParam(':id', $id);

                $updateBatchQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function deleteBatch()
{
    if (isset($_POST['id'])) {
        try {
            $conn = dbConnect();
            $deleteBatchQuery = $conn->prepare(
                "DELETE FROM batch WHERE id=:id"
            );

            $deleteBatchQuery->bindParam(':id', $_POST['id']);

            if (!$deleteBatchQuery->execute()) {
                $conn = null;
                die("1");
            }

            $conn = null;
            die("100");
        } catch (PDOException $e) {
            echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
            die("50");
        }
    }
}
