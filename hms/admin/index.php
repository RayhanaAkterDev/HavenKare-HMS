<?php
session_start();
include("../include/config.php");
error_reporting(E_ALL);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $uname = trim($_POST['username']);
    $upassword = trim($_POST['password']);
    $uip = $_SERVER['REMOTE_ADDR'];

    $stmt = mysqli_prepare($con, "SELECT id, username FROM admin WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $uname, $upassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $username);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        $_SESSION['login'] = $username;
        $_SESSION['id'] = $id;
        $_SESSION['isLoggedIn'] = true;

        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES('$id','$uname','$uip',1)");

        session_write_close();
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES(NULL,'$uname','$uip',0)");

        session_write_close();
        header("Location: index.php");
        exit();
    }

    mysqli_stmt_close($stmt);
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

    <!-- Fullscreen Overlay Loader -->
    <div id="loader-overlay"
        class="fixed inset-0 flex items-center justify-center z-[99999] bg-gradient-to-br from-[#e7f3ff] via-white to-[#f9fdff] opacity-100 transition-opacity duration-400 overflow-hidden">

        <!-- Spinner -->
        <div
            class="w-12 h-12 rounded-full border-4 border-[rgba(0,0,0,0.1)] border-t-transparent border-b-transparent border-l-sky-600 border-r-sky-800 animate-spin">
        </div>
    </div>

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
                        <span id="loginError" class="php-error hidden flex items-center gap-1 text-red-600 text-sm">
                            <i class="fas fa-circle-exclamation"></i>
                            <span id="loginErrorText"></span>
                        </span>

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

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
    </script>

    <script src="../../dist/loader.js"></script>
</body>

</html>