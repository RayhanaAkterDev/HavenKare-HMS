<?php
session_start();
include("include/config.php");
error_reporting(E_ALL);

if (!$con) die("Database connection failed: " . mysqli_connect_error());

if (isset($_POST['submit'])) {
    $uname = trim($_POST['username']);
    $upassword = trim($_POST['password']);
    $hashed_password = md5($upassword); // IMPORTANT: match DB hash
    $uip = $_SERVER['REMOTE_ADDR'];

    $stmt = mysqli_prepare($con, "SELECT id, doctorName FROM doctors WHERE docEmail = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $uname, $hashed_password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $doctorName);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        $_SESSION['dlogin'] = $doctorName;
        $_SESSION['did'] = $id;
        $_SESSION['isLoggedIn'] = true;

        // Optional: log login
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES('$id','$uname','$uip',1)");

        mysqli_stmt_close($stmt);
        session_write_close();
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid email or password";
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES(NULL,'$uname','$uip',0)");

        mysqli_stmt_close($stmt);
        session_write_close();
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
    <title>HeavenKare | Doctor Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="../../src/output.css" rel="stylesheet" />
</head>

<body class="bg-transparent">

    <section class="php-section">
        <div class="php-container">
            <div class="form-heading">
                <h2 class="php-headline">HeavenKare HMS</h2>
                <p class="php-text">Access your secure doctor dashboard anytime, anywhere.</p>
            </div>

            <div class="php-card">
                <div class="php-illustration">
                    <div class="php-overlay"></div>
                    <div class="php-illustration-content">
                        <i class="fa-solid fa-user-doctor fa-5x text-white/70"></i>
                        <p class="php-text !text-lg !text-white/60">Doctor Login</p>
                    </div>
                </div>

                <div class="php-form-wrapper p-6 md:p-8 bg-white rounded-lg shadow-md">
                    <div class="form-heading">
                        <h2 class="php-form-title">Log in to your account</h2>
                        <p class="php-form-subtitle">Enter your email and password to continue</p>
                    </div>

                    <form method="POST" class="php-form space-y-4">

                        <?php if (!empty($login_error)): ?>
                        <span id="loginError" class="php-error show">
                            <i class="fas fa-circle-exclamation text-red-700"></i>
                            <span id="loginErrorText"><?php echo htmlentities($login_error); ?></span>
                        </span>
                        <?php endif; ?>

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

                        <button type="submit" name="submit" class="php-btn w-full flex justify-center items-center">
                            Login <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <p class="php-footer">© 2025 <span>HMS</span>. All Rights Reserved.</p>
        </div>
    </section>

</body>

</html>