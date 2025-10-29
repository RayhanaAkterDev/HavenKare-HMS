<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("include/config.php");

if (isset($_POST['submit'])) {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    // Basic validation
    if (empty($name) || empty($email)) {
        $_SESSION['errmsg'] = "Please provide your full name and registered email address.";
        header("Location: forgot-password.php");
        exit();
    }

    // Sanitize inputs
    $name = mysqli_real_escape_string($con, $name);
    $email = mysqli_real_escape_string($con, $email);

    // Check if user exists
    $query = mysqli_query($con, "SELECT id FROM users WHERE fullName='$name' AND email='$email'");
    $row = mysqli_num_rows($query);

    if ($row > 0) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        header("Location: reset-password.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "These credentials do not match our records.";
        header("Location: forgot-password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] min-h-screen">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HeavenKare | Password Recovery</title>

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

                <!-- Error message (same style as login) -->
                <span id="fpError" class="bei-login__error hidden">
                    <i class="fas fa-circle-exclamation text-red-700"></i>
                    <span id="fpErrorText"></span>
                </span>

                <!-- Form -->
                <form method="post" class="bei-fp-form">
                    <div class="bei-fp-field">
                        <i class="fa-solid fa-user bei-fp-icon-field"></i>
                        <input type="text" name="fullname" placeholder="Full Name" class="bei-fp-input">
                    </div>

                    <div class="bei-fp-field">
                        <i class="fa-regular fa-envelope bei-fp-icon-field"></i>
                        <input type="email" name="email" placeholder="Email Address" class="bei-fp-input">
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

    <!-- Error Message Script -->
    <script>
        function showError(msg) {
            const errorDiv = document.getElementById('fpError');
            const errorText = document.getElementById('fpErrorText');
            errorText.textContent = msg;
            errorDiv.classList.remove('hidden');
            errorDiv.classList.add('show');
        }

        <?php if (!empty($_SESSION['errmsg'])): ?>
            showError("<?php echo $_SESSION['errmsg']; ?>");
            <?php $_SESSION['errmsg'] = ""; ?>
        <?php endif; ?>
    </script>

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
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
