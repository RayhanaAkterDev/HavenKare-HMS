<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit;
} else {

    // ✅ FIX: Process cancellation and redirect properly
    if (isset($_GET['cancel']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        mysqli_query($con, "UPDATE appointment SET doctorStatus='0' WHERE id='$id'");
        $_SESSION['msg'] = "Appointment canceled successfully!";
        header("Location: appointment-history.php");
        exit;
    }
?>
<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Appointment History | HeavenKare Doctor</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@400;500;600;700&display=swap"
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
                        <h1>Appointments</h1>
                        <p>Review all scheduled, active, and cancelled appointments with your patients.</p>
                    </header>


                    <!-- ✅ Success Message -->
                    <?php if (!empty($_SESSION['msg'])) { ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Success: </strong>
                        <span class="block sm:inline">
                            <?php
                                    echo htmlentities($_SESSION['msg']);
                                    $_SESSION['msg'] = "";
                                    ?>
                        </span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
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
                                <th class="hidden-xs">Patient Name</th>
                                <th>Specialization</th>
                                <th>Consultancy Fee</th>
                                <th>Appointment Date / Time</th>
                                <th>Creation Date</th>
                                <th>Current Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = mysqli_query($con, "SELECT users.fullName AS fname, appointment.* 
                                                       FROM appointment 
                                                       JOIN users ON users.id = appointment.userId 
                                                       WHERE appointment.doctorId = '" . $_SESSION['id'] . "'");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($sql)) {
                                ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt; ?>.</td>
                                <td data-label="Patient Name"><?php echo htmlentities($row['fname']); ?></td>
                                <td data-label="Specialization">
                                    <?php echo htmlentities($row['doctorSpecialization']); ?></td>
                                <td data-label="Consultancy Fee"><?php echo htmlentities($row['consultancyFees']); ?>
                                </td>
                                <td data-label="Appointment Date / Time">
                                    <?php echo htmlentities($row['appointmentDate']); ?> /
                                    <?php echo htmlentities($row['appointmentTime']); ?>
                                </td>
                                <td data-label="Creation Date"><?php echo htmlentities($row['postingDate']); ?></td>
                                <td data-label="Current Status">
                                    <?php
                                            if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                                echo "Active";
                                            } elseif (($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                                echo "Cancelled by Patient";
                                            } elseif (($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                                echo "Cancelled by You";
                                            } else {
                                                echo "Cancelled";
                                            }
                                            ?>
                                </td>
                                <td data-label="Action">
                                    <div class="adminui-table__actions">
                                        <?php if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                        <a href="javascript:void(0);"
                                            onclick="openCancelModal('appointment-history.php?id=<?php echo $row['id']; ?>&cancel=update')"
                                            class="adminui-table__delete">
                                            <i class="fas fa-trash-alt"></i> Cancel
                                        </a>
                                        <?php } else { ?>
                                        <!-- ✅ Styled cancelled badge -->
                                        <span
                                            class="px-3 py-1 bg-gray-200 text-gray-500 text-sm rounded select-none cursor-not-allowed">
                                            Cancelled
                                        </span>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php $cnt++;
                                } ?>
                        </tbody>
                    </table>
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenCare. All rights reserved.</footer>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div id="cancelModal"
        class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center transition-opacity duration-300">
        <div id="cancelModalContent"
            class="bg-white rounded-lg shadow-lg p-6 w-96 relative transform scale-90 opacity-0 transition-all duration-300">
            <h3 class="text-lg font-semibold mb-4">Confirm Cancellation</h3>
            <p class="mb-6">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelCancel"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">No</button>
                <a id="confirmCancel" href="#" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Yes,
                    Cancel</a>
            </div>
            <span class="absolute top-2 right-2 cursor-pointer text-gray-500"
                onclick="closeCancelModal()">&times;</span>
        </div>
    </div>

    <script src="../../dist/main.js"></script>

    <script>
    function openCancelModal(cancelUrl) {
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.getElementById('confirmCancel').setAttribute('href', cancelUrl);
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');
        modalContent.classList.add('scale-90', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('cancelCancel').addEventListener('click', closeCancelModal);
    </script>
</body>

</html>
<?php } ?>