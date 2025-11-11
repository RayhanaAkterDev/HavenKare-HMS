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
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['username'] ?? "User";
    }
}

// Load previous form data
$formData = $_SESSION['formData'] ?? [
    'docname' => '',
    'Doctorspecialization' => '',
    'doccontact' => '',
    'docemail' => '',
    'clinicaddress' => '',
    'docfees' => ''
];

// Clear session data
unset($_SESSION['formData']);

if (isset($_POST['submit'])) {
    $docspecialization = trim($_POST['Doctorspecialization']);
    $docname = trim($_POST['docname']);
    $docaddress = trim($_POST['clinicaddress']);
    $docfees = trim($_POST['docfees']);
    $doccontactno = trim($_POST['doccontact']);
    $docemail = trim($_POST['docemail']);
    $npass = $_POST['npass'];
    $cfpass = $_POST['cfpass'];

    // Save for repopulation
    $formData = [
        'docname' => $docname,
        'Doctorspecialization' => $docspecialization,
        'doccontact' => $doccontactno,
        'docemail' => $docemail,
        'clinicaddress' => $docaddress,
        'docfees' => $docfees
    ];

    // Sequential validation: first error only
    $error = '';

    if (empty($docspecialization)) {
        $error = "Please select a specialization.";
    } elseif (empty($docname) || empty($docaddress) || empty($docfees) || empty($doccontactno) || empty($docemail) || empty($npass) || empty($cfpass)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^\d{11}$/', $doccontactno)) {
        $error = "Contact number must be exactly 11 digits.";
    } elseif (!filter_var($docemail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!is_numeric($docfees) || $docfees < 500) {
        $error = "Doctor fees must be at least 500.";
    } elseif (strlen($npass) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $npass)) {
        $error = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[0-9]/', $npass)) {
        $error = "Password must contain at least one number.";
    } elseif (!preg_match('/[\W]/', $npass)) {
        $error = "Password must contain at least one special character.";
    } elseif ($npass !== $cfpass) {
        $error = "Password and Confirm Password do not match.";
    } else {
        // Email uniqueness
        $emailCheck = mysqli_query($con, "SELECT id FROM doctors WHERE docEmail='$docemail'");
        if (mysqli_num_rows($emailCheck) > 0) {
            $error = "Email already exists. Please use a different email.";
        }

        // Contact uniqueness
        $contactCheck = mysqli_query($con, "SELECT id FROM doctors WHERE contactno='$doccontactno'");
        if (mysqli_num_rows($contactCheck) > 0) {
            $error = "Contact number already exists. Please use a different number.";
        }

        // Enforce hospital domain
        $emailDomain = substr(strrchr($docemail, "@"), 1);
        $allowedDomain = "heavenkare.care";
        if ($emailDomain !== $allowedDomain) {
            $error = "Email must be a '$allowedDomain' address for hospital staff or doctors.";
        }
    }

    // If any error, store in flash_error and redirect
    if ($error != '') {
        $_SESSION['flash_error'][] = $error;
        $_SESSION['formData'] = $formData;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Escape inputs
    $docspecialization_safe = mysqli_real_escape_string($con, $docspecialization);
    $docname_safe           = mysqli_real_escape_string($con, $docname);
    $docaddress_safe        = mysqli_real_escape_string($con, $docaddress);
    $docfees_safe           = mysqli_real_escape_string($con, $docfees);
    $doccontactno_safe      = mysqli_real_escape_string($con, $doccontactno);
    $docemail_safe          = mysqli_real_escape_string($con, $docemail);
    $password_safe          = mysqli_real_escape_string($con, md5($npass));

    // Insert doctor
    $sql = mysqli_query($con, "INSERT INTO doctors(specilization, doctorName, address, docFees, contactno, docEmail, password) 
        VALUES('$docspecialization_safe', '$docname_safe', '$docaddress_safe', '$docfees_safe', '$doccontactno_safe', '$docemail_safe', '$password_safe')");

    if ($sql) {
        $_SESSION['flash_success'][] = "Doctor added successfully!";
        header("Location: add-doctor.php");
        exit;
    } else {
        $_SESSION['flash_error'][] = "Database error: Unable to add doctor.";
        $_SESSION['formData'] = $formData;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>


<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Doctor | HeavenKare Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <link href="../../src/output.css" rel="stylesheet">

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

            <!-- MAIN SCROLLABLE AREA -->
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
                        <h1>Add New Doctor</h1>
                        <p>Add a new doctor to the system with their details.</p>
                    </header>


                    <form role="form" name="adddoc" method="post" onsubmit="return valid();"
                        class="adminui-form-accent">
                        <p class="adminui-section__subtitle mb-1 underline !text-[#ff6e7f]">Add Doctor Informations:
                        </p>

                        <!-- Display Server-side Errors -->
                        <?php include('./include/flash-error.php'); ?>

                        <?php include('./include/flash-success.php'); ?>

                        <!-- Row 1 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="docname" placeholder=" " required
                                    value="<?php echo htmlentities($formData['docname']); ?>">
                                <label class="adminui-label">Doctor Name</label>
                            </div>

                            <div class="adminui-input-group">
                                <select name="Doctorspecialization" required>
                                    <option value="" disabled
                                        <?php echo empty($formData['Doctorspecialization']) ? 'selected' : ''; ?>>
                                        Select
                                        Specialization</option>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM doctorspecilization");
                                    while ($row = mysqli_fetch_array($ret)) {
                                        $selected = ($formData['Doctorspecialization'] == $row['specilization']) ? 'selected' : '';
                                        echo '<option value="' . htmlentities($row['specilization']) . '" ' . $selected . '>' . htmlentities($row['specilization']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <label class="adminui-label">Specialization</label>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="doccontact" placeholder=" " required
                                    value="<?php echo htmlentities($formData['doccontact']); ?>">
                                <label class="adminui-label">Contact Number</label>
                            </div>

                            <div class="adminui-input-group">
                                <input type="email" id="docemail" name="docemail" placeholder=" "
                                    onblur="checkemailAvailability()" required
                                    value="<?php echo htmlentities($formData['docemail']); ?>">
                                <label class="adminui-label">Email Address</label>
                                <span id="email-availability-status" class="adminui-status"></span>
                            </div>
                        </div>

                        <!-- Row 3: Clinic Address Full Width -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <textarea name="clinicaddress" placeholder=" "
                                    required><?php echo htmlentities($formData['clinicaddress']); ?></textarea>
                                <label class="adminui-label">Clinic Address</label>
                            </div>
                        </div>

                        <!-- Row 4: Consultancy Fees -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="number" name="docfees" placeholder=" " required
                                    value="<?php echo htmlentities($formData['docfees']); ?>">
                                <label class="adminui-label">Consultancy Fees</label>
                            </div>
                        </div>

                        <!-- Row 5: Password Fields -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="password" name="npass" placeholder=" " required>
                                <label class="adminui-label">Password</label>
                            </div>

                            <div class="adminui-input-group">
                                <input type="password" name="cfpass" placeholder=" " required>
                                <label class="adminui-label">Confirm Password</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Add Doctor</button>
                        </div>
                    </form>


                    </section>
                </div>

            </main>

            <!-- FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
</body>

</html>