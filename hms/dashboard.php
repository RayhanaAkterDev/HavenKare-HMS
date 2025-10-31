<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();
?>

<?php
$currentPage = basename($_SERVER['PHP_SELF']); // gets current filename
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet" />
</head>

<body>
    <div class="bg-base-200 flex min-h-screen flex-col">
        <!-- ---------- HEADER ---------- -->
        <?php include('include/header.php'); ?>
        <!-- ---------- END HEADER ---------- -->

        <!-- ---------- SIDEBAR ---------- -->
        <?php include('include/sidebar.php'); ?>
        <!-- ---------- END SIDEBAR ---------- -->

        <div class="flex grow flex-col lg:ps-75">
            <!-- ---------- MAIN CONTENT ---------- -->
            <main class="mx-auto w-full max-w-7xl flex-1 p-6">
                <!-- ---------- LOGIN SUCCESS MESSAGE ---------- -->
                <?php if (!empty($_SESSION['success_msg'])): ?>
                <div id="loginSuccessMsg"
                    class="mb-6 bg-green-100 border border-green-300 text-green-800 p-4 rounded-lg shadow-md flex items-center gap-2 transition-opacity duration-700">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?php echo $_SESSION['success_msg']; ?></span>
                </div>
                <?php $_SESSION['success_msg'] = "";
                endif; ?>

                <!-- ---------- DASHBOARD CONTENT ---------- -->
                <div class="grid grid-cols-1 gap-6">
                    <div class="card w-full p-6 ">
                        <!-- Patient Dashboard -->
                        <div class="space-y-8">

                            <!-- Welcome Section -->
                            <div
                                class="rounded-xl bg-gradient-to-r from-purple-600 to-purple-700 text-white p-8 flex flex-col sm:flex-row items-center justify-between shadow-lg">
                                <div>
                                    <h2 class="text-2xl font-semibold">Welcome Back,
                                        <span class="font-bold">
                                            <?php
                                            $query = mysqli_query($con, "SELECT fullName FROM users WHERE id='" . $_SESSION['id'] . "'");
                                            $row = mysqli_fetch_array($query);
                                            echo $row['fullName'];
                                            ?>
                                        </span>
                                        👋
                                    </h2>
                                    <p class="text-indigo-100 mt-2">Here’s a quick overview of your health
                                        dashboard.</p>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <a href="book-appointment.php"
                                        class="btn bg-white text-indigo-700 font-medium hover:bg-indigo-50 transition-all">
                                        <i class="fa-solid fa-calendar-plus me-2"></i> Book Appointment
                                    </a>
                                </div>
                            </div>

                            <!-- Stats Overview -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="card border-t-4 border-indigo-500 bg-white shadow-sm p-5 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-sm text-gray-500">Upcoming Appointments</h3>
                                            <p class="text-2xl font-semibold text-gray-800 mt-1">3</p>
                                        </div>
                                        <i class="fa-solid fa-stethoscope text-indigo-600 text-2xl"></i>
                                    </div>
                                </div>

                                <div class="card border-t-4 border-green-500 bg-white shadow-sm p-5 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-sm text-gray-500">Total Visits</h3>
                                            <p class="text-2xl font-semibold text-gray-800 mt-1">12</p>
                                        </div>
                                        <i class="fa-solid fa-hospital-user text-green-600 text-2xl"></i>
                                    </div>
                                </div>

                                <div class="card border-t-4 border-yellow-500 bg-white shadow-sm p-5 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-sm text-gray-500">Pending Reports</h3>
                                            <p class="text-2xl font-semibold text-gray-800 mt-1">2</p>
                                        </div>
                                        <i class="fa-solid fa-file-medical text-yellow-500 text-2xl"></i>
                                    </div>
                                </div>

                                <div class="card border-t-4 border-pink-500 bg-white shadow-sm p-5 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-sm text-gray-500">Prescriptions</h3>
                                            <p class="text-2xl font-semibold text-gray-800 mt-1">8</p>
                                        </div>
                                        <i class="fa-solid fa-pills text-pink-500 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Appointments -->
                            <div class="card bg-white shadow-sm p-6 rounded-xl">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h3>
                                    <a href="appointment-history.php"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View
                                        All</a>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="table w-full border">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                                <th>Date</th>
                                                <th>Doctor</th>
                                                <th>Specialization</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5 Nov 2025</td>
                                                <td>Dr. Ayesha Rahman</td>
                                                <td>Cardiology</td>
                                                <td><span class="badge badge-success">Confirmed</span></td>
                                            </tr>
                                            <tr>
                                                <td>12 Nov 2025</td>
                                                <td>Dr. Tahmid Hasan</td>
                                                <td>Neurology</td>
                                                <td><span class="badge badge-warning">Pending</span></td>
                                            </tr>
                                            <tr>
                                                <td>22 Nov 2025</td>
                                                <td>Dr. Nabila Karim</td>
                                                <td>Dermatology</td>
                                                <td><span class="badge badge-info">Upcoming</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Quick Access -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <a href="appointment-history.php"
                                    class="flex items-center justify-center gap-3 p-5 bg-gradient-to-r from-indigo-100 to-indigo-50 border border-indigo-200 rounded-xl shadow hover:shadow-md transition">
                                    <i class="fa-solid fa-calendar-check text-indigo-600 text-2xl"></i>
                                    <span class="font-medium text-gray-700">View Appointment History</span>
                                </a>

                                <a href="view-prescriptions.php"
                                    class="flex items-center justify-center gap-3 p-5 bg-gradient-to-r from-green-100 to-green-50 border border-green-200 rounded-xl shadow hover:shadow-md transition">
                                    <i class="fa-solid fa-prescription-bottle-medical text-green-600 text-2xl"></i>
                                    <span class="font-medium text-gray-700">My Prescriptions</span>
                                </a>

                                <a href="medical-reports.php"
                                    class="flex items-center justify-center gap-3 p-5 bg-gradient-to-r from-yellow-100 to-yellow-50 border border-yellow-200 rounded-xl shadow hover:shadow-md transition">
                                    <i class="fa-solid fa-notes-medical text-yellow-600 text-2xl"></i>
                                    <span class="font-medium text-gray-700">My Reports</span>
                                </a>
                            </div>

                        </div>
                        <!-- End Dashboard -->

                    </div>
                </div>
            </main>
            <!-- ---------- END MAIN CONTENT ---------- -->

            <!-- ---------- FOOTER CONTENT ---------- -->
            <?php include('include/footer.php'); ?>
            <!-- ---------- END FOOTER CONTENT ---------- -->
        </div>
    </div>

    <script src="../node_modules/flyonui/flyonui.js"></script>

    <!-- Fade out login success message -->
    <script>
    const successMsg = document.getElementById('loginSuccessMsg');
    if (successMsg) {
        setTimeout(() => {
            successMsg.classList.add('opacity-0');
            setTimeout(() => successMsg.remove(), 700);
        }, 500);
        c: \xampp\ htdocs\ Hospital - Management - System - PHP\ hospital\ hms\ get_doctor.php
    }
    </script>

</body>

</html>