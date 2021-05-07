<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (isset($_SESSION['user']['logged_in'])) {
    if ($_SESSION['user']['type'] != "admin") {
        redirectTo("dashboard.php");
    }
} else {
    redirectTo("index.php");
}

try {
    // Set student passwords

    // $conn = dbConnect();
    // $getPhoneQuery = $conn->prepare(
    //     "SELECT id, phone
    //     FROM student"
    // );

    // $getPhoneQuery->execute();

    // while ($row = $getPhoneQuery->fetch(PDO::FETCH_ASSOC)) {
    //     $id = $row['id'];
    //     $password = openssl_digest($row['phone'], 'sha512');

    //     $setPasswordQuery = $conn->prepare(
    //         "UPDATE student
    //         SET password=:password
    //         WHERE id=:id"
    //     );
    //     $setPasswordQuery->bindParam('password', $password);
    //     $setPasswordQuery->bindParam('id', $id);

    //     $setPasswordQuery->execute();

    //     echo "ID: ".$id." Phone: ".$row['phone']." Password: ".$password."<br>";
    // }

    // Set teacher passwords

    // $conn = dbConnect();
    // $getPhoneQuery = $conn->prepare(
    //     "SELECT id, phone
    //     FROM teacher"
    // );

    // $getPhoneQuery->execute();

    // while ($row = $getPhoneQuery->fetch(PDO::FETCH_ASSOC)) {
    //     $id = $row['id'];
    //     $password = openssl_digest($row['phone'], 'sha512');

    //     $setPasswordQuery = $conn->prepare(
    //         "UPDATE teacher
    //         SET password=:password
    //         WHERE id=:id"
    //     );
    //     $setPasswordQuery->bindParam('password', $password);
    //     $setPasswordQuery->bindParam('id', $id);

    //     $setPasswordQuery->execute();

    //     echo "ID: ".$id." Phone: ".$row['phone']." Password: ".$password."<br>";
    // }

} catch (PDOException $e) {
    echo ("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n" . "3" . "../logs/db_error.log");
    die("50");
}
