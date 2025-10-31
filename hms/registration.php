<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('include/config.php');

if (isset($_POST['submit'])) {
    $fname = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['password_again']);

    // Basic validation
    if (empty($fname) || empty($address) || empty($city) || empty($gender) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['errmsg'] = "All fields are required.";
        header("Location: registration.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errmsg'] = "Please enter a valid email address.";
        header("Location: registration.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['errmsg'] = "Password and Confirm Password do not match.";
        header("Location: registration.php");
        exit();
    }

    // Password complexity check
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        $_SESSION['errmsg'] = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
        header("Location: registration.php");
        exit();
    }

    // Check if email already exists
    $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['errmsg'] = "Email already registered. Please log in.";
        header("Location: registration.php");
        exit();
    }

    // Insert new user
    $password_hashed = md5($password);
    $query = mysqli_query($con, "INSERT INTO users(fullname,address,city,gender,email,password) VALUES('$fname','$address','$city','$gender','$email','$password_hashed')");

    if ($query) {
        $_SESSION['successmsg'] = "Successfully registered! Redirecting to login page...";
        header("Location: registration.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Registration failed. Please try again later.";
        header("Location: registration.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="php-bg">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Patient Registration</title>

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

<body class="php-bg">
    <div class="flex flex-col lg:flex-row">

        <!-- Left Illustration Banner -->
        <div class="php-register-banner">
            <i class="fa fa-hospital fa-10x mb-5 text-white"></i>
            <h1 class="php-headline !text-white/90">Welcome to HeavenCare</h1>
            <p class="php-text !text-white/70">Register and manage your hospital visits with ease. Efficient, safe, and
                reliable.</p>
        </div>

        <!-- Right Form Section -->
        <div class="w-11/12 lg:w-1/2 py-12 mx-auto">
            <!-- Form heading -->
            <div class="form-heading">
                <h2 class="php-headline">Create Your Account</h2>
                <p class="php-text">
                    Join HeavenKare today and manage your hospital visits and health records effortlessly.
                </p>
            </div>

            <div class="php-card py-10 px-4 lg:px-6 lg:w-5/6 mx-auto max-w-lg">

                <!-- Error / Success Messages -->
                <span id="regError" class="php-error hidden">
                    <i class="fas fa-circle-exclamation text-red-700"></i>
                    <span id="regErrorText"></span>
                </span>
                <span id="regSuccess" class="php-success hidden">
                    <i class="fas fa-circle-check text-green-700"></i>
                    <span id="regSuccessText"></span>
                </span>

                <form name="registration" id="registration" method="post" class="php-form w-full">

                    <div class="php-field">
                        <i class="fa fa-user php-icon"></i>
                        <input type="text" name="full_name" placeholder="Full Name" required class="php-input" />
                    </div>

                    <div class="php-field">
                        <i class="fa fa-home php-icon"></i>
                        <input type="text" name="address" placeholder="Address" required class="php-input" />
                    </div>

                    <div class="php-field">
                        <i class="fa fa-city php-icon"></i>
                        <input type="text" name="city" placeholder="City" required class="php-input" />
                    </div>

                    <div class="php-field">
                        <i class="fa fa-venus-mars php-icon"></i>
                        <div class="flex items-center gap-4">
                            <label><input type="radio" name="gender" value="female" class="php-radio"> Female</label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="male" class="php-radio"> Male
                            </label>
                        </div>
                    </div>

                    <div class="php-field">
                        <i class="fa fa-envelope php-icon"></i>
                        <input type="email" name="email" id="email" placeholder="Email" required class="php-input" />
                    </div>

                    <div class="php-field">
                        <i class="fa fa-lock php-icon"></i>
                        <input type="password" name="password" id="password" placeholder="Password" required
                            class="php-input" />
                    </div>

                    <div class="php-field">
                        <i class="fa fa-lock php-icon"></i>
                        <input type="password" name="password_again" id="password_again" placeholder="Confirm Password"
                            required class="php-input" />
                    </div>

                    <div class="php-field border-none !bg-transparent">
                        <input type="checkbox" id="agree" value="agree" checked readonly
                            class="php-checkbox accent-sky-600" />
                        <label for="agree" class="text-slate-600 text-sm">I agree to the terms and conditions</label>
                    </div>

                    <button type="submit" name="submit" class="php-btn">
                        Register <i class="fa fa-arrow-right ml-2"></i>
                    </button>

                    <p class="php-link-text">
                        Already have an account?
                        <a href="user-login.php" class="php-link">Log in</a>
                    </p>
                </form>
            </div>

            <p class="php-footer">
                © 2025 <span>HeavenKare HSM</span>. All Rights Reserved.
            </p>
        </div>
    </div>

    <!-- Message Script -->
    <script>
    function showError(msg) {
        const err = document.getElementById('regError');
        const errTxt = document.getElementById('regErrorText');
        errTxt.textContent = msg;
        err.classList.remove('hidden');
        err.classList.add('show');
    }

    function showSuccess(msg) {
        const suc = document.getElementById('regSuccess');
        const sucTxt = document.getElementById('regSuccessText');
        sucTxt.textContent = msg;
        suc.classList.remove('hidden');
        suc.classList.add('show');

        // Redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = 'user-login.php';
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
</body>

</html>