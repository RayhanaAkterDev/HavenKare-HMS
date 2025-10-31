<?php
session_start();
include("../include/config.php");
error_reporting(0);
if (isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $dpassword = md5($_POST['password']);
    $ret = mysqli_query($con, "SELECT * FROM doctors WHERE docEmail='$uname' and password='$dpassword'");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        $_SESSION['dlogin'] = $_POST['username'];
        $_SESSION['id'] = $num['id'];
        $uid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;
        $log = mysqli_query($con, "insert into doctorslog(uid,username,userip,status) values('$uid','$uname','$uip','$status')");
        header("location:dashboard.php");
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        mysqli_query($con, "insert into doctorslog(username,userip,status) values('$uname','$uip','$status')");
        echo "<script>alert('Invalid username or password');</script>";
        echo "<script>window.location.href='index.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="php-bg">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>    <title>HeavenKare | Admin Login</title>
 | Doctor Login</title>

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
                    Access your secure doctor
                    dashboard anytime, anywhere.
                </p>
            </div>

            <div class="php-card">
                <div class="php-illustration">
                    <div class="php-overlay"></div>
                    <div class="php-illustration-content ">
                        <!-- App icon -->
                        <i class="fa-solid fa-user-doctor fa-5x text-white/70"></i>
                        <!-- App name -->
                        <p class="php-text !text-lg !text-white/60 ">Doctor Login</p>
                    </div>
                </div>

                <div class="php-form-wrapper p-6 md:p-8 bg-white rounded-lg shadow-md">
                    <div class="form-heading">
                        <h2 class="php-form-title">Log in to your account</h2>
                        <p class="php-form-subtitle">Enter your email and password to continue</p>
                    </div>

                    <form method="POST" class="php-form space-y-4">
                        <span id="loginError" class="php-error hidden text-red-600 text-sm flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i>
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

                        <div class="text-right mb-4">
                            <a href="./forgot-password.php"
                                class="text-sm text-sky-500 hover:text-sky-600 hover:underline font-medium transition">
                                Forgot Password?
                            </a>
                        </div>

                        <button type="submit" name="submit" class="php-btn w-full flex justify-center items-center">
                            Login <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
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
    </script>

    <script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
    </script>
</body>

</html>
