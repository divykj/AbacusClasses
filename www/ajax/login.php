<?php
// ____Response Codes____
//    0: Already Logged In
//    1: Email Error
//    2: Password Error
//     50: Server Error
//    100: Success Admin Login
//    101: Success Student Login
//    102: Success Teacher Login

require_once '../includes/functions.inc.php';

session_start();

if (isset($_SESSION['user']['logged_in'])) {
    die("0");
}

if (
    isset($_POST['email']) &&
    isset($_POST['password'])
) {

    if ($_POST['email'] == "admin" && $_POST['password'] == "admin") {
        $_SESSION['user']['logged_in'] = true;
        $_SESSION['user']['type'] = "admin";
        $_SESSION['user']['id'] = 0;
        $_SESSION['user']['email'] = "admin";
        $_SESSION['user']['name'] = "Admin";

        die("100");
    }

    try {
        $conn = dbConnect();
        $loginQuery = $conn->prepare(
            "SELECT id, email, name, password, 'student' AS src
            FROM student
            WHERE email=:email
            UNION
            SELECT id, email, name, password, 'teacher' AS src
            FROM teacher
            WHERE email=:email"
        );

        $email = strtolower($_POST['email']);
        $loginQuery->bindParam(':email', $email);

        $loginQuery->execute();

        $userDetails = $loginQuery->fetch(PDO::FETCH_ASSOC);

        // Check email exists
        if (!$userDetails) {
            $conn = null;
            die("1");
        }

        // Check password
        $enteredPass = openssl_digest(testInput($_POST['password']), 'sha512');

        if (!hash_equals($userDetails['password'], $enteredPass)) {
            $conn = null;
            die("2");
        }

        // Set cookie and auth if remember me is checked
        if (isset($_POST['remember']) && $_POST['remember'] == 'remember') {
            $selector = base64_encode(random_bytes(9));
            $authenticator = random_bytes(33);

            setcookie(
                'RLID',
                $selector . ':' . base64_encode($authenticator),
                time() + 86400 * 365,
                '/'
            );

            $insertAuth = $conn->prepare(
                "INSERT INTO auth_tokens (selector, token, email, expires)
                VALUES (:selector, :token, :email, :expires)"
            );

            $hashed = hash('sha256', $authenticator);
            $expires = date('Y-m-d\TH:i:s', time() + 86400 * 365);
            $insertAuth->bindParam(':selector', $selector);
            $insertAuth->bindParam(':token', $hashed);
            $insertAuth->bindParam(':email', $userDetails['email']);
            $insertAuth->bindParam(':expires', $expires);

            $insertAuth->execute();
        }

        // Set session variables
        $_SESSION['user']['logged_in'] = true;
        $_SESSION['user']['type'] = $userDetails['src'];
        $_SESSION['user']['id'] = $userDetails['id'];
        $_SESSION['user']['email'] = $userDetails['email'];
        $_SESSION['user']['name'] = $userDetails['name'];
        $conn = null;
        switch ($userDetails['src']) {
            case "student":
                die("101");
                break;
            case "teacher":
                die("102");
                break;
        }
    } catch (PDOException $e) {
        $error = "[" . date('Y-m-d H:i:s O', time()) . "] [" . basename(__FILE__) . "] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage();
        file_put_contents('../logs/db_error.log', $error . PHP_EOL, FILE_APPEND | LOCK_EX);
        die("50");
    }
}
