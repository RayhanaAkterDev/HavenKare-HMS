<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('include/config.php');

$uid = $_SESSION['id'] ?? null;
if ($uid) {
    mysqli_query($con, "UPDATE users SET session_token=NULL WHERE id='$uid'");
}

// Destroy session completely
$_SESSION = [];
session_unset();
session_destroy();

header("Location: user-login.php");
exit();