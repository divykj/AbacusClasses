<?php
require_once '../includes/functions.inc.php';

session_start();

if (isset($_SESSION['user']['logged_in'])) {
    if ($_SESSION['user']['type'] != "teacher") {
        die("0");
    }

} else {
    die("0");
}

if (
    isset($_POST['batchid']) &&
    isset($_POST['testtypeid']) &&
    isset($_POST['date'])
) {

    $bid = testInput($_POST['batchid']);
    $ttid = testInput($_POST['testtypeid']);
    $date = testInput($_POST['date']);

    if (
        preg_match('/^[0-9]{4}\-[0-1][0-9]\-[0-3][0-9]+$/', $date) &&
        preg_match('/^[0-9]+$/', $bid) &&
        preg_match('/^[0-9]+$/', $ttid)
    ) {

        try {
            $conn = dbConnect();

            $addTestQuery = $conn->prepare(
                "INSERT INTO test (date, testtype_id, batch_id)
                VALUES (:date, :ttid, :bid);"
            );
            $addTestQuery->bindParam(':date', $date);
            $addTestQuery->bindParam(':ttid', $ttid);
            $addTestQuery->bindParam(':bid', $bid);

            $addTestQuery->execute();

            $idQuery = $conn->prepare(
                "SELECT LAST_INSERT_ID() AS id"
            );
            $idQuery->execute();
            $tid = $idQuery->fetch(PDO::FETCH_ASSOC)['id'];

            $insertMarksQuery = $conn->prepare(
                "INSERT INTO marks (marks, test_id, student_id)
                VALUES (:marks, :tid, :sid)"
            );

            $insertMarksQuery->bindParam(':marks', $smarks);
            $insertMarksQuery->bindParam(':tid', $tid);
            $insertMarksQuery->bindParam(':sid', $sid);

            foreach ($_POST as $student => $marks) {
                if (substr($student, 0, 7) == 'student') {
                    $smarks = $marks;
                    $sid = substr($student, 7, strlen($student) - 7);
                    $insertMarksQuery->execute();
                }
            }

            $conn = null;
            die("100");
        } catch (PDOException $e) {
            echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
            die("50");
        }
    }
}
