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

// Check login
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

// Get doctor ID
$did = intval($_GET['id'] ?? 0);

// Fetch doctor data
$doctorData = null;
if ($did > 0) {
    $res = mysqli_query($con, "SELECT * FROM doctors WHERE id='$did' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $doctorData = mysqli_fetch_assoc($res);
    } else {
        $_SESSION['flash_error'][] = "Doctor not found.";
        header("Location: manage-doctors.php");
        exit;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $docspecialization = trim($_POST['Doctorspecialization']);
    $docname           = trim($_POST['docname']);
    $docaddress        = trim($_POST['clinicaddress']);
    $docfees           = trim($_POST['docfees']);
    $doccontactno      = trim($_POST['doccontact']);
    $docemail          = trim($_POST['docemail']); // readonly, safe

    // 1️⃣ Check fees first
    if (!is_numeric($docfees) || $docfees < 500) {
        $_SESSION['flash_error'][] = "Consultancy fees must be at least 500.";
        header("Location: edit-doctor.php?id=$did");
        exit;
    }

    // 2️⃣ Check contact number length (exact 11 digits)
    if (!preg_match('/^\d{11}$/', $doccontactno)) {
        $_SESSION['flash_error'][] = "Contact number must be exactly 11 digits.";
        header("Location: edit-doctor.php?id=$did");
        exit;
    }

    // 3️⃣ Check contact number uniqueness
    $contactCheck = mysqli_query($con, "SELECT id FROM doctors WHERE contactno='$doccontactno' AND id != '$did'");
    if ($contactCheck && mysqli_num_rows($contactCheck) > 0) {
        $_SESSION['flash_error'][] = "Contact number already exists. Please use a different number.";
        header("Location: edit-doctor.php?id=$did");
        exit;
    }

    // Escape values
    $docspecialization_safe = mysqli_real_escape_string($con, $docspecialization);
    $docname_safe           = mysqli_real_escape_string($con, $docname);
    $docaddress_safe        = mysqli_real_escape_string($con, $docaddress);
    $docfees_safe           = mysqli_real_escape_string($con, $docfees);
    $doccontactno_safe      = mysqli_real_escape_string($con, $doccontactno);

    // Update record
    $sql = mysqli_query($con, "UPDATE doctors SET 
        specilization='$docspecialization_safe',
        doctorName='$docname_safe',
        address='$docaddress_safe',
        docFees='$docfees_safe',
        contactno='$doccontactno_safe',
        updationDate=NOW()
        WHERE id='$did'
    ");

    if ($sql) {
        $_SESSION['flash_success'][] = "Doctor details updated successfully.";
        header("Location: edit-doctor.php?id=$did");
        exit;
    } else {
        $_SESSION['flash_error'][] = "Something went wrong. Please try again!";
        header("Location: edit-doctor.php?id=$did");
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Edit Doctor | HeavenKare Admin</title>
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

                    <!-- Page Header -->
                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>Edit Doctor Profile</h1>
                        <p>Update doctor details and specialization information.</p>
                    </header>

                    <!-- Edit Form -->
                    <form role="form" name="editdoc" method="post" class="adminui-form-accent">
                        <div class="adminui-doctor__profile-info mb-4">
                            <h4 class="adminui-doctor__name"><?php echo htmlentities($doctorData['doctorName']); ?>'s
                                Profile</h4>
                            <p><strong>Profile Reg. Date:</strong>
                                <?php echo date("d M Y | h:i:s a", strtotime($doctorData['creationDate'])); ?></p>
                            <?php if ($doctorData['updationDate']) { ?>
                            <p><strong>Profile Last Updated:</strong>
                                <?php echo date("d M Y | h:i:s a", strtotime($doctorData['updationDate'])); ?></p>
                            <?php } ?>
                        </div>


                        <!-- Flash Messages -->
                        <?php include('./include/flash-success.php'); ?>
                        <?php include('./include/flash-error.php'); ?>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <select name="Doctorspecialization" required>
                                    <option value="<?php echo htmlentities($doctorData['specilization']); ?>">
                                        <?php echo htmlentities($doctorData['specilization']); ?>
                                    </option>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM doctorspecilization");
                                    while ($row = mysqli_fetch_assoc($ret)) { ?>
                                    <option value="<?php echo htmlentities($row['specilization']); ?>">
                                        <?php echo htmlentities($row['specilization']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <label class="adminui-label">Doctor Specialization</label>
                            </div>

                            <div class="adminui-input-group">
                                <input type="text" name="docname"
                                    value="<?php echo htmlentities($doctorData['doctorName']); ?>" placeholder=" "
                                    required>
                                <label class="adminui-label">Doctor Name</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <textarea name="clinicaddress" placeholder=" "
                                    required><?php echo htmlentities($doctorData['address']); ?></textarea>
                                <label class="adminui-label">Clinic Address</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="number" name="docfees" required min="500"
                                    value="<?php echo htmlentities($doctorData['docFees']); ?>" placeholder=" ">
                                <label class="adminui-label">Consultancy Fees</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="doccontact" required
                                    value="<?php echo htmlentities($doctorData['contactno']); ?>" placeholder=" ">
                                <label class="adminui-label">Contact Number</label>
                            </div>
                            <div class="adminui-input-group">
                                <input type="email" name="docemail"
                                    value="<?php echo htmlentities($doctorData['docEmail']); ?>" readonly
                                    placeholder=" " class="cursor-not-allowed">
                                <label class="adminui-label">Email Address</label>
                            </div>
                        </div>

                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Update Doctor Profile</button>
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