<aside id="layout-toggle"
    class="overlay overlay-open:translate-x-0 drawer drawer-start inset-y-0 start-0 hidden h-full [--auto-close:lg] sm:w-75 lg:block lg:translate-x-0 lg:shadow-none"
    aria-label="Sidebar" tabindex="-1">
    <div class="drawer-body border-base-content/20 h-full border-e p-0">
        <div class="flex h-full max-h-full flex-col">
            <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden"
                aria-label="Close" data-overlay="#layout-toggle">
                <span class="icon-[tabler--x] size-5"></span>
            </button>
            <div class="text-base-content border-base-content/20 flex flex-col items-center gap-4 border-b px-4 py-6">
                <div class="avatar">
                    <div class="size-17 rounded-full">
                        <img class="object-top" src="assets/backend-images/user.jpg" alt="user-img" />
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-base-content text-lg font-semibold">
                        <?php
                        $query = mysqli_query($con, "SELECT username FROM admin WHERE id='" . $_SESSION['id'] . "'");
                        $row = mysqli_fetch_array($query);
                        echo $row['username'];
                        ?>
                    </h3>
                    <!-- <p class="text-base-content/80">
                        <?php
                        $query = mysqli_query($con, "SELECT email FROM users WHERE id='" . $_SESSION['id'] . "'");
                        $row = mysqli_fetch_array($query);
                        echo $row['email'];
                        ?>
                    </p> -->
                </div>
            </div>
            <div class="h-full overflow-y-auto">
                <ul class="menu menu-sm gap-1 px-4">
                    <li class="mt-2.5">
                        <a href="dashboard.php"
                            class="px-2 <?= $currentPage == 'dashboard.php' ? 'menu-active' : '' ?>">
                            <span class="icon-[tabler--dashboard] size-4.5"></span>
                            <span class="grow">Dashboard</span>
                        </a>
                    </li>
                    <li class="text-base-content/50 mt-2.5 p-2 text-xs uppercase">Pages</li>
                    <li>
                        <a href="book-appointment.php"
                            class="px-2 <?= $currentPage == 'book-appointment.php' ? 'menu-active' : '' ?>">
                            <span class="icon-[tabler--file-invoice] size-4.5"></span>
                            Book Appointment
                        </a>
                    </li>
                    <li>
                        <a href="appointment-history.php"
                            class="px-2 <?= $currentPage == 'appointment-history.php' ? 'menu-active' : '' ?>">
                            <span class="icon-[tabler--users] size-4.5"></span>
                            Appointment History
                        </a>
                    </li>
                    <li>
                        <a href="view-medical-history.php"
                            class="px-2 <?= $currentPage == 'view-medical-history.php' ? 'menu-active' : '' ?>">
                            <span class="icon-[tabler--chart-pie-2] size-4.5"></span>
                            Medical History
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mt-auto flex items-center p-4">
                <img src="../../../assets/images/logo.png" class="w-16" alt="">
                <div>
                    <span class="text-base-content block text-xl font-bold">HeavenKare HSM</span>
                </div>
            </div>
        </div>
    </div>
</aside>