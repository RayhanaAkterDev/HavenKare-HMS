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

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $vid = $_GET['viewid'];
        $bp = $_POST['bp'];
        $bs = $_POST['bs'];
        $weight = $_POST['weight'];
        $temp = $_POST['temp'];
        $pres = $_POST['pres'];

        $query = mysqli_query($con, "INSERT INTO tblmedicalhistory(PatientID,BloodPressure,BloodSugar,Weight,Temperature,MedicalPres) 
            VALUES('$vid','$bp','$bs','$weight','$temp','$pres')");

        if ($query) {
            echo '<script>alert("Medical history has been added.")</script>';
            echo "<script>window.location.href ='manage-patient.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
}
?>

<!doctype html>
<html lang="en" data-theme="light">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage Patients | HeavenKare Admin</title>

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

                <!-- Patient Details Section -->
                <section class="admin-card-details">

                    <?php
                    $vid = $_GET['viewid'];
                    $ret = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID='$vid'");
                    $row = mysqli_fetch_array($ret);
                    ?>

                    <div class="admin-card-grid">

                        <header class="admin-card-header admin-card-section-full">
                            <h1>Patient Details</h1>
                            <p>All essential information at a glance</p>
                        </header>

                        <!-- Personal Info -->
                        <div class="admin-card-section">
                            <h2>Personal Info</h2>
                            <div class="admin-card-row"><span class="admin-card-label">Name:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientName']); ?></span>
                            </div>
                            <div class="admin-card-row"><span class="admin-card-label">Gender:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientGender']); ?></span>
                            </div>
                            <div class="admin-card-row"><span class="admin-card-label">Age:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientAge']); ?></span>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="admin-card-section">
                            <h2>Contact Info</h2>
                            <div class="admin-card-row"><span class="admin-card-label">Email:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientEmail']); ?></span>
                            </div>
                            <div class="admin-card-row"><span class="admin-card-label">Mobile:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientContno']); ?></span>
                            </div>
                            <div class="admin-card-row"><span class="admin-card-label">Address:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientAdd']); ?></span>
                            </div>
                        </div>

                        <!-- Medical Info -->
                        <div class="admin-card-section admin-card-section-full">
                            <h2>Medical Info</h2>
                            <div class="admin-card-row"><span class="admin-card-label">Medical History:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['PatientMedhis']); ?></span>
                            </div>
                            <div class="admin-card-row"><span class="admin-card-label">Registration Date:</span><span
                                    class="admin-card-value"><?php echo htmlentities($row['CreationDate']); ?></span>
                            </div>
                        </div>


                        <!-- Separator -->
                        <div class="my-6 border-t border-gray-600 admin-card-section-full"></div>

                        <header class="admin-card-header admin-card-section-full">
                            <h1>Medical Records</h1>
                            <p>All medical records in details for this patient.</p>
                        </header>

                        <table class="adminui-table admin-card-section-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Blood Pressure</th>
                                    <th>Weight</th>
                                    <th>Blood Sugar</th>
                                    <th>Temperature</th>
                                    <th>Prescription</th>
                                    <th>Visit Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$vid'");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <tr>
                                    <td data-label="#"><?php echo $cnt; ?></td>
                                    <td data-label="Blood Pressure"><?php echo htmlentities($row['BloodPressure']); ?>
                                    </td>
                                    <td data-label="Weight"><?php echo htmlentities($row['Weight']); ?></td>
                                    <td data-label="Blood Sugar"><?php echo htmlentities($row['BloodSugar']); ?></td>
                                    <td data-label="Temperature"><?php echo htmlentities($row['Temperature']); ?></td>
                                    <td data-label="Prescription"><?php echo htmlentities($row['MedicalPres']); ?></td>
                                    <td data-label="Visit Date"><?php echo htmlentities($row['CreationDate']); ?></td>
                                </tr>
                                <?php $cnt++;
                                } ?>
                            </tbody>
                        </table>

                    </div>
                </section>

            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenCare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>

</body>

</html>