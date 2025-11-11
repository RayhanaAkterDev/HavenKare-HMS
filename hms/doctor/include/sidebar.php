<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safety check: Ensure doctor info exists
if (!isset($_SESSION['dlogin']) || !isset($_SESSION['did'])) {
    header("Location: ../index.php");
    exit();
}

$doctorName = $_SESSION['dlogin'] ?? "Doctor";
$specialization = "General";

// Fetch specialization from DB using doctorId
if (isset($con)) { // Ensure $con is available from dashboard
    $doctorId = intval($_SESSION['did']);
    $stmt = $con->prepare("SELECT specilization FROM doctors WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $specialization = $row['specilization'] ?? "General";
    }
    $stmt->close();
}
?>


<aside id="sidebar" class="dashboard-sidebar lg:translate-x-0 -translate-x-full">

    <div class="flex flex-col items-center py-8 px-4 w-full border-b border-[#2A2A40]">
        <!-- Header -->
        <span class="inline-block px-3.5 py-0.5 rounded-full text-sm font-semibold text-white 
            bg-gradient-to-r from-[#ff4e6a] via-[#ff6e7f] to-[#ff9a9e] 
            shadow-[0_1px_2px_rgba(255,110,127,0.4)] tracking-wide mb-2">
            Doctor
        </span>

        <h4 class="text-white text-base font-semibold truncate w-full text-center py-1">
            Dr. <?= htmlspecialchars($doctorName) ?> <br>
            <small class="text-gray-300"><?= htmlspecialchars($specialization) ?></small>
        </h4>


    </div>

    <!-- NAV -->
    <div class="dashboard-sidebar__nav sidebar-scroll">
        <nav class="dashboard-sidebar__nav-inner">

            <!-- MANAGEMENT SECTION -->
            <div class="p-4 text-gray-700 text-xs uppercase font-semibold tracking-wider text-shadow-md/30 text-center">
                Management
            </div>

            <ul class="dashboard-sidebar__nav-list">
                <!-- Dashboard -->
                <li>
                    <a href="dashboard.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-chart-line dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Patients -->
                <li>
                    <button class="dashboard-sidebar__nav-item dashboard-sidebar__nav-item--has-sub" data-has-sub>
                        <i class="fa-solid fa-user-injured dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Patients</span>
                        <i class="fa-solid fa-chevron-down dashboard-sidebar__nav-dropdown-icon"></i>
                    </button>
                    <ul class="dashboard-sidebar__sub-menu submenu-transition">
                        <li><a href="add-patient.php" class="dashboard-sidebar__sub-item">Add Patient</a></li>
                        <li><a href="manage-patient.php" class="dashboard-sidebar__sub-item">Manage Patients</a></li>
                    </ul>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="appointment-history.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-calendar-check dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Appointment History</span>
                    </a>
                </li>

                <!-- search -->
                <li>
                    <a href="search.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-magnifying-glass dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Search</span>
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <div class="my-4 border-t border-[#2A2A40] w-full"></div>

            <!-- LOGS -->
            <!-- 
            <div class="p-4 text-gray-700 text-xs uppercase font-semibold tracking-wider text-shadow-md/30 text-center">
                Reports & Logs
            </div>


            <ul class="dashboard-sidebar__nav-list">

                <li>
                    <button class="dashboard-sidebar__nav-item dashboard-sidebar__nav-item--has-sub" data-has-sub>
                        <i class="fa-solid fa-file-alt dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Reports</span>
                        <i class="fa-solid fa-chevron-down dashboard-sidebar__nav-dropdown-icon"></i>
                    </button>
                    <ul class="dashboard-sidebar__sub-menu submenu-transition">
                        <li><a href="between-dates-reports.php" class="dashboard-sidebar__sub-item">B/w Dates
                                Reports</a></li>
                    </ul>
                </li>

                <li>
                    <a href="doctor-logs.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-list-check dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Doctor Session Logs</span>
                    </a>
                </li>
                <li>
                    <a href="user-logs.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-list-check dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">User Session Logs</span>
                    </a>
                </li>
            </ul>

            <div class="mt-5 mb-6 border-t border-[#2A2A40] w-full"></div> -->

            <!-- SETTINGS -->
            <div class="p-4 text-gray-700 text-xs uppercase font-semibold tracking-wider text-shadow-md/30 text-center">
                Accounts & Settings
            </div>

            <ul class="dashboard-sidebar__nav-list">
                <li>
                    <button class="dashboard-sidebar__nav-item dashboard-sidebar__nav-item--has-sub" data-has-sub>
                        <i class="fa-solid fa-user-gear dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Accounts & Settings</span>
                        <i class="fa-solid fa-chevron-down dashboard-sidebar__nav-dropdown-icon"></i>
                    </button>
                    <ul class="dashboard-sidebar__sub-menu submenu-transition">
                        <li>
                            <a href="edit-profile.php" class="dashboard-sidebar__nav-item">
                                <i class="fa-solid fa-user-pen dashboard-sidebar__nav-icon"></i>
                                <span class="dashboard-sidebar__nav-text">Update Profile</span>
                            </a>
                        </li>
                        <li>
                            <a id="logoutSidebar" href="logout.php" class="dashboard-sidebar__nav-item">
                                <i class="fa-solid fa-right-from-bracket dashboard-sidebar__nav-icon"></i>
                                <span class="dashboard-sidebar__nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </nav>
    </div>

    <!-- SIDEBAR FOOTER -->
    <div class="dashboard-sidebar__footer">
        <a href="dashboard.php" class="dashboard-sidebar__logo">
            <div class="dashboard-sidebar__logo-icon">
                <img src="./images/logo.png" alt="HK">
            </div>
            <span class="dashboard-sidebar__logo-text -ml-4">Heaven<span class="text-sky-500">Kare</span></span>
        </a>
    </div>
</aside>