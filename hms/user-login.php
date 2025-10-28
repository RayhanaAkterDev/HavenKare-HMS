<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("include/config.php");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $puname = mysqli_real_escape_string($con, $_POST['username']);
    $ppwd = mysqli_real_escape_string($con, md5($_POST['password']));

    $ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' AND password='$ppwd'");
    $num = mysqli_fetch_array($ret);
    $uip = $_SERVER['REMOTE_ADDR'];

    if ($num > 0) {
        $_SESSION['login'] = $puname;
        $_SESSION['id'] = $num['id'];
        $pid = $num['id'];
        $status = 1;
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES('$pid','$puname','$uip','$status')");
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['login'] = $puname;
        $status = 0;
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES(NULL,'$puname','$uip','$status')");
        $_SESSION['errmsg'] = "Invalid username or password";
        header("Location: user-login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-[#e7f3ff] to-white min-h-screen">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Patient Login</title>

    <!-- Google fonts: Poppins & Open sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet">
</head>

<body class="bg-transparent">

    <section class="bei-login-section">
        <div class="bei-login__container">
            <div class="bei-login__flex">
                <!-- Left Illustration -->
                <div class="bei-login__illustration">
                    <div class="bei-login__overlay"></div>
                    <div class="bei-login__content">
                        <i class="fa fa-hospital fa-4x mb-4 text-white/70"></i>
                        <h2 class="bei-login__headline">HeavenKare</h2>
                        <p class="bei-login__text">Access your secure health dashboard anytime, anywhere.</p>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="bei-login__form-side">
                    <h2 class="bei-login__form-title">Log in to your account</h2>

                    <form method="POST" class="bei-login__form">
                        <span class="bei-login__error">
                            <?php
                            echo isset($_SESSION['errmsg']) ? $_SESSION['errmsg'] : '';
                            $_SESSION['errmsg'] = "";
                            ?>
                        </span>

                        <div class="bei-login__field">
                            <i class="fa-regular fa-envelope bei-login__icon"></i>
                            <input type="email" name="username" placeholder="Email Address" required
                                class="bei-login__input" />
                        </div>


                        <div class="bei-login__field">
                            <i class="fa-solid fa-lock bei-login__icon"></i>
                            <input type="password" name="password" placeholder="Password" required
                                class="bei-login__input" />
                        </div>

                        <div class="bei-login__actions">
                            <a href="./forgot-password.php" class="bei-login__forgot">Forgot
                                Password?</a>
                        </div>

                        <button type="submit" name="submit" class="bei-login__btn">
                            Login <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>

                        <p class="bei-login__register">
                            Don’t have an account?
                            <a href="registration.php" class="bei-login__register-link">Create one</a>
                        </p>
                    </form>

                    <p class="bei-login__footer">© 2025 <span>HeavenKare HSM</span>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
