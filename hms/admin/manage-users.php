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


// Logged-in user ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full user data for profile
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['username'] ?? "User";
    }
}

// If not logged in
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $deleteQuery = mysqli_query($con, "DELETE FROM users WHERE id='$id'");
    if ($deleteQuery && mysqli_affected_rows($con) > 0) {
        $_SESSION['flash_success'][] = "User deleted successfully!";
    } else {
        $_SESSION['flash_success'][] = "User not found or already deleted!";
    }
    header("Location: manage-users.php");
    exit;
}

// Fetch users table data
$users = [];
$resUsers = mysqli_query($con, "
    SELECT id, fullName AS name, email, regDate AS creationDate, updationDate
    FROM users
");
if (!$resUsers) {
    die("Users query failed: " . mysqli_error($con));
}
while ($row = mysqli_fetch_assoc($resUsers)) {
    $users[] = $row;
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage Users | HeavenKare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="../../src/output.css" rel="stylesheet" />
    <style>
    :root {
        --sidebar-w: 280px;
    }
    </style>
</head>

<body class="dashboard-body">
    <div id="app" class="dashboard-container">
        <?php include('./include/sidebar.php'); ?>
        <div id="overlay" class="dashboard-overlay"></div>
        <div id="main" class="dashboard-main">
            <?php include('include/header.php'); ?>
            <main id="main-content" class="dashboard-main-content">
                <div class="adminui-table__wrapper">

                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>Manage Users</h1>
                        <p>View, edit, and manage all registered users</p>
                    </header>

                    <!-- Delete success msg -->
                    <?php include('include/flash-success.php'); ?>

                    <table class="adminui-table" id="sample-table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Creation Date</th>
                                <th>Updation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($users)) {
                                echo '<tr><td colspan="6" class="text-center">No users found.</td></tr>';
                            } else {
                                $cnt = 1;
                                foreach ($users as $row) {
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt; ?>.</td>
                                <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td data-label="Creation Date"><?php echo $row['creationDate'] ?? '-'; ?></td>
                                <td data-label="Updation Date"><?php echo $row['updationDate'] ?? '-'; ?></td>
                                <td data-label="Actions">
                                    <div class="adminui-table__actions">
                                        <a href="view-user.php?id=<?php echo $row['id']; ?>"
                                            class="adminui-table__edit">
                                            <i class="fa-solid fa-eye"></i> See Details
                                        </a>
                                        <a href="javascript:void(0);"
                                            onclick="openDeleteModal('manage-users.php?id=<?php echo $row['id']; ?>&del=delete')"
                                            class="adminui-table__delete">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                    $cnt++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center transition-opacity duration-300">
        <div id="deleteModalContent"
            class="bg-white rounded-lg shadow-lg p-6 w-96 relative transform scale-90 opacity-0 transition-all duration-300">
            <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                <!-- Confirm delete link -->
                <a id="confirmDelete" href="#"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</a>
            </div>
            <span class="absolute top-2 right-2 cursor-pointer text-gray-500"
                onclick="closeDeleteModal()">&times;</span>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
    <script>
    function openDeleteModal(deleteUrl) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.getElementById('confirmDelete').setAttribute('href', deleteUrl);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        modalContent.classList.add('scale-90', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);

    // Force confirmDelete link to navigate
    document.getElementById('confirmDelete').addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        if (href && href !== '#') {
            window.location.href = href;
        }
    });
    </script>

</body>

</html>