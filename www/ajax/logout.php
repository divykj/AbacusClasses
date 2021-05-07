<?php
require_once "../includes/functions.inc.php";

session_start();

if (isset($_COOKIE['RLID'])) {
    try {
        $conn = dbConnect();

        $deleteToken = $conn->prepare("DELETE FROM auth_tokens WHERE selector = :selector");
        $deleteToken->bindParam(':selector', explode(':', $_COOKIE['RLID'])[0]);
        $deleteToken->execute();
    } catch (PDOException $e) {
        error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . PHP_EOL, 3, "../logs/db_error.log");
        die("50");
    }

    setcookie(
        'RLID',
        '',
        time() - 3600,
        '/'
    );
}

unset($_SESSION['user']);

if (isset($_SESSION['user'])) {
    echo "50";
    exit();
} else {
    echo "100";
    exit();
}
