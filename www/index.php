<?php
require_once "includes/functions.inc.php";
session_start();
loginFromCookie();

if (isset($_SESSION['user']['logged_in'])) {
    redirectTo('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1, user-scalable=yes">
    <title>Abacus Classes</title>

    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header class="expanded">
        <a href="#cover" id="title" class="button flat">
            <h1>Abacus Classes</h1>
        </a>
        <nav>
            <a href="#about" class="button flat">About Us</a>
            <a id="login-btn" class="button">Login</a>
        </nav>
    </header>
    <div id="cover">
        <?php include "images/abacus.svg"?>
    </div>
    <svg href="#about" id="cover-scroll" preserveAspectRatio="xMidYMid meet" viewBox="0 0 200 100">
        <path d="M10 100C5 0,190 0,190 100" id="bg" opacity="1" fill-opacity="0" fill="#ffffff" stroke-width="1" stroke-opacity="1" stroke="rgba(36,37,38,0.1)" />
        <path d="M90 70L100 80L110 70" id="arrow" stroke-linecap="round" stroke-linejoin="round" opacity="1" fill-opacity="0" stroke="#333333" stroke-width="6" stroke-opacity="1"/>
    </svg>
    <main style="height: 100vh">
        <div id="about"></div>
        <h2 class="fancy span6">About Abacus</h2>
        <div class="card span3">
            <h3>What is Abacus?</h3>
            <p>Abacus is one of the most ancient tool used for the purpose of calculations. With the help of an
                abacus, simple arithmetical functions are easy to learn. Using abacus techniques, calculations can
                be carried out mentally.</p>
        </div>
        <div class="card span3">
            <h3>Why learn Abacus?</h3>
            <p>Enrolling your kid in abacus classes can help them to bring up their confidence and concentration
                level in their daily activities. Not only that, learning abacus can also help a child to improve
                their memory.</p>
        </div>
        <div class="card span2">
            <h3>Why join us?</h3>
            <p>Because we are the best!</p>
            <a href="#contact" class="button small">
                Join Now
            </a>
        </div>
        <div id="contact-card" class="card grid span4">
            <a id="map" href="https://goo.gl/maps/LNzoDXkH2dWmtvKC8" target="_blank" class="span3 button"></a>
            <div id="info" class="span3">
                <div><span>Phone:</span> 9137628205</div>
                <div><span>Email:</span> yathnagda1999@gmail.com</div>
                <div><span>Address:</span> 205, Adwait Apartment Shivram Nagar, Jalgaon - 425002</div>
            </div>
        </div>
    </main>
    <?php
include "parts/footer.php";
?>
    <div id="login-cover">
        <div id="login-box" class="card small">
            <h2>Sign In</h2>
            <form id="login-form" onsubmit="logIn(event)" method="post">
                <section>
                    <label class="textfield">
                        <input type="text" name="email" placeholder=" " required>
                        <span>Email</span>
                    </label><br>
                    <label class="textfield">
                        <input type="password" name="password" placeholder=" " required>
                        <span>Password</span>
                    </label><br>
                    <label class="checkbox">
                        <input type="checkbox" name="remember" id="remember" value="remember" checked>
                        <span>Remember Me</span>
                    </label><br>
                    <button id="login-submit" class="button" type="submit">Login</button>
                </section>
            </form>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="js/common.js"></script>
<script src="js/index.js"></script>

</html>