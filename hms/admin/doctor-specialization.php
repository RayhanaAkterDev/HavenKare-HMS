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

// Logged-in patient ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full patient data for profile
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['username'] ?? "User";
    }
}

// Handle adding specialization
if (isset($_POST['submit'])) {
    $doctorspecilization = trim(mysqli_real_escape_string($con, $_POST['doctorspecilization']));

    // Check if specialization already exists (case-insensitive)
    $checkQuery = mysqli_query($con, "SELECT * FROM doctorSpecilization WHERE LOWER(specilization) = LOWER('$doctorspecilization') LIMIT 1");

    if (mysqli_num_rows($checkQuery) > 0) {
        // Use flash error
        $_SESSION['flash_error'] = ["This specialization already exists!"];
    } else {
        mysqli_query($con, "INSERT INTO doctorSpecilization(specilization) VALUES('$doctorspecilization')");
        // Use flash success
        $_SESSION['flash_success'] = ["Doctor Specialization added successfully!!"];
    }

    // Redirect back to the same page
    header("Location: doctor-specialization.php");
    exit;
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $sid = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM doctorSpecilization WHERE id = '$sid'");
    // Use flash success
    $_SESSION['flash_success'] = ["Specialization data deleted!!"];
    header("Location: doctor-specialization.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>


<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Doctor Specialization | HeavenKare Admin</title>
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
                <div class="adminui-table__wrapper">

                    <!-- Breadcrumb -->
                    <div class="dashboard-header__breadcrumb mb-4">
                        HeavenKare /
                        <?php
                        $currentPath = $_SERVER['PHP_SELF'];
                        $parts = explode('/', trim($currentPath, '/'));
                        $lastTwo = array_slice($parts, -2);
                        foreach ($lastTwo as &$part) {
                            $part = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $part));
                        }
                        echo implode(' / ', $lastTwo);
                        ?>
                    </div>

                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>Doctor Specializations</h1>
                        <p>Add or remove doctor specializations from the system.</p>
                    </header>

                    <!-- Display Success/Error Message -->
                    <?php include('./include/flash-success.php'); ?>
                    <?php include('./include/flash-error.php'); ?>

                    <!-- Section: Add Specialization form -->
                    <form method="post" class="adminui-form-accent">
                        <div class="adminui-input-group">
                            <input type="text" id="doctorspecilization" name="doctorspecilization" placeholder=" "
                                required>
                            <label class="adminui-label">Add New Doctor Specialization</label>
                        </div>

                        <div class="adminui-submit">
                            <button type="submit" name="submit" class="adminui-form__button"> Add Specialization
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="my-16 border-t border-[#2A2A40] w-full"></div>


                    <!-- view all specialization -->


                    <div class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>Manage Specializations</h1>
                        <p> Explore and manage all the doctor specializations in the system efficiently and
                            professionally.</p>
                    </div>

                    <?php include('./include/flash-success.php'); ?>

                    <table class="adminui-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Specialization</th>
                                <th class="adminui-table__hidden-md">Created</th>
                                <th class="adminui-table__hidden-lg">Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM doctorSpecilization");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($sql)) { ?>
                            <tr>
                                <td data-label="#"> <?php echo $cnt; ?> </td>
                                <td data-label="Specialization">
                                    <?php echo htmlentities($row['specilization']); ?>
                                </td>
                                <td data-label="Created" class="adminui-table__hidden-md">
                                    <?php echo htmlentities($row['creationDate']); ?> </td>
                                <td data-label="Updated" class="adminui-table__hidden-lg">
                                    <?php echo htmlentities($row['updationDate']); ?> </td>

                                <td data-label="Actions">
                                    <div class="adminui-table__actions">
                                        <a href="edit-doctor-specialization.php?id=<?php echo $row['id']; ?>"
                                            class="adminui-table__edit">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <a href="javascript:void(0);"
                                            onclick="openDeleteSpecModal('doctor-specialization.php?id=<?php echo $row['id'] ?>&del=delete')"
                                            class="adminui-table__delete">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <?php $cnt++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteSpecModal"
        class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center transition-opacity duration-300">
        <div id="deleteSpecModalContent"
            class="bg-white rounded-lg shadow-lg p-6 w-96 relative transform scale-90 opacity-0 transition-all duration-300">
            <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this specialization? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDeleteSpec"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                <a id="confirmDeleteSpec" href="#"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</a>
            </div>
            <span class="absolute top-2 right-2 cursor-pointer text-gray-500"
                onclick="closeDeleteSpecModal()">&times;</span>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
    <script>
    function openDeleteSpecModal(deleteUrl) {
        const modal = document.getElementById('deleteSpecModal');
        const modalContent = document.getElementById('deleteSpecModalContent');
        modal.classList.remove('hidden');

        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        document.getElementById('confirmDeleteSpec').setAttribute('href', deleteUrl);
    }

    function closeDeleteSpecModal() {
        const modal = document.getElementById('deleteSpecModal');
        const modalContent = document.getElementById('deleteSpecModalContent');

        // Animate out
        modalContent.classList.add('scale-90', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');

        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('cancelDeleteSpec').addEventListener('click', closeDeleteSpecModal);
    </script>

</body>

</html>