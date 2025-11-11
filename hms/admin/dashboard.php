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

// ===== Fetch Admin Info =====
$uid = intval($_SESSION['id']);
$userName = "User";
$data = null;

$res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
if ($res && mysqli_num_rows($res) > 0) {
    $data = mysqli_fetch_assoc($res);
    $userName = $data['username'] ?? "User";
}

// ===== Dashboard Statistics =====
$numPatients = 0;
$numUsers = 0;
$numDoctors = 0;
$numAppointments = 0;

// Total Patients
$resPatients = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM tblpatient");
if ($resPatients && mysqli_num_rows($resPatients) > 0) {
    $numPatients = intval(mysqli_fetch_assoc($resPatients)['cnt']);
}

// Total Registered Users
$resUsers = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM users");
if ($resUsers && mysqli_num_rows($resUsers) > 0) {
    $numUsers = intval(mysqli_fetch_assoc($resUsers)['cnt']);
}

// Total Doctors
$resDoctors = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM doctors");
if ($resDoctors && mysqli_num_rows($resDoctors) > 0) {
    $numDoctors = intval(mysqli_fetch_assoc($resDoctors)['cnt']);
}

// Total Appointments
$resAppointments = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM appointment");
if ($resAppointments && mysqli_num_rows($resAppointments) > 0) {
    $numAppointments = intval(mysqli_fetch_assoc($resAppointments)['cnt']);
}

// Recent 5 Appointments
$recentAppointments = mysqli_query($con, "
    SELECT a.*, 
           u.PatientName AS patientName, 
           d.doctorName 
    FROM appointment a
    JOIN tblpatient u ON a.userId = u.ID
    JOIN doctors d ON a.doctorId = d.id
    ORDER BY a.appointmentDate DESC, a.appointmentTime DESC
    LIMIT 5
");
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard | HeavenKare Admin</title>

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

            <!-- SCROLLABLE MAIN CONTENT -->
            <main id="main-content" class="dashboard-main-content">
                <div class="adminui-table__wrapper relative !p-4">

                    <?php if (isset($_SESSION['success_msg'])): ?>
                    <div id="toast-success"
                        class="absolute top-4 right-4 z-50 !admin-card-section-full bg-[#1F2937] text-white px-5 py-4 rounded-xl shadow-lg flex items-start space-x-4 opacity-0 translate-x-4 transform transition-all duration-500 pointer-events-auto">

                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-1">
                            <i class="fa-solid fa-circle-check text-green-400 text-xl"></i>
                        </div>

                        <!-- Message -->
                        <div class="flex-1 text-sm md:text-base font-medium leading-snug pt-1">
                            <?= $_SESSION['success_msg'] ?>
                        </div>

                        <!-- Close button -->
                        <button id="toast-success-close" class="flex-shrink-0 text-gray-400 hover:text-gray-200 mt-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <?php unset($_SESSION['success_msg']);
                    endif; ?>

                    <!-- Breadcrumb -->
                    <div class="dashboard-header__breadcrumb mb-4">
                        HeavenKare / Admin Dashboard
                    </div>

                    <!-- Header -->
                    <header class="admin-card-section-full admin-card-header !py-4 mb-6">
                        <h1 class="!text-2xl lg:!text-4xl">Welcome back,
                            <span><?= htmlentities($userName) ?>!</span>
                        </h1>
                        <p>Here’s a quick overview of the hospital statistics and recent activity.</p>
                    </header>

                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Total Patients -->
                        <div class="dashboard-card dashboard-card--yellow order-1">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-user-injured text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Total Registered Users</p>
                                    <h2 class="dashboard-card__value"><?= $numUsers ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="manage-users.php" class="dashboard-card__link">See details <i
                                        class="fa-solid fa-arrow-right text-xs"></i></a>
                            </div>
                        </div>

                        <!-- Total Patients -->
                        <div class="dashboard-card dashboard-card--sky order-1">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-user-injured text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Total Patients</p>
                                    <h2 class="dashboard-card__value"><?= $numPatients ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="manage-patient.php" class="dashboard-card__link">See details <i
                                        class="fa-solid fa-arrow-right text-xs"></i></a>
                            </div>
                        </div>

                        <!-- Total Doctors -->
                        <div class="dashboard-card dashboard-card--emerald order-2">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-user-doctor text-lg"></i></div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Total Doctors</p>
                                    <h2 class="dashboard-card__value"><?= $numDoctors ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="manage-doctors.php" class="dashboard-card__link">See details <i
                                        class="fa-solid fa-arrow-right text-xs"></i></a>
                            </div>
                        </div>

                        <!-- Total Appointments -->
                        <div class="dashboard-card dashboard-card--sky order-3">
                            <div class="dashboard-card__info">
                                <div class="dashboard-card__icon"><i class="fa-solid fa-calendar-check text-lg"></i>
                                </div>
                                <div class="dashboard-card__text">
                                    <p class="dashboard-card__label">Appointments</p>
                                    <h2 class="dashboard-card__value"><?= $numAppointments ?></h2>
                                </div>
                            </div>
                            <div class="dashboard-card__footer">
                                <a href="appointment-history.php" class="dashboard-card__link">See details <i
                                        class="fa-solid fa-arrow-right text-xs"></i></a>
                            </div>
                        </div>

                        <!-- Recent Appointments -->
                        <aside
                            class="dashboard-recent-activity col-span-1 sm:col-span-2 md:col-span-2 order-4 dashboard-card--white">
                            <h4 class="dashboard-heading dashboard-heading__title">Recent Appointments</h4>
                            <ul class="dashboard-recent-activity__list">
                                <?php
                                if ($recentAppointments && mysqli_num_rows($recentAppointments) > 0) {
                                    while ($appointment = mysqli_fetch_assoc($recentAppointments)) {
                                        $apptDateTime = date('Y-m-d h:i A', strtotime($appointment['appointmentDate'] . ' ' . $appointment['appointmentTime']));
                                        echo '<li class="dashboard-recent-activity__item">
                                    <span>Dr. ' . htmlentities($appointment['doctorName']) . ' with ' . htmlentities($appointment['patientName']) . '</span>
                                    <span class="dashboard-recent-activity__time">' . $apptDateTime . '</span>
                                  </li>';
                                    }
                                } else {
                                    echo '<li class="dashboard-recent-activity__item">No recent appointments</li>';
                                }
                                ?>
                            </ul>
                            <div class="dashboard-card__footer">
                                <a href="manage-appointments.php" class="dashboard-card__link">See more <i
                                        class="fa-solid fa-arrow-right text-xs"></i></a>
                            </div>
                        </aside>

                        <!-- Health Overview Chart -->
                        <div
                            class="dashboard-overview col-span-1 sm:col-span-2 md:col-span-2 lg:!col-span-3 order-5 dashboard-card--white">
                            <h3 class="dashboard-heading dashboard-heading__title">Hospital Overview</h3>
                            <p class="dashboard-heading__subtitle">Visual overview of patient and appointment trends.
                            </p>
                            <div class="dashboard-overview__chart">
                                <canvas id="healthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>


            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
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