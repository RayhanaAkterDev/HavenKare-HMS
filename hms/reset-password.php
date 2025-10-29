<?php
session_start();
include("include/config.php");

if (isset($_POST['change'])) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $password = trim($_POST['password']);
    $confirm = trim($_POST['password_again']);

    // Password validation
    if (empty($password) || empty($confirm)) {
        $_SESSION['errmsg'] = "Password fields cannot be empty.";
        header("Location: reset-password.php");
        exit();
    } elseif ($password !== $confirm) {
        $_SESSION['errmsg'] = "Passwords do not match.";
        header("Location: reset-password.php");
        exit();
    } elseif (strlen($password) < 8) {
        $_SESSION['errmsg'] = "Password must be at least 8 characters long.";
        header("Location: reset-password.php");
        exit();
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $_SESSION['errmsg'] = "Password must contain at least one uppercase letter and one number.";
        header("Location: reset-password.php");
        exit();
    }

    // Update password
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
<html lang="en" class="bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] min-h-screen">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Reset Password</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind -->
    <link href="../src/output.css" rel="stylesheet">

    <style>
    /* Success message style */
    .bei-login__success {
        @apply hidden items-center gap-3 bg-green-100/30 border border-green-200 rounded-lg p-4 w-full text-sm text-green-700 mb-4;
    }

    .bei-login__success.show {
        @apply flex;
        animation: softFadeIn 0.25s ease-out forwards;
    }

    @keyframes softFadeIn {
        from {
            opacity: 0;
            transform: translateY(-2px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
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
                <span id="rpError" class="bei-login__error hidden">
                    <i class="fas fa-circle-exclamation text-red-700"></i>
                    <span id="rpErrorText"></span>
                </span>

                <!-- Success message -->
                <span id="rpSuccess" class="bei-login__success hidden">
                    <i class="fas fa-circle-check text-green-700"></i>
                    <span id="rpSuccessText"></span>
                </span>

                <!-- Form -->
                <form name="passwordreset" method="post" class="bei-rp-form" novalidate>
                    <div class="bei-rp-field">
                        <i class="fa fa-lock bei-rp-icon-field"></i>
                        <input type="password" id="password" name="password" placeholder="New Password"
                            class="bei-rp-input" required>
                    </div>

                    <div class="bei-rp-field">
                        <i class="fa fa-lock bei-rp-icon-field"></i>
                        <input type="password" id="password_again" name="password_again" placeholder="Confirm Password"
                            class="bei-rp-input" required>
                    </div>

                    <button type="submit" name="change" class="bei-rp-btn">
                        Change Password <i class="fa fa-arrow-right ml-2"></i>
                    </button>

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
        setTimeout(() => {
            window.location.href = "user-login.php";
        }, 3000);
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
