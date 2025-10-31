<?php
session_start();
error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    $vid = $_GET['viewid'];
    $bp = $_POST['bp'];
    $bs = $_POST['bs'];
    $weight = $_POST['weight'];
    $temp = $_POST['temp'];
    $pres = $_POST['pres'];

    $query = mysqli_query($con, "INSERT INTO tblmedicalhistory(PatientID,BloodPressure,BloodSugar,Weight,Temperature,MedicalPres) VALUES('$vid','$bp','$bs','$weight','$temp','$pres')");
    if ($query) {
        echo '<script>alert("Medical history has been added.")</script>';
        echo "<script>window.location.href ='manage-patient.php'</script>";
    } else {
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient | Medical History</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet" />
</head>

<body>

    <div class="bg-base-200 flex min-h-screen flex-col overflow-hidden">
        <!-- ---------- HEADER ---------- -->
        <?php include('include/header.php'); ?>
        <!-- ---------- END HEADER ---------- -->

        <!-- ---------- SIDEBAR ---------- -->
        <?php include('include/sidebar.php'); ?>
        <!-- ---------- END SIDEBAR ---------- -->

        <div class="flex grow flex-col lg:ps-75">
            <!-- ---------- MAIN CONTENT ---------- -->
            <main class="flex flex-col lg:flex-row w-full max-w-7xl mx-auto p-6 min-h-screen">
                <div class="card w-full">

                    <div class="card-body px-4 pt-6 rounded-xl">
                        <!-- Page Title -->
                        <div class="mb-6">
                            <h1 class="text-2xl sm:text-3xl font-semibold text-indigo-900/80">Medical History</h1>
                            <p class="text-gray-600 mt-1">View patient's medical history and records</p>
                        </div>

                        <?php
                        $vid = $_GET['viewid'];
                        $ret = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID='$vid'");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>

                        <!-- Patient Details -->
                        <div class="overflow-x-auto mb-6 rounded-lg shadow-md bg-white">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-100 text-blue-900">
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-lg font-semibold text-center">Patient
                                            Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 text-left font-medium">Patient Name</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientName']; ?></td>
                                        <th class="px-6 py-2 text-left font-medium">Patient Email</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientEmail']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-2 text-left font-medium">Patient Mobile Number</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientContno']; ?></td>
                                        <th class="px-6 py-2 text-left font-medium">Patient Address</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientAdd']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-2 text-left font-medium">Patient Gender</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientGender']; ?></td>
                                        <th class="px-6 py-2 text-left font-medium">Patient Age</th>
                                        <td class="px-6 py-2"><?php echo $row['PatientAge']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-2 text-left font-medium">Patient Medical History (if any)
                                        </th>
                                        <td class="px-6 py-2"><?php echo $row['PatientMedhis']; ?></td>
                                        <th class="px-6 py-2 text-left font-medium">Patient Reg Date</th>
                                        <td class="px-6 py-2"><?php echo $row['CreationDate']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>

                        <?php
                        $ret = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$vid'");
                        ?>

                        <!-- Medical History Table -->
                        <div class="overflow-x-auto rounded-lg shadow-md bg-white">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-indigo-100 text-indigo-900">
                                    <tr>
                                        <th colspan="7" class="px-6 py-3 text-lg font-semibold text-center">Medical
                                            History</th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-medium">#</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Blood Pressure</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Weight</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Blood Sugar</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Body Temperature</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Medical Prescription</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Visit Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-gray-700">
                                    <?php
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-2"><?php echo $cnt++; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['BloodPressure']; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['Weight']; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['BloodSugar']; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['Temperature']; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['MedicalPres']; ?></td>
                                        <td class="px-6 py-2"><?php echo $row['CreationDate']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>



            <!-- ---------- END MAIN CONTENT ---------- -->

            <!-- ---------- FOOTER CONTENT ---------- -->
            <?php include('include/footer.php'); ?>
            <!-- ---------- END FOOTER CONTENT ---------- -->
        </div>
    </div>

    <script src="../node_modules/flyonui/flyonui.js"></script>
</body>

</html>