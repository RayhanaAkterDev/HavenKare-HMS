<?php
session_start();
include("include/config.php");

// ===== Login Check =====
if (empty($_SESSION['dlogin']) || empty($_SESSION['did']) || empty($_SESSION['isLoggedIn'])) {
    header("Location: index.php");
    exit();
}

$doctorName = $_SESSION['dlogin'];
$doctorId   = intval($_SESSION['did']);

// ===== Fetch Doctor Info =====
$stmt = $con->prepare("SELECT * FROM doctors WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $doctorId);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$stmt->close();

if (!$doctor) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// ===== Dashboard Stats =====

// Total Patients (this doctor)
$patientQuery = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$doctorId'");
$numPatients = mysqli_num_rows($patientQuery);

// Today's Appointments
$today = date('Y-m-d');
$apptTodayQuery = mysqli_query($con, "SELECT * FROM appointment WHERE doctorId='$doctorId' AND appointmentDate='$today'");
$numToday = mysqli_num_rows($apptTodayQuery);

// Total Appointments
$apptAllQuery = mysqli_query($con, "SELECT * FROM appointment WHERE doctorId='$doctorId'");
$numAppointments = mysqli_num_rows($apptAllQuery);

// Recent 5 Appointments
$recentAppointments = mysqli_query($con, "
    SELECT 
        a.id,
        a.appointmentDate,
        a.appointmentTime,
        p.PatientName,
        p.PatientEmail,
        p.PatientContno
    FROM appointment a
    JOIN tblpatient p ON a.userId = p.ID
    WHERE a.doctorId = '$doctorId'
    ORDER BY a.appointmentDate DESC, a.appointmentTime DESC
    LIMIT 5
");
?>


<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Doctor Dashboard | HeavenKare</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Tailwind Output -->
    <link href="../../src/output.css" rel="stylesheet" />

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

        <!-- Spinner -->
        <div class="w-12 h-12 rounded-full border-4 border-[rgba(255,255,255,0.2)] border-t-[#ff6e7f] animate-spin">
        </div>
    </div>

    <div id="app" class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include('./include/sidebar.php'); ?>

        <!-- MOBILE OVERLAY -->
        <div id="overlay" class="dashboard-overlay"></div>

        <!-- MAIN CONTENT -->
        <div id="main" class="dashboard-main">

            <!-- HEADER -->
            <?php include('include/header.php'); ?>

            <!-- MAIN SECTION -->
            <main id="main-content" class="dashboard-main-content">
                <div class="adminui-table__wrapper">
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
                        <h1 class="text-3xl font-semibold text-gray-900">
                            Welcome back, <span class="text-sky-600">Dr. <?= htmlentities($doctorName) ?></span> 👋
                        </h1>
                        <p class="text-gray-500 mt-1">
                            Manage appointments, review patients, and monitor your clinic stats.
                        </p>
                    </header>


                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

                        <!-- My Patients -->
                        <div class="dashboard-card dashboard-card--yellow">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon">
                                    <i class="fa-solid fa-user-injured text-lg"></i>
                                </div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">My Patients</p>
                                    <h2 class="dashboard-card__value"><?= $numPatients ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="manage-patient.php" class="dashboard-card__link">
                                    View patients <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Today's Appointments -->
                        <div class="dashboard-card dashboard-card--sky">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon">
                                    <i class="fa-solid fa-calendar-day text-lg"></i>
                                </div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Today's Appointments</p>
                                    <h2 class="dashboard-card__value"><?= $numToday ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="manage-appointments.php" class="dashboard-card__link">
                                    View schedule <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Total Appointments -->
                        <div class="dashboard-card dashboard-card--emerald">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon">
                                    <i class="fa-solid fa-stethoscope text-lg"></i>
                                </div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Total Appointments</p>
                                    <h2 class="dashboard-card__value"><?= $numAppointments ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="appointment-history.php" class="dashboard-card__link">
                                    See all <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Recent Appointments -->
                    <section class="dashboard-card dashboard-card--white p-6 rounded-xl shadow">
                        <h3 class="text-xl font-semibold mb-4">Recent Appointments</h3>
                        <ul class="divide-y divide-gray-200">
                            <?php
                            if ($recentAppointments && mysqli_num_rows($recentAppointments) > 0) {
                                while ($appt = mysqli_fetch_assoc($recentAppointments)) {
                                    $apptTime = date('d M Y, h:i A', strtotime($appt['appointmentDate'] . ' ' . $appt['appointmentTime']));
                                    echo '<li class="py-3 flex justify-between text-gray-700">
                                            <span><i class="fa-solid fa-user text-gray-400 mr-2"></i> ' . htmlentities($appt['patientName']) . '</span>
                                            <span class="text-sm text-gray-500">' . $apptTime . '</span>
                                          </li>';
                                }
                            } else {
                                echo '<li class="py-3 text-gray-500 text-sm">No recent appointments</li>';
                            }
                            ?>
                        </ul>
                    </section>

                    <!-- Health Overview Chart -->
                    <div
                        class="mt-8 dashboard-overview col-span-1 sm:col-span-2 md:col-span-2 lg:!col-span-3 order-5 dashboard-card--white">
                        <h3 class="dashboard-heading dashboard-heading__title"> Overview</h3>
                        <p class="dashboard-heading__subtitle">Visual overview of patient and appointment trends.
                        </p>
                        <div class="dashboard-overview__chart">
                            <canvas id="healthChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">
                © 2025 HeavenKare. All rights reserved.
            </footer>
        </div>
    </div>


    <!-- logout pop up -->
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

    <!-- dashboard success login msg -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast-success');
        if (!toast) return;

        const closeBtn = document.getElementById('toast-success-close');

        // Small delay for smooth transition
        setTimeout(() => {
            toast.classList.remove('translate-x-4', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        // Auto hide after 4s
        const timer = setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-4', 'opacity-0');
            setTimeout(() => toast.remove(), 1200);
        }, 4000);

        // Manual close
        closeBtn.addEventListener('click', () => {
            clearTimeout(timer);
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-4', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../dist/main.js"></script>
    <script src="../../dist/loader.js"></script>
</body>

</html>