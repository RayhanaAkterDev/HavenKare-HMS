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
$uid = intval($_SESSION['id']); // Ensure ID is integer
$userName = "User"; // Default
$data = null;

$res = mysqli_query($con, "SELECT * FROM admin WHERE id='$uid' LIMIT 1");

if ($res && mysqli_num_rows($res) > 0) {
    $data = mysqli_fetch_assoc($res);
    $userName = !empty($data['username']) ? $data['username'] : "User";
}


// Helper function to format creation date
function formatCreationDate($datetime)
{
    if (!$datetime) return '—';
    $dt = new DateTime($datetime);
    $now = new DateTime();
    $diff = $now->diff($dt)->days;
    return $diff > 2 ? $dt->format('Y-m-d') : $dt->format('Y-m-d H:i');
}
?>

<!doctype html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Appointment History | HeavenKare Admin</title>

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

                    <header class="admin-card-section-full admin-card-header !py-4 mb-4">
                        <h1>All Appointments</h1>
                        <p>Search across all categories or select a specific type to filter results.</p>
                    </header>

                    <table class="adminui-table" id="sample-table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="hidden-xs">Patient Name</th>
                                <th>Doctor</th>
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
                            $sql = mysqli_query($con, "SELECT appointment.*, users.fullName AS patientName, doctors.doctorName AS doctorName, doctors.specilization AS specialization 
                                FROM appointment
                                JOIN users ON users.id = appointment.userId
                                JOIN doctors ON doctors.id = appointment.doctorId
                                ORDER BY appointment.appointmentDate DESC, appointment.appointmentTime DESC");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($sql)) {
                                $timeFormatted = date("g:i a", strtotime($row['appointmentTime']));
                            ?>
                            <tr>
                                <td data-label="#"><?php echo $cnt; ?>.</td>
                                <td data-label="Patient Name"><?php echo htmlentities($row['patientName']); ?></td>
                                <td data-label="Doctor"><?php echo htmlentities($row['doctorName']); ?></td>
                                <td data-label="Specialization"><?php echo htmlentities($row['specialization']); ?></td>
                                <td data-label="Consultancy Fee"><?php echo htmlentities($row['consultancyFees']); ?>
                                </td>
                                <td data-label="Appointment Date / Time">
                                    <?php echo htmlentities($row['appointmentDate']); ?> / <?php echo $timeFormatted; ?>
                                </td>
                                <td data-label="Creation Date"><?php echo htmlentities($row['postingDate']); ?></td>
                                <td data-label="Current Status">
                                    <?php
                                        if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                            echo "Active";
                                        } elseif (($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                            echo "Cancelled by Patient";
                                        } elseif (($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                            echo "Cancelled by Doctor";
                                        } else {
                                            echo "Cancelled";
                                        }
                                        ?>
                                </td>
                                <td data-label="Action">
                                    <div class="adminui-table__actions">
                                        <?php if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                        <a href="javascript:void(0);"
                                            onclick="openCancelModal('<?php echo $row['id']; ?>')"
                                            class="adminui-table__delete">
                                            <i class="fas fa-trash-alt"></i> Cancel
                                        </a>
                                        <?php } else { ?>
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

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
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

    <script>
    function openCancelModal(appointmentId) {
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.getElementById('confirmCancel').setAttribute('data-id', appointmentId);
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

    document.getElementById('confirmCancel').addEventListener('click', function(e) {
        e.preventDefault();
        const appointmentId = this.getAttribute('data-id');

        fetch('cancel-appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(appointmentId)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const actionCell = document.querySelector('a[onclick="openCancelModal(\'' +
                        appointmentId + '\')"]').parentElement;
                    actionCell.innerHTML =
                        '<span class="px-3 py-1 bg-gray-200 text-gray-500 text-sm rounded select-none cursor-not-allowed">Cancelled</span>';

                    const statusCell = actionCell.parentElement.querySelector(
                        'td[data-label="Current Status"]');
                    if (statusCell) {
                        statusCell.textContent = data.by === 'Admin' ? 'Cancelled by Admin' :
                            'Cancelled by Patient';
                    }
                } else {
                    alert(data.message || 'Failed to cancel appointment.');
                }
                closeCancelModal();
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred while cancelling the appointment.');
                closeCancelModal();
            });
    });
    </script>


    <script src="../../dist/main.js"></script>
</body>

</html>