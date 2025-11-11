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
                    Patient
                    <span class="text-sky-500">Dashbaord</span>
                </span>
            </div>
        </div>

        <div class="dashboard-header__right"> <button class="dashboard-header__notification"> <i
                    class="fa-solid fa-bell"></i> <span class="dashboard-header__notification-badge"></span> </button>
            <div class="dashboard-header__profile profile-dropdown" id="profileDropdown">
                <div class="dashboard-header__profile-info">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#ff6e7f] to-[#ff3d6d] flex items-center justify-center text-white text-xl font-bold">
                        <?= isset($data['fullName']) ? strtoupper(substr($data['fullName'], 0, 1)) : 'U'; ?> </div>
                    <div class="dashboard-header__profile-text">
                        <div class="dashboard-header__profile-name">
                            <?= isset($data['fullName']) ? htmlspecialchars($data['fullName'], ENT_QUOTES) : 'User'; ?>
                        </div>
                        <div class="dashboard-header__profile-role">
                            <?= isset($data['email']) ? htmlspecialchars($data['email'], ENT_QUOTES) : 'Email'; ?>
                        </div>
                    </div>
                </div>
                <div class="dashboard-header__profile-menu"> <a href="edit-profile.php"> <span
                            class="icon-[tabler--user-edit] mr-1"></span> Edit Profile </a> <a
                        href="change-password.php"> <span class="icon-[tabler--lock-check] mr-1"></span> Change Password
                    </a> <a href="logout.php" id="logoutHeader"> <span class="icon-[tabler--logout] mr-1"></span> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>