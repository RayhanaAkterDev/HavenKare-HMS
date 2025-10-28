<?php
define('DB_SERVER', 'sql202.infinityfree.com');
define('DB_USER', 'if0_40218713');
define('DB_PASS', 'hk2025Infinity');
define('DB_NAME', 'if0_40218713_hms');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}