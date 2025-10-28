<?php
session_start();
error_reporting(0);
include("include/config.php");
if (isset($_POST['submit'])) {
	$name = $_POST['fullname'];
	$email = $_POST['email'];
	$query = mysqli_query($con, "select id from users where fullName='$name' and email='$email'");
	$row = mysqli_num_rows($query);
	if ($row > 0) {
		$_SESSION['name'] = $name;
		$_SESSION['email'] = $email;
		header('location:reset-password.php');
	} else {
		echo "<script>alert('Invalid details. Please try with valid details');</script>";
		echo "<script>window.location.href ='forgot-password.php'</script>";
	}
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] min-h-screen">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HeavenKare | Password Recovery</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind -->
    <link href="../src/output.css" rel="stylesheet">
</head>

<body class="bg-transparent">

    <section class="bei-fp-section">
        <div class="bei-fp-container">

            <!-- Glass card -->
            <div class="bei-fp-card">

                <!-- Icon -->
                <div class="bei-fp-icon">
                    <i class="fa fa-unlock-alt"></i>
                </div>

                <!-- Title -->
                <h2 class="bei-fp-title">Forgot Your Password?</h2>
                <p class="bei-fp-subtext">
                    Enter your full name and registered email below to receive a reset link.
                </p>

                <!-- Error message -->
                <span class="bei-fp-error">
                    <?php
					echo isset($_SESSION['errmsg']) ? $_SESSION['errmsg'] : '';
					$_SESSION['errmsg'] = "";
					?>
                </span>

                <!-- Form -->
                <form method="post" class="bei-fp-form">
                    <div class="bei-fp-field">
                        <i class="fa-solid fa-user bei-fp-icon-field"></i>
                        <input type="text" name="fullname" placeholder="Full Name" required class="bei-fp-input">
                    </div>

                    <div class="bei-fp-field">
                        <i class="fa-regular fa-envelope bei-fp-icon-field"></i>
                        <input type="email" name="email" placeholder="Email Address" required class="bei-fp-input">
                    </div>

                    <button type="submit" name="submit" class="bei-fp-btn">
                        Send Reset Link <i class="fa fa-arrow-right ml-2"></i>
                    </button>

                    <p class="bei-fp-login">
                        Remember your password?
                        <a href="user-login.php" class="bei-fp-login-link">Log in</a>
                    </p>
                </form>
            </div>

            <p class="bei-fp-footer">
                © 2025 <span>HeavenKare HSM</span>. All Rights Reserved.
            </p>
        </div>
    </section>



</body>

</html>
