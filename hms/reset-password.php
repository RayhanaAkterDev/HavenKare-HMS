<?php
session_start();
include("include/config.php");
// Code for updating Password
if (isset($_POST['change'])) {
	$name = $_SESSION['name'];
	$email = $_SESSION['email'];
	$newpassword = md5($_POST['password']);
	$query = mysqli_query($con, "update users set password='$newpassword' where fullName='$name' and email='$email'");
	if ($query) {
		echo "<script>alert('Password successfully updated.');</script>";
		echo "<script>window.location.href ='user-login.php'</script>";
	}
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] min-h-screen">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>HeavenKare | Reset Password</title>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

	<!-- Tailwind -->
	<link href="../src/output.css" rel="stylesheet">

	<script type="text/javascript">
		function valid() {
			if (document.passwordreset.password.value != document.passwordreset.password_again.value) {
				alert("Password and Confirm Password Field do not match !!");
				document.passwordreset.password_again.focus();
				return false;
			}
			return true;
		}
	</script>
</head>

<body class="bg-transparent">

	<section class="bei-rp-section">
		<div class="bei-rp-container">

			<!-- Card -->
			<div class="bei-rp-card">

				<!-- Icon -->
				<div class="bei-rp-icon">
					<i class="fa fa-lock"></i>
				</div>

				<!-- Title -->
				<h2 class="bei-rp-title">Reset Your Password</h2>
				<p class="bei-rp-subtext">
					Please set your new password to access your account securely.
				</p>

				<!-- Error message -->
				<span class="bei-rp-error">
					<?php echo $_SESSION['errmsg']; ?><?php echo $_SESSION['errmsg'] = ""; ?>
				</span>

				<!-- Form -->
				<form name="passwordreset" method="post" onSubmit="return valid();" class="bei-rp-form">
					<div class="bei-rp-field">
						<i class="fa fa-lock bei-rp-icon-field"></i>
						<input type="password" id="password" name="password" placeholder="New Password" required
							class="bei-rp-input">
					</div>

					<div class="bei-rp-field">
						<i class="fa fa-lock bei-rp-icon-field"></i>
						<input type="password" id="password_again" name="password_again" placeholder="Confirm Password"
							required class="bei-rp-input">
					</div>

					<a href="./user-login.php" type="submit" name="change" class="bei-rp-btn">
						Change Password <i class="fa fa-arrow-right ml-2"></i>
					</a>

					<p class="bei-rp-login">
						Already have an account?
						<a href="user-login.php" class="bei-rp-login-link">Log in</a>
					</p>
				</form>
			</div>

			<p class="bei-rp-footer">
				© 2025 <span>HeavenKare HSM</span>. All Rights Reserved.
			</p>
		</div>
	</section>

</body>

</html>
