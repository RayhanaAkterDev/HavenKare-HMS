<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');
include('include/checklogin.php');

$currentPage = basename($_SERVER['PHP_SELF']);
$uid = intval($_SESSION['id'] ?? 0);

$adminData = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $adminData = mysqli_fetch_assoc($res);
        $userName = $adminData['username'] ?? "User";
    }
}

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$query = mysqli_query($con, "SELECT * FROM users WHERE id='$id' LIMIT 1");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<h2 style='text-align:center; padding:20px;'>User not found!</h2>";
    exit;
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>User Details | HeavenKare Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet" />

    <!-- Dashboard CSS -->
    <link href="../../src/output.css" rel="stylesheet" />

    <style>
    :root {
        --sidebar-w: 280px;
    }


    .profile-card {
        background: linear-gradient(145deg, #2A2A40, #1F1F30);
        border-radius: 1rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, #23233A, #1B1B2D);
        border-bottom: 1px solid #3A3A55;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .profile-header h2 {
        color: #fff;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .profile-avatar {
        width: 65px;
        height: 65px;
        border-radius: 9999px;
        background: linear-gradient(135deg, #3f3f63, #292947);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e0e0e0;
        font-size: 1.4rem;
        border: 2px solid #3a3a55;
    }

    .profile-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .section-title {
        color: #cbd5e1;
        font-weight: 600;
        border-bottom: 1px solid #3a3a55;
        padding-bottom: 0.4rem;
        margin-bottom: 0.6rem;
        font-size: 1rem;
    }

    .info-section {
        background-color: rgba(51, 51, 77, 0.5);
        border: 1px solid transparent;
        transition: all 0.25s ease;
    }

    .info-section:hover {
        background-color: rgba(58, 58, 90, 0.7);
        border-color: #464678;
    }

    .info-section p:first-child {
        color: #9ca3af;
        font-size: 0.8rem;
    }

    .info-section p:last-child {
        color: #fff;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .info-section {
        padding: 0.8rem 1rem;
        border-radius: 0.6rem;
    }

    /* --- Responsive Tweaks --- */
    @media (max-width: 1024px) {
        .profile-body {
            padding: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .profile-card {
            border-radius: 0.8rem;
        }

        .profile-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1rem;
        }

        .profile-avatar {
            margin-bottom: 0.5rem;
            width: 55px;
            height: 55px;
            font-size: 1.2rem;
        }

        .profile-body {
            padding: 1rem;
            gap: 1.2rem;
        }

        .section-title {
            font-size: 0.95rem;
        }

        .info-section {
            padding: 0.75rem 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .profile-header {
            padding: 0.9rem;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            font-size: 1rem;
        }

        .info-section p:first-child {
            font-size: 0.75rem;
        }

        .info-section p:last-child {
            font-size: 0.85rem;
        }

        .section-title {
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body class="dashboard-body">
    <div id="app" class="dashboard-container">
        <?php include('./include/sidebar.php'); ?>
        <div id="overlay" class="dashboard-overlay"></div>

        <div id="main" class="dashboard-main">
            <?php include('include/header.php'); ?>

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

                    <!-- Page Header -->
                    <header class="admin-card-section-full admin-card-header !py-3 mb-4">
                        <h1>User Details</h1>
                        <p>View full information about this registered user.</p>
                    </header>

                    <!-- Compact & Responsive User Profile Card -->
                    <div class="max-w-xl mx-auto profile-card mb-12">

                        <!-- Header -->
                        <div class="profile-header">
                            <div class="flex items-center gap-3">
                                <div class="profile-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <h2><?php echo htmlspecialchars($user['fullName'] ?? 'Unnamed'); ?></h2>
                                    <p class="text-gray-400 text-xs">
                                        <?php echo htmlspecialchars($user['email'] ?? '-'); ?>
                                    </p>
                                </div>
                            </div>
                            <span class="text-gray-400 text-xs whitespace-nowrap">User Overview</span>
                        </div>

                        <!-- Body -->
                        <div class="profile-body">

                            <!-- Personal Info -->
                            <section>
                                <h3 class="section-title">Personal Info</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="info-section">
                                        <p>Full Name</p>
                                        <p><?php echo htmlspecialchars($user['fullName'] ?? '-'); ?></p>
                                    </div>
                                    <div class="info-section">
                                        <p>Email</p>
                                        <p><?php echo htmlspecialchars($user['email'] ?? '-'); ?></p>
                                    </div>
                                    <div class="info-section">
                                        <p>Gender</p>
                                        <p><?php echo htmlspecialchars($user['gender'] ?? '-'); ?></p>
                                    </div>
                                    <div class="info-section">
                                        <p>City</p>
                                        <p><?php echo htmlspecialchars($user['city'] ?? '-'); ?></p>
                                    </div>
                                </div>
                            </section>

                            <!-- Address -->
                            <section>
                                <h3 class="section-title">Address</h3>
                                <div class="info-section">
                                    <p>Address</p>
                                    <p><?php echo htmlspecialchars($user['address'] ?? '-'); ?></p>
                                </div>
                            </section>

                            <!-- Account Dates -->
                            <section>
                                <h3 class="section-title">Account Dates</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="info-section">
                                        <p>Registration Date</p>
                                        <p><?php echo htmlspecialchars($user['regDate'] ?? '-'); ?></p>
                                    </div>
                                    <div class="info-section">
                                        <p>Last Updated</p>
                                        <p><?php echo htmlspecialchars($user['updationDate'] ?? '-'); ?></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
</body>

</html>