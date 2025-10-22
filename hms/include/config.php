<?php
define('DB_SERVER', '127.0.0.1:3307'); // include the port
define('DB_USER', 'root');
define('DB_PASS', ''); // or empty string if no password
define('DB_NAME', 'hms');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}