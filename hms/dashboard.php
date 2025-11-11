<?php
session_start();
include('include/config.php');

// ----------------------------
// 1️⃣ Check if user is logged in
// ----------------------------
if (empty($_SESSION['dlogin']) || empty($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

// ----------------------------
// 2️⃣ Optional: Token validation (only if token exists)
// ----------------------------
$userId = intval($_SESSION['id']);
$sessionToken = $_SESSION['token'] ?? '';

if (!empty($sessionToken)) {
    $res = mysqli_query($con, "SELECT session_token FROM users WHERE id='$userId' LIMIT 1");
    $row = mysqli_fetch_assoc($res);

    if (!$row || $row['session_token'] !== $sessionToken) {
        session_unset();
        session_destroy();
        header("Location: user-login.php");
        exit();
    }
}

// ----------------------------
// 3️⃣ Fetch user info
// ----------------------------
$uid = $userId;
$username = 'User';
$email = '';
$initial = 'U';

$res = mysqli_query($con, "SELECT fullName, email FROM users WHERE id='$uid' LIMIT 1");
if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $username = $row['fullName'] ?? 'User';
    $email = $row['email'] ?? '';
    $initial = strtoupper(substr($username, 0, 1));
}

// ----------------------------
// 4️⃣ Fetch user data, appointments, doctors, history
// ----------------------------
$data = [];
$res = mysqli_query($con, "SELECT * FROM users WHERE id='$uid' LIMIT 1");
if ($res && mysqli_num_rows($res) > 0) {
    $data = mysqli_fetch_assoc($res);
}

// Appointments
$res = mysqli_query($con, "SELECT * FROM appointment WHERE userid='$uid' AND appointmentDate >= CURDATE()");
$totalAppointments = ($res) ? mysqli_num_rows($res) : 0;

$activeAppointmentsRes = mysqli_query($con, "SELECT * FROM appointment WHERE userid='$uid' AND appointmentDate >= CURDATE() AND userStatus=1");
$numUpcomingAppointments = ($activeAppointmentsRes) ? mysqli_num_rows($activeAppointmentsRes) : 0;

$numAppointments = ($totalAppointments > 0) ? $totalAppointments : 0;

// Assigned doctors
$docRes = mysqli_query($con, "SELECT DISTINCT doctorId FROM appointment WHERE userid='$uid'");
$numDoctors = ($docRes) ? mysqli_num_rows($docRes) : 0;

// Medical history
$historyData = [];
$checkRes = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$uid' ORDER BY CreationDate ASC");
while ($row = mysqli_fetch_assoc($checkRes)) {
    $historyData[] = $row;
}

// Recent activity
$recentActivity = [];
$activityRes = mysqli_query($con, "
    SELECT CONCAT('Appointment with Dr. ', d.doctorName, ' on ', a.appointmentDate, ' at ', a.appointmentTime) AS activity,
           a.postingDate AS activityTime
    FROM appointment a
    JOIN doctors d ON a.doctorId = d.id
    WHERE a.userid='$uid'
    UNION ALL
    SELECT CONCAT('Medical Checkup: BP-', mh.BloodPressure, ', Weight-', mh.Weight, ', Temp-', mh.Temperature) AS activity,
           mh.CreationDate AS activityTime
    FROM tblmedicalhistory mh
    WHERE mh.PatientID='$uid'
    ORDER BY activityTime DESC
    LIMIT 5
");
if ($activityRes && mysqli_num_rows($activityRes) > 0) {
    while ($row = mysqli_fetch_assoc($activityRes)) {
        $recentActivity[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard | HeavenKare Patient</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../src/output.css" rel="stylesheet" />

    <style>
    :root {
        --sidebar-w: 280px;
    }
    </style>

</head>

<body class="dashboard-body">

    <!-- Fullscreen Overlay Loader -->
    <div id="loader-overlay"
        class="fixed inset-0 bg-[#141421] flex items-center justify-center z-[99999] opacity-100 transition-opacity duration-400">
        <div class="w-12 h-12 rounded-full border-4 border-[rgba(255,255,255,0.2)] border-t-[#ff6e7f] animate-spin">
        </div>
    </div>

    <div id="app" class="dashboard-container">
        <?php include('./include/sidebar.php'); ?>
        <div id="overlay" class="dashboard-overlay"></div>

        <div id="main" class="dashboard-main">
            <?php include('include/header.php'); ?>

            <main id="main-content" class="dashboard-main-content ">
                <div class="adminui-table__wrapper relative !p-4">

                    <?php if (isset($_SESSION['success_msg'])): ?>
                    <div id="toast-success"
                        class="absolute top-4 right-4 z-50 !admin-card-section-full bg-[#1F2937] text-white px-5 py-4 rounded-xl shadow-lg flex items-start space-x-4 opacity-0 translate-x-4 transform transition-all duration-500 pointer-events-auto">

                        <div class="flex-shrink-0 mt-1">
                            <i class="fa-solid fa-circle-check text-green-400 text-xl"></i>
                        </div>

                        <div class="flex-1 text-sm md:text-base font-medium leading-snug pt-1">
                            <?= $_SESSION['success_msg'] ?>
                        </div>

                        <button id="toast-success-close" class="flex-shrink-0 text-gray-400 hover:text-gray-200 mt-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <?php unset($_SESSION['success_msg']);
                    endif; ?>

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

                    <!-- Header -->
                    <header class="admin-card-section-full admin-card-header !py-4 mb-6">
                        <h1 class="!text-2xl lg:!text-4xl">Welcome back,
                            <span><?= htmlentities($username) ?>!</span>
                        </h1>
                        <p>Here's a quick summary of your hospital statistics and recent activity.</p>
                    </header>

                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Appointment -->
                        <div class="dashboard-card dashboard-card--sky order-1">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-calendar-check text-lg"></i>
                                </div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Appointments</p>
                                    <h2 class="dashboard-card__value"><?= $numAppointments ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="appointment-history.php" class="dashboard-card__link">
                                    See details <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Doctor -->
                        <div class="dashboard-card dashboard-card--emerald order-2">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-user-doctor text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Assigned Doctors</p>
                                    <h2 class="dashboard-card__value"><?= $numDoctors ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="book-appointment.php" class="dashboard-card__link">
                                    Book New Appointment <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Medical History -->
                        <div class="dashboard-card dashboard-card--violet order-3">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-file-medical text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Medical Record</p>
                                    <h2 class="dashboard-card__value"><?= count($historyData) ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="medical-history.php" class="dashboard-card__link">
                                    View History <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Medications -->
                        <div class="dashboard-card dashboard-card--yellow order-4">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-pills text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Medications</p>
                                    <h2 class="dashboard-card__value">0</h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="#" class="dashboard-card__link">
                                    View Medications <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Upcoming Checkups -->
                        <aside
                            class="dashboard-recent-activity col-span-1 sm:col-span-2 md:col-span-2 lg:col-span-2 order-5 dashboard-card--white">
                            <h4 class="dashboard-heading dashboard-heading__title">Upcoming Checkups</h4>
                            <ul class="dashboard-recent-activity__list">
                                <?php
                                $userId = intval($_SESSION['id'] ?? 0);
                                if ($userId > 0) {
                                    $appointments = mysqli_query($con, "
                                        SELECT a.*, d.doctorName 
                                        FROM appointment a
                                        JOIN doctors d ON a.doctorId = d.id
                                        WHERE a.userId = '$userId' AND a.appointmentDate >= CURDATE() AND a.userStatus=1
                                        ORDER BY a.appointmentDate ASC, a.appointmentTime ASC
                                    ");

                                    if ($appointments && mysqli_num_rows($appointments) > 0) {
                                        while ($appointment = mysqli_fetch_assoc($appointments)) {
                                            $appointmentDateTime = date('Y-m-d \a\t h:i A', strtotime($appointment['appointmentDate'] . ' ' . $appointment['appointmentTime']));
                                            echo '<li class="dashboard-recent-activity__item">
                                                <span>Appointment with Dr. ' . htmlentities($appointment['doctorName']) . '</span>
                                                <span class="dashboard-recent-activity__time">' . $appointmentDateTime . '</span>
                                              </li>';
                                        }
                                    } else {
                                        echo '<li class="dashboard-recent-activity__item">No upcoming checkups</li>';
                                    }
                                }
                                ?>
                            </ul>
                            <div class="dashboard-card__footer">
                                <a href="appointment-history.php" class="dashboard-card__link">
                                    See more <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </aside>

                        <!-- Overview Chart -->
                        <div
                            class="dashboard-overview col-span-1 sm:col-span-2 md:col-span-2 lg:!col-span-3 order-6 dashboard-card--white">
                            <h3 class="dashboard-heading dashboard-heading__title">Health Overview</h3>
                            <p class="dashboard-heading__subtitle">Track your health metrics and appointment trends.</p>
                            <div class="dashboard-overview__chart">
                                <canvas id="healthChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('healthChart').getContext('2d');
    const healthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_map(fn($h) => date('d M', strtotime($h['CreationDate'])), $historyData)) ?>,
            datasets: [{
                    label: 'Blood Pressure',
                    data: <?= json_encode(array_map(fn($h) => (int)$h['BloodPressure'], $historyData)) ?>,
                    borderColor: '#ff6e7f',
                    backgroundColor: 'rgba(255,110,127,0.2)',
                    tension: 0.3
                },
                {
                    label: 'Weight',
                    data: <?= json_encode(array_map(fn($h) => (float)$h['Weight'], $historyData)) ?>,
                    borderColor: '#8e44ad',
                    backgroundColor: 'rgba(142,68,173,0.2)',
                    tension: 0.3
                },
                {
                    label: 'Temperature',
                    data: <?= json_encode(array_map(fn($h) => (float)$h['Temperature'], $historyData)) ?>,
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243,156,18,0.2)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../dist/main.js"></script>
    <script src="../dist/loader.js"></script>

    <!-- Logout pop up -->
    <script>
    ['logoutSidebar', 'logoutHeader'].forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout Confirmation',
                    text: "Are you sure you want to logout?",
                    icon: 'warning',
                    background: '#2A2A40',
                    color: '#EAEAEA',
                    iconColor: '#60A5FA',
                    showCancelButton: true,
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: 'Yes, Logout',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-2xl shadow-lg border border-[#3A3A5A] p-4 sm:p-6 md:p-8 text-center',
                        title: 'text-base sm:text-lg md:text-xl font-semibold text-[#EAEAEA]',
                        htmlContainer: 'text-sm sm:text-base md:text-lg text-gray-300',
                        confirmButton: 'px-4 py-2 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base font-medium',
                        cancelButton: 'px-4 py-2 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php';
                    }
                });
            });
        }
    });
    </script>

    <!-- Dashboard success login msg -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast-success');
        if (!toast) return;
        const closeBtn = document.getElementById('toast-success-close');
        setTimeout(() => {
            toast.classList.remove('translate-x-4', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);
        const timer = setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-4', 'opacity-0');
            setTimeout(() => toast.remove(), 1200);
        }, 4000);
        closeBtn.addEventListener('click', () => {
            clearTimeout(timer);
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-4', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        });
    });
    </script>

</body>

</html>