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


// Logged-in admin ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full admin data
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['username'] ?? "User";
    }
}

// Helper function to format creation date
function formatCreationDate($datetime)
{
    if (!$datetime) return '—';
    $dt = new DateTime($datetime);
    $now = new DateTime();
    $diff = $now->diff($dt)->days;
    return $diff > 2 ? $dt->format('Y-m-d') : $dt->format('Y-m-d H:i');
}

// Handle search
if (isset($_GET['search']) && $_GET['search'] != '') {
    $searchTerm = trim($_GET['search']);
    $searchType = $_GET['type'] ?? '';

    $termEscaped = mysqli_real_escape_string($con, $searchTerm);
    $results = [];
    $queries = [];

    // Doctor Name search
    if (!$searchType || $searchType == 'doctor') {
        $queries[] = "SELECT 'Doctor' AS role, id, doctorName AS name, docEmail AS email, creationDate, 'doctor' AS type
                      FROM doctors
                      WHERE doctorName LIKE '%$termEscaped%'";
    }

    // Doctor Specialization search
    if ($searchType == 'doctor_spec') {
        $queries[] = "SELECT 'Doctor (Specialization)' AS role, id, specilization AS name, docEmail AS email, creationDate, 'doctor' AS type
                      FROM doctors
                      WHERE specilization LIKE '%$termEscaped%'";
    }

    // User search
    if (!$searchType || $searchType == 'user') {
        $queries[] = "SELECT 'User' AS role, id, fullName AS name, email, regDate AS creationDate, 'user' AS type
                      FROM users
                      WHERE fullName LIKE '%$termEscaped%' OR email LIKE '%$termEscaped%'";
    }

    // Patient search
    if (!$searchType || $searchType == 'patient') {
        $queries[] = "SELECT 'Patient' AS role, ID AS id, PatientName AS name, PatientEmail AS email, CreationDate AS creationDate, 'patient' AS type
                      FROM tblpatient
                      WHERE PatientName LIKE '%$termEscaped%' OR PatientEmail LIKE '%$termEscaped%'";
    }

    // Admin search
    if (!$searchType || $searchType == 'admin') {
        $queries[] = "SELECT 'Admin' AS role, id, username AS name, '' AS email, updationDate AS creationDate, 'admin' AS type
                      FROM admin
                      WHERE username LIKE '%$termEscaped%'";
    }

    if ($queries) {
        $query = implode(" UNION ALL ", $queries) . " ORDER BY role, name";
        $res = mysqli_query($con, $query);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $results[] = $row;
            }
        }
    }

    // Store results in session to show after reload
    $_SESSION['search_results'] = $results;
    $_SESSION['search_type'] = $searchType;
    $_SESSION['search_term'] = $searchTerm;

    header("Location: search.php");
    exit;
}

$results = $_SESSION['search_results'] ?? [];
$searchType = $_SESSION['search_type'] ?? '';
$searchTerm = $_SESSION['search_term'] ?? '';

unset($_SESSION['search_results'], $_SESSION['search_type'], $_SESSION['search_term']);
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Search Records | HeavenKare Admin</title>

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
        <?php include('./include/sidebar.php'); ?>
        <div id="overlay" class="dashboard-overlay"></div>
        <div id="main" class="dashboard-main">
            <?php include('include/header.php'); ?>

            <main id="main-content" class="dashboard-main-content">
                <div class="adminui-table__wrapper">
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
                        <h1>Search Records</h1>
                        <p>Search across all categories or select a specific type to filter results.</p>
                    </header>

                    <!-- Search Form -->
                    <form method="GET" class="mb-6 flex flex-col sm:flex-row sm:items-end sm:gap-4 gap-2">

                        <!-- Category Select -->
                        <div class="adminui-form-row w-full sm:w-2/6">
                            <div class="adminui-input-group">
                                <select name="type">
                                    <option value="">All Categories</option>
                                    <option value="user" <?= $searchType == 'user' ? 'selected' : '' ?>>Registered User
                                    </option>
                                    <option value="admin" <?= $searchType == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="doctor" <?= $searchType == 'doctor' ? 'selected' : '' ?>>Doctor (By
                                        Name)</option>
                                    <option value="doctor_spec" <?= $searchType == 'doctor_spec' ? 'selected' : '' ?>>
                                        Doctor (By Specialization)</option>
                                    <option value="patient" <?= $searchType == 'patient' ? 'selected' : '' ?>>Patient
                                    </option>
                                </select>
                                <label class="adminui-label">Category</label>
                            </div>
                        </div>

                        <!-- Keyword Input -->
                        <div class="adminui-form-row w-full sm:w-3/6">
                            <div class="adminui-input-group">
                                <input type="text" name="search" placeholder=" "
                                    value="<?= htmlspecialchars($searchTerm) ?>" required>
                                <label class="adminui-label">Enter keyword...</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="adminui-submit w-full sm:w-1/6">
                            <button type="submit">Search</button>
                        </div>
                    </form>

                    <?php if ($searchTerm): ?>
                    <h2 class="text-base font-medium mb-2 text-text-main text-center">
                        Results for "<?= htmlspecialchars($searchTerm) ?>" (<?= count($results) ?>)
                    </h2>

                    <?php if (count($results) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="adminui-table w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th>#</th>
                                    <th>Name / Specialization</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlentities($row['name']) ?></td>
                                    <td><?= htmlentities($row['email'] ?: '—') ?></td>
                                    <td><?= htmlentities($row['role']) ?></td>
                                    <td><?= formatCreationDate($row['creationDate']) ?></td>
                                    <td>
                                        <a href="view-search-details.php?type=<?= strtolower(htmlspecialchars($row['role'])) ?>&id=<?= intval($row['id']) ?>"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            View Details
                                        </a>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-red-600 font-medium mt-2 text-center">No results found for
                        "<?= htmlspecialchars($searchTerm) ?>"</p>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>
    <script src="../../dist/main.js"></script>
</body>

</html>