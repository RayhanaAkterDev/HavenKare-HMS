<?php error_reporting(0); ?>
<header class="bg-white shadow px-6 py-4 sticky top-0 z-50">
    <!-- Left side: Sidebar toggler + Brand -->
    <div class="flex items-center space-x-4">
        <!-- Mobile sidebar toggle -->
        <button id="mobile-sidebar-toggler" class="lg:hidden p-2 rounded hover:bg-gray-200">
            <i class="ti-align-justify text-xl"></i>
        </button>

        <!-- Brand -->
        <a href="#" class="text-2xl font-bold">
            HMS
        </a>
    </div>

    <!-- Center: Title -->
    <div class="hidden md:block">
        <h2 class="text-lg font-semibold">Hospital Management System 2</h2>
    </div>

    <!-- Right side: User Dropdown -->
    <div class="relative">
        <button id="user-menu-button" class="flex items-center gap-2 focus:outline-none">
            <img src="../../assets/images/patient.jpg" alt="User" class=" rounded-full object-cover">
            <span class="username font-medium">
                <?php
                $query = mysqli_query($con, "SELECT fullName FROM users WHERE id='" . $_SESSION['id'] . "'");
                while ($row = mysqli_fetch_array($query)) {
                    echo $row['fullName'];
                }
                ?>
                <i class="ti-angle-down"></i>
            </span>
        </button>

        <!-- Dropdown Menu -->
        <ul id="user-dropdown"
            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg text-sm">
            <li>
                <a href="edit-profile.php" class="block px-4 py-2 hover:bg-gray-100">My Profile</a>
            </li>
            <li>
                <a href="change-password.php" class="block px-4 py-2 hover:bg-gray-100">Change Password</a>
            </li>
            <li>
                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Log Out</a>
            </li>
        </ul>
    </div>
</header>

<script>
// Toggle user dropdown
const userButton = document.getElementById('user-menu-button');
const userDropdown = document.getElementById('user-dropdown');

userButton.addEventListener('click', () => {
    userDropdown.classList.toggle('hidden');
});

// Optional: close dropdown on outside click
window.addEventListener('click', (e) => {
    if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
        userDropdown.classList.add('hidden');
    }
});

// Optional: mobile sidebar toggle (if you implement a sidebar)
const mobileToggler = document.getElementById('mobile-sidebar-toggler');
mobileToggler?.addEventListener('click', () => {
    document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
});
</script>