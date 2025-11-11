<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
include('include/config.php');

// ===== Login Protection =====
if (empty($_SESSION['isLoggedIn']) || empty($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}


// Logged-in admin ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full admin data
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['username'] ?? "User";
    }
}

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    header('location:search.php');
    exit;
}

$id = intval($_GET['id']);
$type = strtolower($_GET['type']);

// Fetch details based on type
$data = null;
$title = '';
if ($type == 'doctor') {
    $q = mysqli_query($con, "SELECT * FROM doctors WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);
    $title = "Doctor Details";
} elseif ($type == 'patient') {
    $q = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID='$id'");
    $data = mysqli_fetch_assoc($q);
    $title = "Patient Details";
} elseif ($type == 'user') {
    $q = mysqli_query($con, "SELECT * FROM users WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);
    $title = "Registered User Details";
} elseif ($type == 'admin') {
    $q = mysqli_query($con, "SELECT * FROM admin WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);
    $title = "Admin Details";
}

// Appointments (for doctors, patients, users)
$appointments = [];
if ($type == 'doctor') {
    $a = mysqli_query($con, "
        SELECT a.*, u.fullName AS patientName, u.email AS patientEmail
        FROM appointment a
        LEFT JOIN users u ON a.userId = u.id
        WHERE a.doctorId = '$id'
        ORDER BY a.appointmentDate DESC
    ");
    while ($row = mysqli_fetch_assoc($a)) $appointments[] = $row;
} elseif ($type == 'patient' || $type == 'user') {
    $a = mysqli_query($con, "
        SELECT a.*, d.doctorName, d.specilization
        FROM appointment a
        LEFT JOIN doctors d ON a.doctorId = d.id
        WHERE a.userId = '$id'
        ORDER BY a.appointmentDate DESC
    ");
    while ($row = mysqli_fetch_assoc($a)) $appointments[] = $row;
}

// Medical history (only for patients)
$medicalHistory = [];
if ($type == 'patient') {
    $m = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$id' ORDER BY CreationDate DESC");
    while ($row = mysqli_fetch_assoc($m)) $medicalHistory[] = $row;
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= $title ?> | HeavenKare Admin</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Dashboard CSS -->
    <link href="../../src/output.css" rel="stylesheet" />
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

                    <header class="admin-card-header !py-4 mb-4">
                        <h1><?= $title ?></h1>
                        <p>Complete information of this record.</p>
                    </header>

                    <?php if ($data): ?>

                    <div class="w-11/12 max-w-2xl mx-auto dashboard-card dashboard-card--sky">
                        <h2 class="text-2xl font-semibold mb-5 border-b border-gray-800 pb-3">Personal Info</h2>

                        <div class="divide-y divide-gray-800">
                            <?php if ($type == 'doctor'): ?>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Name</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['doctorName']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Specialization</span>
                                <span
                                    class="font-medium text-gray-100"><?= htmlentities($data['specilization']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Contact</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['contactno']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Email</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['docEmail']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-400 w-36">Address</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['address']) ?></span>
                            </div>

                            <?php elseif ($type == 'patient'): ?>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Name</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['PatientName']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Gender</span>
                                <span
                                    class="font-medium text-gray-100"><?= htmlentities($data['PatientGender']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Age</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['PatientAge']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Email</span>
                                <span
                                    class="font-medium text-gray-100"><?= htmlentities($data['PatientEmail']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Mobile</span>
                                <span
                                    class="font-medium text-gray-100"><?= htmlentities($data['PatientContno']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-400 w-36">Address</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['PatientAdd']) ?></span>
                            </div>

                            <?php elseif ($type == 'user'): ?>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Name</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['fullName']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Email</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['email']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Gender</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['gender']) ?></span>
                            </div>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">City</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['city']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-400 w-36">Address</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['address']) ?></span>
                            </div>

                            <?php elseif ($type == 'admin'): ?>
                            <div class="flex items-center border-b border-[#1B1B2D] pb-4">
                                <span class="text-gray-400 w-36">Username</span>
                                <span class="font-medium text-gray-100"><?= htmlentities($data['username']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-400 w-36">Last Updated</span>
                                <span
                                    class="font-medium text-gray-100"><?= htmlentities($data['updationDate']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>



                    <!-- Appointment History -->
                    <?php if (!empty($appointments)): ?>
                    <section class="adminui-section">
                        <h2 class="text-lg font-semibold mb-3">Appointment History</h2>
                        <div class="overflow-x-auto">
                            <table class="adminui-table w-full">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Doctor</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $i => $a): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlentities($a['doctorSpecialization'] ?? $a['specilization'] ?? '-') ?>
                                        </td>
                                        <td><?= htmlentities($a['patientName'] ?? '-') ?></td>
                                        <td><?= htmlentities($a['appointmentDate']) ?></td>
                                        <td><?= htmlentities($a['appointmentTime']) ?></td>
                                        <td>
                                            <?php
                                                        $status = ($a['userStatus'] == 1 && $a['doctorStatus'] == 1) ? 'Active' : 'Cancelled';
                                                        echo "<span class='text-" . ($status == 'Active' ? "green" : "red") . "-600 font-medium'>$status</span>";
                                                        ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Medical History -->
                    <?php if ($type == 'patient' && !empty($medicalHistory)): ?>
                    <div class="my-6 border-t border-gray-500"></div>
                    <section class="adminui-section">
                        <h2 class="text-lg font-semibold mb-3">Medical History</h2>
                        <div class="overflow-x-auto">
                            <table class="adminui-table w-full">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Blood Pressure</th>
                                        <th>Blood Sugar</th>
                                        <th>Weight</th>
                                        <th>Temperature</th>
                                        <th>Prescription</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicalHistory as $i => $m): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlentities($m['BloodPressure']) ?></td>
                                        <td><?= htmlentities($m['BloodSugar']) ?></td>
                                        <td><?= htmlentities($m['Weight']) ?></td>
                                        <td><?= htmlentities($m['Temperature']) ?></td>
                                        <td><?= htmlentities($m['MedicalPres']) ?></td>
                                        <td><?= htmlentities($m['CreationDate']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php else: ?>
                    <p class="text-red-600 font-medium text-center">No record found.</p>
                    <?php endif; ?>

                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
</body>

</html>