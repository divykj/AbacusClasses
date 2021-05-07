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
            addTeacher();
            break;
        case 'update':
            updateTeacher();
            break;
        case 'delete':
            deleteTeacher();
            break;
    }
}

function addTeacher()
{
    if (
        isset($_POST['name']) &&
        isset($_POST['email']) &&
        isset($_POST['phone'])
    ) {

        // Normalize inputs
        $email = testInput($_POST['email']);
        $phone = testInput($_POST['phone']);
        $name = ucwords(testInput($_POST['name']));

        if (
            preg_match('/^[A-Za-z ]{4,40}$/', $name) &&
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

                $password = openssl_digest($phone, 'sha512');

                $addTeacherQuery = $conn->prepare(
                    "INSERT INTO teacher(name, email, phone, password)
                VALUES(:name,:email,:phone, :password)"
                );

                $addTeacherQuery->bindParam(':name', $name);
                $addTeacherQuery->bindParam(':email', $email);
                $addTeacherQuery->bindParam(':phone', $phone);
                $addTeacherQuery->bindParam(':password', $password);

                $addTeacherQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function updateTeacher()
{
    if (
        isset($_POST['id']) &&
        isset($_POST['name']) &&
        isset($_POST['email']) &&
        isset($_POST['phone'])
    ) {

        // Normalize inputs
        $id = testInput($_POST['id']);
        $email = testInput($_POST['email']);
        $phone = testInput($_POST['phone']);
        $name = ucwords(testInput($_POST['name']));

        if (
            preg_match('/^[0-9]+$/', $id) &&
            preg_match('/^[A-Za-z ]{4,40}$/', $name) &&
            preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $email) &&
            preg_match('/^[0-9]{10}$/', $phone)
        ) {

            try {

                $conn = dbConnect();

                $teacherCheckQuery = $conn->prepare(
                    "SELECT COUNT(*)
                    FROM
                    (
                        SELECT id
                        FROM teacher
                        WHERE id=:id
                    ) AS x"
                );

                $teacherCheckQuery->bindParam(':id', $id);

                $teacherCheckQuery->execute();

                if (!$teacherCheckQuery->fetchColumn() > 0) {
                    $conn = null;
                    die("1");
                }

                $updateTeacherQuery = $conn->prepare(
                    "UPDATE teacher
                    SET name=:name,
                        phone=:phone,
                        email=:email
                    WHERE id=:id"
                );

                $updateTeacherQuery->bindParam(':name', $name);
                $updateTeacherQuery->bindParam(':phone', $phone);
                $updateTeacherQuery->bindParam(':email', $email);
                $updateTeacherQuery->bindParam(':id', $id);

                $updateTeacherQuery->execute();

                $conn = null;
                die("100");
            } catch (PDOException $e) {
                echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
                die("50");
            }
        }
    }
}

function deleteTeacher()
{
    if (isset($_POST['id'])) {
        try {
            $conn = dbConnect();
            $deleteTeacherQuery = $conn->prepare(
                "DELETE FROM teacher WHERE id=:id"
            );

            $deleteTeacherQuery->bindParam(':id', $_POST['id']);

            if (!$deleteTeacherQuery->execute()) {
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
