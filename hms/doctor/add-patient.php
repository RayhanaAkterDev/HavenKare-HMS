<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $docid = $_SESSION['id'];
        $patname = $_POST['patname'];
        $patcontact = $_POST['patcontact'];
        $patemail = $_POST['patemail'];
        $gender = $_POST['gender'];
        $pataddress = $_POST['pataddress'];
        $patage = $_POST['patage'];
        $medhis = $_POST['medhis'];
        $sql = mysqli_query($con, "insert into tblpatient(Docid,PatientName,PatientContno,PatientEmail,PatientGender,PatientAdd,PatientAge,PatientMedhis) values('$docid','$patname','$patcontact','$patemail','$gender','$pataddress','$patage','$medhis')");
        if ($sql) {
            // replaced alert + JS redirect with session message and server-side redirect
            $_SESSION['msg'] = "Patient info added Successfully";
            header("Location: manage-patient.php");
            exit();
        }
    }
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Patient | HeavenKare Admin</title>

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
                        <h1>Add New Patient</h1>
                    </header>

                    <form role="form" name="addpatient" method="post" onsubmit="return valid();"
                        class="adminui-form-accent">
                        <p class="adminui-section__subtitle mb-1 underline !text-[#ff6e7f]">
                            Add Patient Information:
                        </p>

                        <!-- Row 1 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="text" name="patname" placeholder=" " required>
                                <label class="adminui-label">Patient Name</label>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="patcontact" placeholder=" " required maxlength="10"
                                    pattern="[0-9]+">
                                <label class="adminui-label">Patient Contact No</label>
                            </div>

                            <div class="adminui-input-group">
                                <input type="email" id="patemail" name="patemail" placeholder=" " required
                                    onblur="userAvailability()">
                                <label class="adminui-label">Patient Email</label>
                                <span id="user-availability-status1" class="adminui-status"></span>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <input type="text" name="patage" placeholder=" " required>
                                <label class="adminui-label">Patient Age</label>
                            </div>

                            <div class="adminui-input-group">
                                <!-- Label: always floated, overlaps border like others -->
                                <label
                                    class="absolute left-4 top-3 -translate-y-4 text-[#FF6E7F] text-xs font-medium bg-[#1B1B2D] px-1 z-20">
                                    Gender
                                </label>

                                <!-- Match input background, border, shadow, padding -->
                                <div
                                    class="w-full bg-[#1F1F35] text-white px-4 pt-4 pb-3 rounded-md border border-[#2C2C42] shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)]">
                                    <div class="flex items-center gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" id="rg-female" name="gender" value="female"
                                                class="w-4 h-4 accent-red-500">
                                            <span>Female</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" id="rg-male" name="gender" value="male"
                                                class="w-4 h-4 accent-red-500">
                                            <span>Male</span>
                                        </label>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <!-- Row 4 -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <textarea name="pataddress" placeholder=" " required></textarea>
                                <label class="adminui-label">Patient Address</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group">
                                <textarea name="medhis" placeholder=" " required></textarea>
                                <label class="adminui-label">Medical History</label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Add Patient</button>
                        </div>
                    </form>
                    new doctor to the system with their details.</p>
                </div>


                </section>
        </div>
        </main>

        <!-- FOOTER -->
        <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
    </div>
    </div>

    <script src="../../dist/main.js"></script>

    <script>
    function userAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'email=' + $("#patemail").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status1").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>
</body>

</html>

<?php } ?>