<aside id="sidebar" class="dashboard-sidebar lg:translate-x-0 -translate-x-full">

    <?php if ($data): ?>
    <div class="flex flex-col items-center py-8 px-4 w-full border-b border-[#2A2A40]">

        <!-- Header -->
        <span class="inline-block px-3.5 py-0.5 rounded-full text-sm font-semibold text-white 
	bg-gradient-to-r from-[#ff4e6a] via-[#ff6e7f] to-[#ff9a9e] 
	shadow-[0_1px_2px_rgba(255,110,127,0.4)] tracking-wide mb-2">
            Patient
        </span>

        <!-- Name -->
        <h4 class="text-white text-base font-semibold truncate w-full text-center py-1">
            <?= htmlentities($data['fullName']); ?>
        </h4>

        <!-- Registration info -->
        <div class="mt-2 text-xs text-gray-400 text-center leading-tight">
            <p><span class="font-medium text-[#ff6e7f]">Reg:</span> <?= htmlentities($data['regDate']); ?></p>
            <?php if ($data['updationDate']): ?>

            <p><span class="font-medium text-[#ff3d6d]">Last:</span> <?= htmlentities($data['updationDate']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Divider -->
        <div class="my-4 pt-2 border-t border-[#2A2A40] w-full"></div>

        <!-- Key Info -->
        <div class="flex flex-col space-y-2 w-full text-xs text-gray-200">
            <div class="flex justify-between">
                <span class="text-[#ff6e7f] font-medium">Gender</span>
                <span class="truncate"><?= htmlentities($data['gender']); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-[#ff6e7f] font-medium">Address</span>
                <span class="truncate text-right w-1/2"><?= htmlentities($data['address']); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-[#ff6e7f] font-medium">City</span>
                <span class="truncate"><?= htmlentities($data['city']); ?></span>
            </div>
        </div>

    </div>
    <?php else: ?>
    <p class="text-gray-400 text-xs text-center py-4">Profile data not found.</p>
    <?php endif; ?>

    <!-- NAV -->
    <div class="dashboard-sidebar__nav sidebar-scroll">
        <nav class="dashboard-sidebar__nav-inner">

            <!-- Management Title (Muted, truly muted) -->
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

                <!-- Appointments Parent -->
                <li>
                    <button class="dashboard-sidebar__nav-item dashboard-sidebar__nav-item--has-sub" data-has-sub>
                        <i class="fa-solid fa-calendar-check dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Appointments</span>
                        <i class="fa-solid fa-chevron-down dashboard-sidebar__nav-dropdown-icon"></i>
                    </button>

                    <ul class="dashboard-sidebar__sub-menu submenu-transition">
                        <li>
                            <a href="book-appointment.php" class="dashboard-sidebar__sub-item">
                                <i class="fa-solid fa-plus-circle mr-2"></i>
                                Book Appointment
                            </a>
                        </li>
                        <li>
                            <a href="appointment-history.php" class="dashboard-sidebar__sub-item">
                                <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                                Appointment History
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Medical History -->
                <li>
                    <a href="medical-history.php" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-notes-medical dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Medical History</span>
                    </a>
                </li>

            </ul>

            <!-- Divider -->
            <div class="mt-5 mb-6 border-t border-[#2A2A40] w-full"></div>

            <!-- Accounts & Settings Title (Muted, truly muted) -->

            <div class="p-4 text-gray-700 text-xs uppercase font-semibold tracking-wider text-shadow-md/30 text-center">
                Settings
            </div>


            <ul class="dashboard-sidebar__nav-list">
                <!-- Accounts & Settings Parent -->
                <li>
                    <button class="dashboard-sidebar__nav-item dashboard-sidebar__nav-item--has-sub" data-has-sub>
                        <i class="fa-solid fa-user-gear dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Accounts</span>
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

                    <!-- Extra Option -->
                <li>
                    <a href="#" class="dashboard-sidebar__nav-item">
                        <i class="fa-solid fa-prescription-bottle-medical dashboard-sidebar__nav-icon"></i>
                        <span class="dashboard-sidebar__nav-text">Settings</span>
                    </a>
                </li>
                </li>
            </ul>
        </nav>
    </div>


    <!-- SIDEBAR FOOTER -->
    <div class="dashboard-sidebar__footer">
        <div class="dashboard-sidebar__logo-icon">
            <img src="./images/logo.png" alt="HK">
        </div>
        <span class="dashboard-sidebar__logo-text">Heaven<span class="text-sky-500">Kare</span></span>
    </div>
</aside>