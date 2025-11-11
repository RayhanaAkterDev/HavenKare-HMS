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

// timezone
date_default_timezone_set('Asia/Dhaka');
$currentTime = date('d-m-Y h:i:s A', time());

// initialize session containers
$_SESSION['msg1'] = $_SESSION['msg1'] ?? null;
$_SESSION['errors'] = $_SESSION['errors'] ?? null;

// Handle POST (server-side validation + PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $current = isset($_POST['cpass']) ? trim($_POST['cpass']) : '';
    $new = isset($_POST['npass']) ? trim($_POST['npass']) : '';
    $confirm = isset($_POST['cfpass']) ? trim($_POST['cfpass']) : '';

    $errors = [];

    // server-side validation (no JS alerts)
    if ($current === '' || $new === '' || $confirm === '') {
        $errors[] = "All password fields are required!";
    } elseif ($new !== $confirm) {
        $errors[] = "New password and Confirm password do not match!";
    } else {
        // verify current password
        $qry = mysqli_query($con, "SELECT id FROM users WHERE password='" . md5($current) . "' AND id='" . $_SESSION['id'] . "'");
        if ($qry && mysqli_num_rows($qry) > 0) {
            // update password
            $upd = mysqli_query($con, "UPDATE users SET password='" . md5($new) . "', updationDate='$currentTime' WHERE id='" . $_SESSION['id'] . "'");
            if ($upd) {
                $_SESSION['msg1'] = "Password Changed Successfully !!";
            } else {
                $errors[] = "Something went wrong while updating password. Please try again!";
            }
        } else {
            $errors[] = "Old password does not match!";
        }
    }

    // store errors (if any) in session and redirect to avoid form resubmission
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    } else {
        // ensure errors cleared
        unset($_SESSION['errors']);
    }

    // Redirect to same page (PRG) so reload won't resubmit the form
    header("Location: change-password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User | Change Password</title>

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

    <!-- removed JS alerts; keep valid() if you want client-side checks later -->
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
                        <h1>Change Password</h1>
                        <p> Update your account password securely</p>
                    </header>

                    <!-- Form Section -->
                    <form role="form" name="chngpwd" method="post" class="adminui-form-accent">


                        <!-- Message area: display session success or errors (then clear) -->
                        <?php if (!empty($_SESSION['msg1'])) { ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Success: </strong>
                            <span class="block sm:inline"><?php echo htmlentities($_SESSION['msg1']); ?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                    onclick="this.parentElement.parentElement.style.display='none';"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
                                </svg>
                            </span>
                        </div>
                        <?php unset($_SESSION['msg1']);
                        } ?>

                        <?php if (!empty($_SESSION['errors']) && is_array($_SESSION['errors'])) { ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline"><?php echo htmlentities($_SESSION['errors'][0]); ?></span>
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
                        <?php unset($_SESSION['errors']);
                        } ?>

                        <!-- Current Password -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="password" name="cpass" placeholder="Enter Current Password"
                                    class="input input-bordered w-full" required>
                                <label class="adminui-label">Current Password</label>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="password" name="npass" placeholder="Enter New Password"
                                    class="input input-bordered w-full" required>
                                <label class="adminui-label">New Password</label>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="password" name="cfpass" placeholder="Confirm New Password"
                                    class="input input-bordered w-full" required>
                                <label class="adminui-label">Confirm Password</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">
                                <i class="fa-solid fa-key mr-2"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../dist/main.js"></script>
</body>

</html>