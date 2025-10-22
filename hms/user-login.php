<?php
session_start();

// Show all errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include DB config
include("include/config.php");

// Check database connection
if (!$con) {
	die("Database connection failed: " . mysqli_connect_error());
}

// Handle login form
if (isset($_POST['submit'])) {
	$puname = mysqli_real_escape_string($con, $_POST['username']);
	$ppwd = mysqli_real_escape_string($con, md5($_POST['password']));

	$ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' AND password='$ppwd'");
	$num = mysqli_fetch_array($ret);
	$uip = $_SERVER['REMOTE_ADDR'];

	if ($num > 0) {
		// Successful login
		$_SESSION['login'] = $puname;
		$_SESSION['id'] = $num['id'];
		$pid = $num['id'];
		$status = 1;

		mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) 
                            VALUES('$pid', '$puname', '$uip', '$status')");

		header("Location: dashboard.php");
		exit(); // stop execution after redirect
	} else {
		// Failed login
		$_SESSION['login'] = $puname;
		$status = 0;

		mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) 
                            VALUES(NULL, '$puname', '$uip', '$status')");

		$_SESSION['errmsg'] = "Invalid username or password";
		header("Location: user-login.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HMS | Patient Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <!-- Logo -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">HMS | Patient Login</h1>
        </div>

        <!-- Login Form -->
        <form method="POST" class="space-y-4">
            <p class="text-gray-600 text-sm">
                Please enter your email and password to log in.
                <span class="text-red-500 text-xs block mt-1">
                    <?php
					echo isset($_SESSION['errmsg']) ? $_SESSION['errmsg'] : '';
					$_SESSION['errmsg'] = "";
					?>
                </span>
            </p>

            <!-- Email Input -->
            <div class="relative">
                <input type="email" name="username" placeholder="Email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                <i class="fa fa-user absolute right-3 top-2.5 text-gray-400"></i>
            </div>

            <!-- Password Input -->
            <div class="relative">
                <input type="password" name="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                <i class="fa fa-lock absolute right-3 top-2.5 text-gray-400"></i>
            </div>

            <!-- Forgot Password -->
            <div class="text-right">
                <a href="forgot-password.php" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-200">
                Login <i class="fa fa-arrow-right ml-2"></i>
            </button>

            <!-- Register Link -->
            <p class="text-center text-gray-600 text-sm">
                Don't have an account yet?
                <a href="registration.php" class="text-blue-500 hover:underline">Create an account</a>
            </p>
        </form>

        <!-- Footer Text -->
        <p class="mt-6 text-center text-gray-400 text-xs">
            &copy; 2025 <span class="font-semibold">Hospital Management System</span>. All Rights Reserved.
        </p>
    </div>

</body>

</html>