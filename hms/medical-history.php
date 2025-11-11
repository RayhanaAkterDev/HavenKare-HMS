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

if (empty($_SESSION['id'])) {
    header('location:logout.php');
    exit();
}

// Use session user ID directly
$vid = intval($_SESSION['id']);

// Fetch medical history (newest first)
$ret = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$vid' ORDER BY CreationDate DESC");

// Optional: debug SQL error
if (!$ret) {
    echo "Error fetching medical history: " . mysqli_error($con);
    exit();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Medical History | HeavenKare Patient</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <link href="../src/output.css" rel="stylesheet" />
    <style>
    :root {
        --sidebar-w: 280px;
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

                    <div class="admin-card-section-full flex justify-end mr-3">
                        <a href="book-appointment.php" class="dashboard-card__link">
                            Book new appointments <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>Medical History</h1>
                        <p>View all your medical history</p>
                    </header>

                    <table class="adminui-table" id="sample-table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Blood Pressure</th>
                                <th>Weight</th>
                                <th>Blood Sugar</th>
                                <th>Body Temperature</th>
                                <th>Medical Prescription</th>
                                <th>Visit Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            if ($ret && mysqli_num_rows($ret) > 0) {
                                while ($row = mysqli_fetch_array($ret)) {
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt++; ?>.</td>
                                <td data-label="Blood Pressure"><?php echo htmlentities($row['BloodPressure']); ?></td>
                                <td data-label="Weight"><?php echo htmlentities($row['Weight']); ?></td>
                                <td data-label="Blood Sugar"><?php echo htmlentities($row['BloodSugar']); ?></td>
                                <td data-label="Body Temperature"><?php echo htmlentities($row['Temperature']); ?></td>
                                <td data-label="Medical Prescription"><?php echo htmlentities($row['MedicalPres']); ?>
                                </td>
                                <td data-label="Visit Date"><?php echo htmlentities($row['CreationDate']); ?></td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center text-gray-500 py-4">No medical history found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../dist/main.js"></script>
</body>

</html>