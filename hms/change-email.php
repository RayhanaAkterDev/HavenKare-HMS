<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('include/config.php');

// 🚨 Step 1: Check if logged in
if (empty($_SESSION['login']) || $_SESSION['login'] !== true || empty($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

// 🚨 Step 2: Verify session token (prevents manual or multiple login)
$userId = intval($_SESSION['id']);
$sessionToken = $_SESSION['token'] ?? '';

$res = mysqli_query($con, "SELECT session_token FROM users WHERE id='$userId' LIMIT 1");
$row = mysqli_fetch_assoc($res);

if (!$row || $row['session_token'] !== $sessionToken) {
    session_unset();
    session_destroy();
    header("Location: user-login.php");
    exit();
}

// ✅ From here, the user is fully authenticated

// Logged-in patient ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full patient data for profile
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM users WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['fullName'] ?? "User";
    }
}

$errors = [];

// Handle form submission
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $userId = $_SESSION['id'];

    // Check if email exists in other users or in doctors
    $checkUser = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '$userId'");
    $checkDoctor = mysqli_query($con, "SELECT id FROM doctors WHERE docEmail='$email'");

    if (mysqli_num_rows($checkUser) > 0 || mysqli_num_rows($checkDoctor) > 0) {
        $_SESSION['error'] = "Email already in use!";
    } else {
        $sql = mysqli_query($con, "UPDATE users SET email='$email' WHERE id='$userId'");
        if ($sql) {
            $_SESSION['msg'] = "Your email was updated successfully.";
            header("Location: edit-profile.php");
            exit;
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again!";
        }
    }

    // Always redirect after form submission to prevent resubmission popup
    header("Location: change-email.php");
    exit;
}

// If redirected back, capture error message (if any)
if (isset($_SESSION['error'])) {
    $errors[] = $_SESSION['error'];
    unset($_SESSION['error']); // Clear it after showing
}
?>


<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Edit EmailId | HeavenKare Patient</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Dashboard CSS -->
    <link href="../src/output.css" rel="stylesheet" />
    <style>
    :root {
        --sidebar-w: 280px;
    }
    </style>
</head>

<body class="dashboard-body">
    <div id="app" class="dashboard-container">
        <!-- SIDEBAR -->
        <?php include('./include/sidebar.php'); ?>

        <!-- MOBILE OVERLAY -->
        <div id="overlay" class="dashboard-overlay"></div>

        <!-- MAIN CONTENT -->
        <div id="main" class="dashboard-main">
            <!-- HEADER -->
            <?php include('include/header.php'); ?>

            <!-- SCROLLABLE MAIN CONTENT -->
            <main id="main-content" class="dashboard-main-content">
                <div class="adminui-table__wrapper">

                    <!-- Breadcrumb -->
                    <div class="dashboard-header__breadcrumb mb-4">
                        HeavenKare /
                        <?php
                        $currentPath = $_SERVER['PHP_SELF'];
                        $parts = explode('/', trim($currentPath, '/'));
                        $lastTwo = array_slice($parts, -2);
                        foreach ($lastTwo as &$part) {
                            $part = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $part));
                        }
                        echo implode(' / ', $lastTwo);
                        ?>
                    </div>

                    <header class="admin-card-section-full admin-card-header">
                        <h1>Change Email Address</h1>
                        <p> Update your registered email address</p>
                    </header>

                    <div class="admin-card-section-full flex justify-center mt-4">
                        <a href="edit-profile.php" class="dashboard-card__link  ">
                            Go back to edit profile<i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Fetch user data -->
                    <?php
                    $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
                    while ($data = mysqli_fetch_array($sql)) {
                    ?>

                    <!-- Edit Form -->
                    <form role="form" name="updatemail" id="updatemail" method="post" class="adminui-form-accent">

                        <!-- Display Error Message -->
                        <?php if (!empty($errors)) { ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline"><?php echo htmlentities($errors[0]); ?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                    onclick="this.parentElement.parentElement.style.display='none';"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
                                </svg>
                            </span>
                        </div>
                        <?php } ?>

                        <!-- Email -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="email" name="email" id="email" placeholder="Enter new email"
                                    class="input input-bordered w-full" required>
                                <label class="adminui-label">Email</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Update Email</button>
                        </div>

                    </form>
                    <?php } ?>
                </div>
            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../dist/main.js"></script>
</body>

</html>