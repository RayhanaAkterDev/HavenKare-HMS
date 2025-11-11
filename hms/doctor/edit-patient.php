<?php
session_start();
error_reporting(0);
include('include/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

if (isset($_POST['submit'])) {
    $eid = $_GET['editid'];
    $patname = $_POST['patname'];
    $patcontact = $_POST['patcontact'];
    $patemail = $_POST['patemail'];
    $gender = $_POST['gender'];
    $pataddress = $_POST['pataddress'];
    $patage = $_POST['patage'];
    $medhis = $_POST['medhis'];

    $sql = mysqli_query($con, "UPDATE tblpatient SET 
        PatientName='$patname',
        PatientContno='$patcontact',
        PatientEmail='$patemail',
        PatientGender='$gender',
        PatientAdd='$pataddress',
        PatientAge='$patage',
        PatientMedhis='$medhis'
        WHERE ID='$eid'");

    if ($sql) {
        $_SESSION['msg'] = "Patient Personal Information Updated.";
        header("Location: manage-patient.php");
        exit();
    } else {
        $msg = "Something went wrong. Please try again!";
    }
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Edit Patient | HeavenKare Doctor</title>

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
                        <h1>Edit Patient Profile</h1>
                        <p>Update patient details and personal information.</p>
                    </header>

                    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                    <p class="adminui-section__message">
                        <?php
                            echo htmlentities($_SESSION['msg']);
                            $_SESSION['msg'] = "";
                            ?>
                    </p>
                    <?php } ?>

                    <!-- Edit Patient Section -->
                    <form role="form" name="editpatient" method="post" class="adminui-form-accent">
                        <p class="adminui-section__subtitle mb-1 underline !text-[#ff6e7f]">
                            Edit Patient Information:
                        </p>

                        <?php
                        $eid = $_GET['editid'];
                        $ret = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID='$eid'");
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>

                        <!-- Row 1: Patient Name -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="text" name="patname"
                                    value="<?php echo htmlentities($row['PatientName']); ?>" placeholder=" ">
                                <label class="adminui-label">Patient Name</label>
                            </div>
                        </div>

                        <!-- Row 2: Contact & Email -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="patcontact"
                                    value="<?php echo htmlentities($row['PatientContno']); ?>" placeholder=" " required
                                    maxlength="10" pattern="[0-9]+">
                                <label class="adminui-label">Patient Contact No</label>
                            </div>

                            <div class="adminui-input-group">
                                <input type="email" id="patemail" name="patemail"
                                    value="<?php echo htmlentities($row['PatientEmail']); ?>" placeholder=" ">
                                <label class="adminui-label">Patient Email</label>
                            </div>
                        </div>

                        <!-- Row 3: Age & Gender -->
                        <div class="adminui-form-row ">
                            <div class="adminui-input-group">
                                <input type="text" name="patage" value="<?php echo htmlentities($row['PatientAge']); ?>"
                                    placeholder=" " required>
                                <label class="adminui-label">Patient Age</label>
                            </div>

                            <div class="adminui-input-group relative">
                                <label
                                    class="absolute left-4 top-3 -translate-y-4 text-[#FF6E7F] text-xs font-medium bg-[#1B1B2D] px-1 z-20">
                                    Gender
                                </label>

                                <div
                                    class="w-full bg-[#1F1F35] text-white px-4 pt-4 pb-3 rounded-md border border-[#2C2C42] shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                                    <div class="flex items-center gap-6">
                                        <?php
                                            $gender = isset($row['PatientGender']) ? strtolower(trim($row['PatientGender'])) : '';
                                            ?>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="gender" value="Female"
                                                class="w-4 h-4 accent-red-500"
                                                <?php echo ($gender === 'female') ? 'checked' : ''; ?>>
                                            <span>Female</span>
                                        </label>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="gender" value="Male"
                                                class="w-4 h-4 accent-red-500"
                                                <?php echo ($gender === 'male') ? 'checked' : ''; ?>>
                                            <span>Male</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: Address -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <textarea name="pataddress" placeholder=" "
                                    required><?php echo htmlentities($row['PatientAdd']); ?></textarea>
                                <label class="adminui-label">Patient Address</label>
                            </div>
                        </div>

                        <!-- Row 5: Medical History -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <textarea name="medhis" placeholder=" "
                                    required><?php echo htmlentities($row['PatientMedhis']); ?></textarea>
                                <label class="adminui-label">Medical History</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Update Patient</button>
                        </div>

                        <?php } ?>
                    </form>
                </div>
            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenCare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>
</body>

</html>