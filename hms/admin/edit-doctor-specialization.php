<?php
session_start();
error_reporting(E_ALL);
include('include/config.php');
include('include/checklogin.php');

// Current filename
$currentPage = basename($_SERVER['PHP_SELF']);

// Logged-in admin ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch admin data
$adminData = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $adminData = mysqli_fetch_assoc($res);
        $userName = $adminData['username'] ?? "User";
    }
}

// Validate ID
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    $_SESSION['flash_error'][] = "Invalid Specialization ID!";
    header("Location: manage-doctor-specialization.php");
    exit;
}

// Initialize input value
$inputValue = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $docspecialization = trim(mysqli_real_escape_string($con, $_POST['doctorspecilization']));
    $inputValue = $docspecialization; // keep value in input even on error

    if ($docspecialization === '') {
        $_SESSION['flash_error'][] = "Specialization cannot be empty!";
    } else {
        // Check duplicates
        $checkQuery = mysqli_query($con, "SELECT id FROM doctorSpecilization WHERE LOWER(specilization) = LOWER('$docspecialization') AND id != '$id'");
        if (mysqli_num_rows($checkQuery) > 0) {
            $_SESSION['flash_error'][] = "This specialization already exists!";
        } else {
            // Update DB
            $updateQuery = mysqli_query($con, "UPDATE doctorSpecilization SET specilization='$docspecialization' WHERE id='$id'");
            if ($updateQuery) {
                $_SESSION['flash_success'][] = "Doctor Specialization updated successfully!";
                // fetch updated value from DB
                $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM doctorSpecilization WHERE id='$id'"));
                $inputValue = $row['specilization'];
            } else {
                $_SESSION['flash_error'][] = "Something went wrong. Please try again!";
            }
        }
    }
}

// Fetch current specialization if not already fetched
if (!isset($row)) {
    $sql = mysqli_query($con, "SELECT * FROM doctorSpecilization WHERE id='$id'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $inputValue = $row['specilization'];
    } else {
        $_SESSION['flash_error'][] = "Specialization not found!";
        header("Location: manage-doctor-specialization.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Edit Specialization | HeavenKare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
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
                        <h1>Update Doctor Specialization</h1>
                        <p>Update the doctor specialization in the system.</p>
                    </header>


                    <form method="post" class="adminui-form-accent">

                        <!-- Flash messages outside form -->
                        <?php include('include/flash-error.php'); ?>
                        <?php include('include/flash-success.php'); ?>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="doctorspecilization" placeholder=" " required
                                    value="<?php echo htmlspecialchars($inputValue, ENT_QUOTES, 'UTF-8'); ?>">

                                <label class="adminui-label">Edit Doctor Specialization</label>
                            </div>
                        </div>

                        <div class="adminui-submit">
                            <button type="submit" name="submit" class="adminui-form__button">
                                Update Specialization
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
</body>

</html>