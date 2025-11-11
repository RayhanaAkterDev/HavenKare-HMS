<?php
// Default values
$initial = 'U';
$username = 'User';

// Make sure session and DB connection exist
if (isset($_SESSION['id']) && isset($con)) {
    $uid = intval($_SESSION['id']);

    // Try admin table
    $query = mysqli_query($con, "SELECT username FROM admin WHERE id='$uid' LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $username = $row['username'] ?? 'User';
    } else {
        // Try doctor table
        $query = mysqli_query($con, "SELECT doctorName AS username FROM doctors WHERE id='$uid' LIMIT 1");
        if ($query && mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            $username = $row['username'] ?? 'User';
        } else {
            // Try patient table
            $query = mysqli_query($con, "SELECT fullName AS username FROM users WHERE id='$uid' LIMIT 1");
            if ($query && mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);
                $username = $row['username'] ?? 'User';
            }
        }
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

            <!-- HEADER -->
            <div class="dashboard-sidebar__header">
                <img src="./images/logo.png" alt="HK" class="w-24 h-16 -mx-4 lg:hidden inline-block">
                <span class="dashboard-sidebar__logo-text">
                    Admin
                    <span class="text-sky-500">Dashbaord</span>
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
                        <div class="dashboard-header__profile-name">
                            <?= htmlspecialchars($username, ENT_QUOTES); ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-header__profile-menu">
                    <!-- Edit profile -->
                    <a href="edit-profile.php">
                        <span class="icon-[tabler--user-edit] mr-1"></span>
                        Edit Profile
                    </a>

                    <!-- Edit password -->
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