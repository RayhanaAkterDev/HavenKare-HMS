<div class="bg-base-100 sticky top-0 z-50 flex lg:ps-75">
    <div class="mx-auto w-full max-w-7xl">
        <nav class="navbar flex items-center h-16">
            <!-- left -->
            <div class="flex items-center">
                <button type="button" class="btn btn-soft btn-square btn-sm me-2 lg:hidden" aria-haspopup="dialog"
                    aria-expanded="false" aria-controls="layout-toggle" data-overlay="#layout-toggle">
                    <span class="icon-[tabler--menu-2] size-4.5"></span>
                </button>

                <!-- breadcrumbs -->
                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li>
                            <?php
                            // Get the current file name
                            $currentPage = basename($_SERVER['PHP_SELF']);

                            // Optional: format it nicely (remove extension, replace hyphens with spaces, capitalize words)
                            $formattedPage = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $currentPage));

                            echo $formattedPage;
                            ?>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- right (use ml-auto so it doesn't grow and push layout) -->
            <div class="ml-auto flex items-center gap-3 mr-2">
                <!-- PROFILE DROPDOWN -->
                <div class="dropdown relative inline-flex [--offset:21]">
                    <button id="profile-dropdown" type="button" class="dropdown-toggle avatar" aria-haspopup="menu"
                        aria-expanded="false" aria-label="Dropdown">
                        <span class="rounded-field size-9.5">
                            <img src="../../../assets/backend-images/user.jpg" alt="user" class="object-top" />
                        </span>
                    </button>

                    <ul class="dropdown-menu absolute end-0 mt-2 z-50 dropdown-open:opacity-100 hidden w-56 max-w-75 space-y-0.5"
                        role="menu" aria-orientation="vertical" aria-labelledby="profile-dropdown">
                        <li class="dropdown-header mb-1 gap-4 px-5 pt-4.5 pb-3.5 flex items-center">
                            <div class="avatar avatar-online-top">
                                <div class="w-10 rounded-full border border-red-600">
                                    <img src="../assets/backend-images/user.jpg" alt="user" class="object-top" />
                                </div>
                            </div>
                            <div>
                                <h6 class="text-base-content mb-0.5 font-semibold">
                                    <?php
                                    // safe check to avoid PHP errors if session id isn't set
                                    if (isset($_SESSION['id']) && isset($con)) {
                                        $query = mysqli_query($con, "SELECT username FROM admin WHERE id='" . intval($_SESSION['id']) . "'");
                                        if ($query) {
                                            $row = mysqli_fetch_array($query);
                                            echo htmlspecialchars($row['username'] ?? 'User', ENT_QUOTES);
                                        } else {
                                            echo 'User';
                                        }
                                    } else {
                                        echo 'User';
                                    }
                                    ?>
                                </h6>
                                <p class="text-base-content/80 font-medium">Patient</p>
                            </div>
                        </li>

                        <li><a class="dropdown-item px-3" href="view-profile.php"><span
                                    class="icon-[tabler--user] size-5"></span> My
                                account</a></li>
                        <li><a class="dropdown-item px-3" href="#"><span class="icon-[tabler--settings] size-5"></span>
                                Setting</a></li>
                        <li><a class="dropdown-item px-3" href="#"><span
                                    class="icon-[tabler--credit-card] size-5"></span> Billing</a></li>
                        <li>
                            <hr class="border-base-content/20 -mx-2 my-1" />
                        </li>

                        <!-- Edit profile -->
                        <li><a class="dropdown-item px-3" href="edit-profile.php"><span
                                    class="icon-[tabler--user-edit]"></span>
                                Edit Profile</a></li>

                        <!-- Edit password -->
                        <li class="mb-1"><a class="dropdown-item px-3" href="change-password.php"><span
                                    class="icon-[tabler--lock-check]"></span> Change Password</a></li>

                        <li class="dropdown-footer p-2 pt-1">
                            <a class="btn btn-text btn-error btn-block h-11 justify-start px-3 font-normal"
                                href="logout.php">
                                <span class="icon-[tabler--logout] size-5"></span> Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END PROFILE -->
            </div>
        </nav>
    </div>
</div>