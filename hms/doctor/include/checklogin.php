<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

function check_login()
{
	if (empty($_SESSION['login']) || $_SESSION['login'] !== true || empty($_SESSION['id'])) {
		// Not logged in, redirect to login page
		header("Location: index.php");
		exit();
	}
}