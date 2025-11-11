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
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Appointment History | HeavenKare Patient</title>

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

                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>My Appointments</h1>
                        <p>See your upcoming and past appointments</p>
                    </header>

                    <div class="admin-card-section-full flex justify-center mb-4">
                        <a href="book-appointment.php" class="dashboard-card__link  ">
                            Book a new appointments<i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <table class="adminui-table" id="sample-table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doctor Specialization</th>
                                <th>Doctor</th>
                                <th>Consultancy Fees</th>
                                <th>Appointment Date & Time</th>
                                <th>Doctor Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $userid = $_SESSION['id'];

                            // 🕒 Sort by nearest appointment date & time
                            $query = mysqli_query($con, "
            SELECT * FROM appointment 
            WHERE userId='$userid' 
            ORDER BY appointmentDate ASC, appointmentTime ASC
        ");

                            $cnt = 1;
                            while ($row = mysqli_fetch_array($query)) {
                                // 🧠 Format date and time
                                $formattedDate = date("M d, Y", strtotime($row['appointmentDate']));
                                $formattedTime = date("g:i A", strtotime($row['appointmentTime']));

                                // Fetch doctor name
                                $docid = $row['doctorId'];
                                $docQuery = mysqli_query($con, "SELECT doctorName FROM doctors WHERE id='$docid'");
                                $docRow = mysqli_fetch_assoc($docQuery);
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt++; ?>.</td>
                                <td data-label="Doctor Specialization">
                                    <?php echo htmlentities($row['doctorSpecialization']); ?>
                                </td>
                                <td data-label="Doctor">
                                    <?php echo htmlentities($docRow['doctorName']); ?>
                                </td>
                                <td data-label="Consultancy Fees">
                                    <?php echo htmlentities($row['consultancyFees']); ?>
                                </td>
                                <td data-label="Appointment Date & Time">
                                    <?php echo $formattedDate . " — " . $formattedTime; ?>
                                </td>
                                <td data-label="Doctor Status">
                                    <?php echo ($row['doctorStatus'] == 1) ? 'Active' : 'Cancelled'; ?>
                                </td>
                            </tr>
                            <?php } ?>
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