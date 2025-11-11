<?php
session_start();
error_reporting(0);
include('include/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $patientid = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM tblpatient WHERE ID ='$patientid'");
    $_SESSION['msg'] = "Patient data deleted!!";
    header("Location: manage-patient.php");
    exit;
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage Patients | HeavenKare Doctor</title>

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
                        <h1>Manage All Patients</h1>
                        <p>View and manage added patients details in the system.</p>
                    </header>

                    <!-- Display Success Message -->
                    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Success: </strong>
                        <span class="block sm:inline"><?php
                                                            echo htmlentities($_SESSION['msg']);
                                                            $_SESSION['msg'] = ""; // clear message
                                                            ?></span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                onclick="this.parentElement.parentElement.style.display='none';"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
                            </svg>
                        </span>
                    </div>
                    <?php } ?>

                    <table class="adminui-table" id="sample-table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Patient Contact Number</th>
                                <th class="adminui-table__hidden-md">Patient Gender</th>
                                <th class="adminui-table__hidden-lg">Creation Date</th>
                                <th class="adminui-table__hidden-lg">Updation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $docid = $_SESSION['id'];
                            $sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$docid'");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt; ?>.</td>
                                <td data-label="Patient Name"><?php echo htmlentities($row['PatientName']); ?></td>
                                <td data-label="Patient Contact Number">
                                    <?php echo htmlentities($row['PatientContno']); ?></td>
                                <td data-label="Patient Gender" class="adminui-table__hidden-md">
                                    <?php echo htmlentities($row['PatientGender']); ?>
                                </td>
                                <td data-label="Creation Date" class="adminui-table__hidden-lg">
                                    <?php echo htmlentities($row['CreationDate']); ?>
                                </td>
                                <td data-label="Updation Date" class="adminui-table__hidden-lg">
                                    <?php echo htmlentities($row['UpdationDate']); ?>
                                </td>
                                <td data-label="Actions">
                                    <div class="adminui-table__actions">

                                        <a href="view-patient.php?viewid=<?php echo $row['ID']; ?>"
                                            class="adminui-table__delete !bg-green-600/80">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="edit-patient.php?editid=<?php echo $row['ID']; ?>"
                                            class="adminui-table__edit">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenCare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../../dist/main.js"></script>

</body>

</html>