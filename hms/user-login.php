<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("include/config.php");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $puname = mysqli_real_escape_string($con, $_POST['username']);
    $ppwd   = mysqli_real_escape_string($con, md5($_POST['password']));
    $uip    = $_SERVER['REMOTE_ADDR'];

    $ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' AND password='$ppwd' LIMIT 1");
    $num = mysqli_fetch_assoc($ret);

    if ($num) {
        // ✅ Correct credentials
        $_SESSION['login']  = true;
        $_SESSION['id']     = $num['id'];
        $_SESSION['dlogin'] = $num['fullName']; // <<< ADDED
        $token              = bin2hex(random_bytes(16));
        $_SESSION['token']  = $token;

        // Save token in DB
        mysqli_query($con, "UPDATE users SET session_token='$token' WHERE id='" . $num['id'] . "'");

        // Log user activity
        $pid    = $num['id'];
        $status = 1;
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES('$pid','$puname','$uip','$status')");

        $_SESSION['success_msg'] = "You're logged in successfully!";
        header("Location: dashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="php-bg">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="192x192" href="./assets/favicon/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="192x192" href="./assets/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="./assets/favicon/favicon.ico" type="image/x-icon" />

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Tailwind / custom CSS -->
    <link href="../src/output.css" rel="stylesheet">
</head>

<body class="bg-transparent">

    <!-- Fullscreen Overlay Loader -->
    <div id="loader-overlay"
        class="fixed inset-0 flex items-center justify-center z-[99999] bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] opacity-100 transition-opacity duration-400 overflow-hidden">
        <div
            class="w-12 h-12 rounded-full border-4 border-[rgba(0,0,0,0.1)] border-t-transparent border-b-transparent border-l-sky-600 border-r-sky-800 animate-spin">
        </div>
    </div>

    <section class="php-section">
        <div class="php-container">
            <!-- Page heading -->
            <div class="form-heading">
                <h2 class="php-headline">HeavenKare HMS</h2>
                <p class="php-text">
                    Access your secure health dashboard anytime, anywhere.
                </p>
            </div>

            <div class="php-card">
                <div class="php-illustration">
                    <div class="php-overlay"></div>
                    <div class="php-illustration-content">
                        <i class="fa-solid fa-procedures fa-5x text-white/70"></i>
                        <p class="php-text !text-lg !text-white/60">
                            Patient Login
                        </p>
                    </div>
                </div>

                <div class="php-form-wrapper">
                    <div class="form-heading">
                        <h2 class="php-form-title">Log in to your account</h2>
                        <p class="php-form-subtitle">Enter your email and password to continue</p>
                    </div>

                    <form method="POST" class="php-form">

                        <span id="loginError" class="php-error hidden">
                            <i class="fas fa-circle-exclamation text-red-700"></i>
                            <span id="loginErrorText"></span>
                        </span>

                        <div class="php-field">
                            <i class="fa-regular fa-envelope php-icon"></i>
                            <input type="email" name="username" placeholder="Email Address" required
                                class="php-input" />
                        </div>

                        <div class="php-field">
                            <i class="fa-solid fa-lock php-icon"></i>
                            <input type="password" name="password" placeholder="Password" required class="php-input" />
                        </div>

                        <div class="text-right">
                            <a href="./forgot-password.php"
                                class="text-sm text-sky-500 hover:text-sky-600 hover:underline font-medium transition">Forgot
                                Password?</a>
                        </div>

                        <button type="submit" name="submit" class="php-btn">
                            Login <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>

                        <p class="php-link-text">
                            Don’t have an account?
                            <a href="registration.php" class="php-link">Create one</a>
                        </p>
                    </form>
                </div>
            </div>

            <p class="php-footer">
                © 2025 <span>HeavenKare HSM</span>. All Rights Reserved.
            </p>
        </div>
    </section>

    <script>
    function showError(msg) {
        const errorDiv = document.getElementById('loginError');
        const errorText = document.getElementById('loginErrorText');
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
    <script src="../dist/loader.js"></script>

</body>

</html>