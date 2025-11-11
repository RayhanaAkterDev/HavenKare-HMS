<?php
// Ensure session started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default values
$initial = 'D';
$username = 'Doctor';
$docEmail = 'doctor@example.com'; // default

// Make sure doctor session exists
if (isset($_SESSION['did']) && isset($con)) {
    $doctorId = intval($_SESSION['did']);

    // Fetch doctor info (name + email)
    $query = mysqli_query($con, "SELECT doctorName, docEmail FROM doctors WHERE id='$doctorId' LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $username = $row['doctorName'] ?? 'Doctor';
        $docEmail = $row['docEmail'] ?? 'doctor@example.com';
    }

    // First letter for avatar
    $initial = strtoupper(substr($username, 0, 1));
}
?>

<header class="dashboard-header">
    <div class="dashboard-header__container">

        <div class="flex justify-center">
            <button id="mobileToggle" class="dashboard-header__mobile-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- HEADER LOGO -->
            <div class="dashboard-sidebar__header">
                <img src="./images/logo.png" alt="HK" class="w-24 h-16 -mx-4 lg:hidden inline-block">
                <span class="dashboard-sidebar__logo-text">
                    Doctor <span class="text-sky-500">Dashboard</span>
                </span>
            </div>
        </div>

        <div class="dashboard-header__right">
            <button class="dashboard-header__notification">
                <i class="fa-solid fa-bell"></i>
                <span class="dashboard-header__notification-badge"></span>
            </button>

            <div class="dashboard-header__profile profile-dropdown" id="profileDropdown">
                <div class="dashboard-header__profile-info">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#ff6e7f] to-[#ff3d6d] flex items-center justify-center text-white text-xl font-bold">
                        <?= htmlspecialchars($initial, ENT_QUOTES); ?>
                    </div>
                    <div class="dashboard-header__profile-text">
                        <div class="dashboard-header__profile-name">Dr.
                            <?= htmlspecialchars($username, ENT_QUOTES); ?>
                        </div>
                        <div class="dashboard-header__profile-email text-xs text-gray-400">
                            <?= htmlspecialchars($docEmail, ENT_QUOTES); ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-header__profile-menu">
                    <a href="edit-profile.php">
                        <span class="icon-[tabler--user-edit] mr-1"></span>
                        Edit Profile
                    </a>
                    <a href="change-password.php">
                        <span class="icon-[tabler--lock-check] mr-1"></span>
                        Change Password
                    </a>
                    <a href="logout.php" id="logoutHeader">
                        <span class="icon-[tabler--logout] mr-1"></span> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>