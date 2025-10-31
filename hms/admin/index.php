<?php
session_start();
error_reporting(0);
include("../include/config.php");

// Handle login submission
if (isset($_POST['submit'])) {
    $uname = trim($_POST['username']);
    $upassword = trim($_POST['password']);

    // ✅ Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT id, username, password FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num = mysqli_fetch_assoc($result);

    if ($num && $upassword === $num['password']) {
        // ✅ Verify credentials (non-hashed for now; matches your DB)
        $_SESSION['login'] = $num['username'];
        $_SESSION['id'] = $num['id']; // this is what fixes your dashboard/aside issue
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="php-bg">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Admin Login</title>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../../src/output.css" rel="stylesheet" />
</head>

<body class="bg-transparent">
    <section class="php-section">
        <div class="php-container">

            <!-- page heading -->
            <div class="form-heading">
                <h2 class="php-headline">HeavenKare HMS</h2>
                <p class="php-text">
                    Access your secure admin dashboard anytime, anywhere.
                </p>
            </div>

            <div class="php-card">
                <div class="php-illustration">
                    <div class="php-overlay"></div>
                    <div class="php-illustration-content">
                        <i class="fa-solid fa-user-shield fa-5x text-white/70"></i>
                        <p class="php-text !text-lg !text-white/60">Admin Login</p>
                    </div>
                </div>

                <div class="php-form-wrapper p-6 md:p-8 bg-white rounded-lg shadow-md">
                    <div class="form-heading">
                        <h2 class="php-form-title">Log in to your account</h2>
                        <p class="php-form-subtitle">Enter your username and password to continue</p>
                    </div>

                    <form method="POST" class="php-form space-y-4">
                        <!-- error message -->
                        <?php if (!empty($_SESSION['errmsg'])): ?>
                        <div class="php-error text-red-600 text-sm flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i>
                            <span><?php echo htmlentities($_SESSION['errmsg']); ?></span>
                        </div>
                        <?php $_SESSION['errmsg'] = ""; ?>
                        <?php endif; ?>

                        <div class="php-field">
                            <i class="fa-regular fa-user php-icon"></i>
                            <input type="text" name="username" placeholder="Username" required class="php-input" />
                        </div>

                        <div class="php-field">
                            <i class="fa-solid fa-lock php-icon"></i>
                            <input type="password" name="password" placeholder="Password" required class="php-input" />
                        </div>

                        <button type="submit" name="submit" class="php-btn w-full flex justify-center items-center">
                            Login <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>

                        <p class="php-link-text mt-4">
                            <a href="../../index.php" class="php-link">Back to Home Page</a>
                        </p>
                    </form>
                </div>
            </div>

            <p class="php-footer">
                © 2025 <span>HMS</span>. All Rights Reserved.
            </p>
        </div>
    </section>

    <script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
    </script>
</body>

</html>