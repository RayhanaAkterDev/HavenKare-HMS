<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('include/config.php');

// 🚨 Step 1: Check if logged in
if (empty($_SESSION['login']) || $_SESSION['login'] !== true || empty($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

// 🚨 Step 2: Verify session token (prevents manual or multiple login)
$userId = intval($_SESSION['id']);
$sessionToken = $_SESSION['token'] ?? '';

$res = mysqli_query($con, "SELECT session_token FROM users WHERE id='$userId' LIMIT 1");
$row = mysqli_fetch_assoc($res);

if (!$row || $row['session_token'] !== $sessionToken) {
    session_unset();
    session_destroy();
    header("Location: user-login.php");
    exit();
}

// ✅ From here, the user is fully authenticated

// Logged-in patient ID
$uid = intval($_SESSION['id'] ?? 0);

// Fetch full patient data for profile
$data = null;
$userName = "User";
if ($uid > 0) {
    $res = mysqli_query($con, "SELECT * FROM users WHERE id='$uid' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $userName = $data['fullName'] ?? "User";
    }
}

if (isset($_POST['submit'])) {
    $specilization = $_POST['Doctorspecialization'];
    $doctorid = $_POST['doctor'];
    $userid = $_SESSION['id'];
    $fees = $_POST['fees'];
    $appdate = $_POST['appdate'];
    $time = $_POST['apptime'];
    $userstatus = 1;
    $docstatus = 1;

    $query = mysqli_query($con, "INSERT INTO appointment(doctorSpecialization,doctorId,userId,consultancyFees,appointmentDate,appointmentTime,userStatus,doctorStatus) 
    VALUES('$specilization','$doctorid','$userid','$fees','$appdate','$time','$userstatus','$docstatus')");

    if ($query) {
        $_SESSION['msg'] = "Your appointment has been successfully booked.";
        header("Location: book-appointment.php"); // reload same page to show msg
        exit();
    }
}

// Handle AJAX requests from JS
if (isset($_POST['specilizationid'])) {
    $spec = $_POST['specilizationid'];
    $query = mysqli_query($con, "SELECT * FROM doctors WHERE specilization='$spec'");
    echo "<option value=''>Select Doctor</option>";
    while ($row = mysqli_fetch_array($query)) {
        echo "<option value='" . htmlentities($row['id']) . "'>" . htmlentities($row['doctorName']) . "</option>";
    }
    exit;
}

if (isset($_POST['doctor'])) {
    $doc = $_POST['doctor'];
    $query = mysqli_query($con, "SELECT docFees FROM doctors WHERE id='$doc'");
    while ($row = mysqli_fetch_array($query)) {
        echo "<option value='" . htmlentities($row['docFees']) . "'>" . htmlentities($row['docFees']) . "</option>";
    }
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Book Appointment | HeavenKare Patient</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <link href="../src/output.css" rel="stylesheet" />
    <style>
    :root {
        --sidebar-w: 280px;
    }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // --- AJAX Fetch Doctor based on Specialization ---
    function getdoctor(val) {
        $.ajax({
            type: "POST",
            url: "", // same page
            data: {
                specilizationid: val
            },
            success: function(data) {
                $("#doctor").html(data);
                $("#fees").html('<option value="">Select Fee</option>');
            }
        });
    }

    // --- AJAX Fetch Fee based on Doctor ---
    function getfee(val) {
        $.ajax({
            type: "POST",
            url: "", // same page
            data: {
                doctor: val
            },
            success: function(data) {
                $("#fees").html(data);
            }
        });
    }
    </script>
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


                    <header class="admin-card-section-full admin-card-header !pt-4">
                        <h1>Book an Appointment</h1>
                        <p>Book with Your Doctor at Your Preferred Time</p>
                    </header>

                    <form method="post" name="book" class="adminui-form-accent">


                        <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
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

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <select name="Doctorspecialization" onChange="getdoctor(this.value);" required>
                                    <option value="">Select Specialization</option>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM doctorspecilization");
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <option value="<?php echo htmlentities($row['specilization']); ?>">
                                        <?php echo htmlentities($row['specilization']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <label class="adminui-label">Doctor Specialization</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <select name="doctor" id="doctor" onChange="getfee(this.value);" required>
                                    <option value="">Select Doctor</option>
                                </select>
                                <label class="adminui-label">Doctor</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <select name="fees" id="fees" readonly>
                                    <option value="">Select Fee</option>
                                </select>
                                <label class="adminui-label">Consultancy Fees</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <input type="date" name="appdate" required>
                                <label class="adminui-label">Appointment Date</label>
                            </div>
                        </div>

                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <input type="time" name="apptime" required>
                                <label class="adminui-label">Appointment Time</label>
                            </div>
                        </div>

                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">
                                <i class="fa-solid fa-calendar-check"></i>
                                Book Appointment
                            </button>
                        </div>

                    </form>

                    <div class="admin-card-section-full flex justify-end mr-3 mb-1">
                        <a href="appointment-history.php" class="dashboard-card__link  ">
                            View all your appointments<i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </main>

            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../dist/main.js"></script>
</body>

</html>