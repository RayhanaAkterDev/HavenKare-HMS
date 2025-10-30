<?php
session_start();
include("include/config.php");

if (isset($_POST['change'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $password = trim($_POST['password']);
    $confirm = trim($_POST['password_again']);

    // Basic validation
    if (empty($password) || empty($confirm)) {
        $_SESSION['errmsg'] = "Password fields cannot be empty.";
        header("Location: reset-password.php");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['errmsg'] = "Passwords do not match.";
        header("Location: reset-password.php");
        exit();
    }

    // Password complexity validation
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        $_SESSION['errmsg'] = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
        header("Location: reset-password.php");
        exit();
    }

    // Update password in database
    $hashed = md5($password);
    $query = mysqli_query($con, "UPDATE users SET password='$hashed' WHERE fullName='$name' AND email='$email'");

    if ($query) {
        $_SESSION['successmsg'] = "Password successfully updated. Redirecting to login...";
        header("Location: reset-password.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Something went wrong. Please try again.";
        header("Location: reset-password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="php-bg">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Reset Password</title>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet">
</head>

<body class="bg-transparent">

    <section class="php-section">
        <div class="php-container">

            <!-- Page heading -->
            <div class="php-page-heading">
                <h2 class="php-headline !text-sky-800">Reset Password</h2>
                <p class="php-text !text-sky-800/80">Create a new secure password to access your HeavenKare account.</p>
            </div>

            <!-- Card -->
            <div class="php-card">
                <div class="php-form-wrapper">
                    <!-- Form heading -->
                    <div class="form-heading">
                        <i class="fa fa-lock fa-4x mb-2 text-sky-800/20"></i>
                        <h2 class="php-form-title">Reset Your Password</h2>
                        <p class="php-form-subtitle">Please set your new password to access your account securely.</p>
                    </div>

                    <!-- Error message -->
                    <span id="rpError" class="php-error hidden">
                        <i class="fas fa-circle-exclamation text-red-700"></i>
                        <span id="rpErrorText"></span>
                    </span>

                    <!-- Success message -->
                    <span id="rpSuccess" class="php-success hidden">
                        <i class="fas fa-circle-check text-green-700"></i>
                        <span id="rpSuccessText"></span>
                    </span>

                    <!-- Form -->
                    <form name="passwordreset" method="post" class="php-form" novalidate>
                        <div class="php-field">
                            <i class="fa fa-lock php-icon"></i>
                            <input type="password" id="password" name="password" placeholder="New Password"
                                class="php-input" required>
                        </div>

                        <div class="php-field">
                            <i class="fa fa-lock php-icon"></i>
                            <input type="password" id="password_again" name="password_again"
                                placeholder="Confirm Password" class="php-input" required>
                        </div>

                        <button type="submit" name="change" class="php-btn">
                            Change Password <i class="fa fa-arrow-right ml-2"></i>
                        </button>

                        <p class="php-link-text">
                            Already have an account?
                            <a href="user-login.php" class="php-link">Log in</a>
                        </p>
                    </form>
                </div>
            </div>

            <p class="bei-rp-footer">
                © 2025 <span>HeavenKare HSM</span>. All Rights Reserved.
            </p>
        </div>
    </section>

    <!-- Show error or success messages -->
    <script>
    function showError(msg) {
        const errorDiv = document.getElementById('rpError');
        const errorText = document.getElementById('rpErrorText');
        errorText.textContent = msg;
        errorDiv.classList.remove('hidden');
        errorDiv.classList.add('show');
    }

    function showSuccess(msg) {
        const successDiv = document.getElementById('rpSuccess');
        const successText = document.getElementById('rpSuccessText');
        successText.textContent = msg;
        successDiv.classList.remove('hidden');
        successDiv.classList.add('show');
        // Redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = "user-login.php";
        }, 1500);
    }

    <?php if (!empty($_SESSION['errmsg'])): ?>
    showError("<?php echo $_SESSION['errmsg']; ?>");
    <?php $_SESSION['errmsg'] = ""; ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['successmsg'])): ?>
    showSuccess("<?php echo $_SESSION['successmsg']; ?>");
    <?php $_SESSION['successmsg'] = ""; ?>
    <?php endif; ?>
    </script>

    <!-- Force page reload on back button -->
    <script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
    </script>

</body>

</html>