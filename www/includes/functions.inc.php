<?php

function dbConnect()
{
    $servername = "db";
    $username = "user";
    $password = "test";
    $dbname = "abacus_classes";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
}

function testInput($data)
{
    return htmlspecialchars(addslashes(trim($data)));
}

function redirectTo($url)
{
    if (headers_sent()) {
        die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
    } else {
        header('Location: ' . $url);
        die();
    }
}

function humanTiming($time)
{
    $time = time() - $time;
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'y',
        604800 => 'w',
        86400 => 'd',
        3600 => 'h',
        60 => 'm',
        1 => 's',
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }

        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . $text;
    }
}

function authCookie()
{
    if (isset($_COOKIE['RLID'])) {
        list($selector, $authenticator) = explode(':', $_COOKIE['RLID']);
    } else {
        return false;
    }

    try {
        $conn = dbConnect();

        $cookieQuery = $conn->prepare(
            "SELECT c.token AS token, c.selector AS selector, c.expires AS expires, a.id AS id, c.email AS email, a.name AS name, 'student' AS src
            FROM auth_tokens AS c
            INNER JOIN student AS a
            ON a.email = c.email
            WHERE c.selector = :selector
            UNION
            SELECT c.token AS token, c.selector AS selector, c.expires AS expires, b.id AS id, c.email AS email, b.name as name, 'teacher' AS src
            FROM auth_tokens AS c
            INNER JOIN teacher AS b
            ON b.email = c.email
            WHERE c.selector = :selector"
        );
        $cookieQuery->bindParam(':selector', $selector);

        $cookieQuery->execute();
        $authDetails = $cookieQuery->fetch(PDO::FETCH_ASSOC);

        if (!$authDetails) {
            setcookie(
                'RLID',
                '',
                time() - 3600,
                '/'
            );
            return false;
        }

        if (hash_equals($authDetails['token'], hash('sha256', base64_decode($authenticator)))) {

            @session_start();

            $_SESSION['user']['logged_in'] = true;
            $_SESSION['user']['type'] = $authDetails['src'];
            $_SESSION['user']['id'] = $authDetails['id'];
            $_SESSION['user']['email'] = $authDetails['email'];
            $_SESSION['user']['name'] = $authDetails['name'];
        }
    } catch (PDOException $e) {
        error_log("[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage() . "\n", 3, "../logs/db_error.log");
        exit();
    }

    return true;
}

function loginFromCookie()
{
    if (isset($_COOKIE['RLID'])) {
        authCookie();
    }
}

function updateSession($details)
{
    $_SESSION['user']['logged_in'] = true;
    $_SESSION['user']['type'] = $details['src'];
    $_SESSION['user']['id'] = $details['id'];
    $_SESSION['user']['email'] = $details['email'];
    $_SESSION['user']['name'] = $details['name'];
}
