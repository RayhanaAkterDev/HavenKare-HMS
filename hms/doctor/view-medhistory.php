<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$vid = isset($_GET['viewid']) ? $_GET['viewid'] : 0;

// Handle Medical History Submission
if (isset($_POST['submit'])) {
    $bp = $_POST['bp'];
    $bs = $_POST['bs'];
    $weight = $_POST['weight'];
    $temp = $_POST['temp'];
    $pres = $_POST['pres'];

    $query = mysqli_query($con, "INSERT INTO tblmedicalhistory(PatientID,BloodPressure,BloodSugar,Weight,Temperature,MedicalPres) 
        VALUES('$vid','$bp','$bs','$weight','$temp','$pres')");

    if ($query) {
        $_SESSION['msg'] = "Medical history has been added successfully!";
        header("Location: view-medhistory.php?viewid=$vid");
        exit;
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
        header("Location: view-medhistory.php?viewid=$vid");
        exit;
    }
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>View Patients | HeavenKare Doctor</title>

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

                    <header class="admin-card-section-full admin-card-header mb-8">
                        <h1>Medical History</h1>
                        <p>All medical visit details for this patient.</p>
                    </header>

                    <div class="admin-card-section-full flex justify-end mr-3 mb-4">
                        <a href="view-patient.php?viewid=<?php echo $row['ID']; ?>" class="dashboard-card__link  ">
                            View Patient's Personal Inforamtion <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Display Success Message -->
                    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Success: </strong>
                        <span class="block sm:inline">
                            <?php
                                echo htmlentities($_SESSION['msg']);
                                $_SESSION['msg'] = ""; // clear message
                                ?>
                        </span>
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

                    <table class="adminui-table">
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
                            $ret2 = mysqli_query($con, "SELECT * FROM tblmedicalhistory WHERE PatientID='$vid'");
                            $cnt = 1;
                            while ($row2 = mysqli_fetch_array($ret2)) {
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt; ?></td>
                                <td data-label="Blood Pressure"><?php echo htmlentities($row2['BloodPressure']); ?>
                                </td>
                                <td data-label="Weight"><?php echo htmlentities($row2['Weight']); ?></td>
                                <td data-label="Blood Sugar"><?php echo htmlentities($row2['BloodSugar']); ?></td>
                                <td data-label="Temperature"><?php echo htmlentities($row2['Temperature']); ?></td>
                                <td data-label="Prescription"><?php echo htmlentities($row2['MedicalPres']); ?></td>
                                <td data-label="Visit Date"><?php echo htmlentities($row2['CreationDate']); ?></td>
                            </tr>
                            <?php $cnt++;
                            } ?>
                        </tbody>
                    </table>


                    <!-- Add Medical History Button -->
                    <div class="admin-card-section-full text-center mt-8 border-t border-[#2A2A50]">
                        <div class="adminui-submit mt-4 !inline-block">
                            <button id="openModalBtn" type="button">
                                Add New History
                            </button>
                        </div>
                    </div>

                </div>

            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenCare. All rights reserved.</footer>
        </div>
    </div>

    <!-- Tailwind Modal (Design Intact) -->
    <div id="medicalModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 modal-bg">
        <div class="bg-[#1B1B2D] rounded-xl w-full max-w-lg relative shadow-lg">

            <button id="closeModalBtn"
                class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl font-bold">&times;</button>

            <form method="post" class="adminui-form-accent">

                <h2 class="text-2xl font-semibold mb-2 text-center text-[#FF6E7F]">Add Medical History</h2>

                <!-- Blood Pressure -->
                <div class="adminui-form-row">
                    <div class="adminui-input-group full-width">
                        <input type="text" name="bp" placeholder=" " required>
                        <label class="adminui-label">Blood Pressure</label>
                    </div>
                </div>

                <!-- Blood Sugar & Weight -->
                <div class="adminui-form-row">
                    <div class="adminui-input-group">
                        <input type="text" name="bs" placeholder=" " required>
                        <label class="adminui-label">Blood Sugar</label>
                    </div>
                    <div class="adminui-input-group">
                        <input type="text" name="weight" placeholder=" " required>
                        <label class="adminui-label">Weight</label>
                    </div>
                </div>

                <!-- Temperature -->
                <div class="adminui-form-row">
                    <div class="adminui-input-group full-width">
                        <input type="text" name="temp" placeholder=" " required>
                        <label class="adminui-label">Temperature</label>
                    </div>
                </div>

                <!-- Prescription -->
                <div class="adminui-form-row">
                    <div class="adminui-input-group full-width">
                        <textarea name="pres" placeholder=" " rows="4" required></textarea>
                        <label class="adminui-label">Prescription</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="adminui-submit flex justify-end gap-6">
                    <button type="button" id="closeModalBtn2"
                        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">Close</button>
                    <button type="submit" name="submit"
                        class="bg-[#FF6E7F] hover:bg-[#ff4f65] text-white px-4 py-2 rounded">Submit</button>
                </div>

            </form>
        </div>
    </div>

    <script src="../../dist/main.js"></script>

    <script>
    // Modal logic (kept intact)
    const modal = document.getElementById('medicalModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const closeBtn2 = document.getElementById('closeModalBtn2');

    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    closeBtn2.addEventListener('click', () => modal.classList.add('hidden'));
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });
    </script>

</body>

</html>