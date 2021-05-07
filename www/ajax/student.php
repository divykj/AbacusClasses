<?php

// ____Response Codes____
//    0: Not logged in as admin
//    1: Duplicate Email
//  1: Couldn't Delete
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
            addStudent();
            break;
        case 'update':
            updateStudent();
            break;
        case 'delete':
            deleteStudent();
            break;
    }
}

function addStudent()
{
    if (
        isset($_POST['name']) &&
        isset($_POST['email']) &&
        isset($_POST['phone']) &&
        isset($_POST['bid'])
    ) {

        // Normalize inputs
        $bid = testInput($_POST['bid']);
        $email = testInput($_POST['email']);
        $phone = testInput($_POST['phone']);
        $name = ucwords(testInput($_POST['name']));

        if (
            preg_match('/^[A-Za-z ]{4,40}$/', $name) &&
            preg_match('/^[0-9]+$/', $bid) &&
            preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $email) &&
            preg_match('/^[0-9]{10}$/', $phone)
        ) {

            try {

                $conn = dbConnect();

                $duplicateEmailQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id AS src
                        FROM student
                        WHERE email=:email
                        UNION
                        SELECT id AS src
                        FROM teacher
                        WHERE email=:email
                    ) AS x"
                );

                $duplicateEmailQuery->bindParam(':email', $email);

                $duplicateEmailQuery->execute();

                if ($duplicateEmailQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1");
                }

                $batchCheckQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM batch
                        WHERE id=:id
                    ) AS x"
                );

                $batchCheckQuery->bindParam(':id', $bid);

                $batchCheckQuery->execute();

                if (!$batchCheckQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1");
                }

                $password = openssl_digest($phone, 'sha512');

                $addStudentQuery = $conn->prepare(
                    "INSERT INTO student(name, email, phone, password, batch_id)
                VALUES(:name,:email,:phone, :password, :bid)"
                );

                $addStudentQuery->bindParam(':name', $name);
                $addStudentQuery->bindParam(':email', $email);
                $addStudentQuery->bindParam(':phone', $phone);
                $addStudentQuery->bindParam(':password', $password);
                $addStudentQuery->bindParam(':bid', $bid);

                $addStudentQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function updateStudent()
{
    if (
        isset($_POST['id']) &&
        isset($_POST['name']) &&
        isset($_POST['email']) &&
        isset($_POST['phone']) &&
        isset($_POST['bid'])
    ) {

        // Normalize inputs
        $id = testInput($_POST['id']);
        $bid = testInput($_POST['bid']);
        $email = testInput($_POST['email']);
        $phone = testInput($_POST['phone']);
        $name = ucwords(testInput($_POST['name']));

        if (
            preg_match('/^[0-9]+$/', $id) &&
            preg_match('/^[A-Za-z ]{4,40}$/', $name) &&
            preg_match('/^[0-9]+$/', $bid) &&
            preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $email) &&
            preg_match('/^[0-9]{10}$/', $phone)
        ) {

            try {

                $conn = dbConnect();

                $studentCheckQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM student
                        WHERE id=:id
                    ) AS x"
                );

                $studentCheckQuery->bindParam(':id', $id);

                $studentCheckQuery->execute();

                if (!$studentCheckQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1");
                }

                // $duplicateEmailQuery = $conn->prepare(
                //     "SELECT COUNT(*)
                //     FROM
                //     (
                //         SELECT id AS src
                //         FROM student
                //         WHERE email=:email
                //         UNION
                //         SELECT id AS src
                //         FROM teacher
                //         WHERE email=:email
                //     ) AS x"
                // );

                // $duplicateEmailQuery->bindParam(':email',$email);

                // $duplicateEmailQuery->execute();

                // if ($duplicateEmailQuery->fetchColumn()>0) {
                //     $conn = null ;
                //     die("1");
                // }

                $batchCheckQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM batch
                        WHERE id=:id
                    ) AS x"
                );

                $batchCheckQuery->bindParam(':id', $bid);

                $batchCheckQuery->execute();

                if (!$batchCheckQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1");
                }

                $updateStudentQuery = $conn->prepare(
                    "UPDATE student
                    SET name=:name,
                        phone=:phone,
                        email=:email,
                        batch_id=:bid
                    WHERE id=:id"
                );

                $updateStudentQuery->bindParam(':name', $name);
                $updateStudentQuery->bindParam(':phone', $phone);
                $updateStudentQuery->bindParam(':email', $email);
                $updateStudentQuery->bindParam(':bid', $bid);
                $updateStudentQuery->bindParam(':id', $id);

                $updateStudentQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function deleteStudent()
{
    if (isset($_POST['id'])) {
        try {
            $conn = dbConnect();
            $deleteStudentQuery = $conn->prepare(
                "DELETE FROM student WHERE id=:id"
            );

            $deleteStudentQuery->bindParam(':id', $_POST['id']);

            if (!$deleteStudentQuery->execute()) {
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
