<?php

// ____Response Codes____
//    0: Not logged in as admin
//    1: Duplicate Email
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

if (
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['phone'])
) {

    // Normalize inputs
    $name = ucwords(testInput($_POST['name']));
    $email = strtolower(testInput($_POST['email']));
    $password = testInput($_POST['phone']);

    if (
        preg_match("/^[a-zA-Z ]{1,20}$/", $name) &&
        preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $email) &&
        preg_match('/^[0-9]{10}$/', $password)
    ) {

        // Check if email already exists in db
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

            $password = openssl_digest($password, 'sha512');

            $registerQuery = $conn->prepare(
                "INSERT INTO student(name,email,password)
            VALUES(:name,:email,:password)"
            );

            $registerQuery->bindParam(':name', $name);
            $registerQuery->bindParam(':email', $email);
            $registerQuery->bindParam(':password', $password);

            $registerQuery->execute();

            $conn = null;
            die("100");
        } catch (PDOException $e) {
            echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
            die("50");
        }
    }
}
