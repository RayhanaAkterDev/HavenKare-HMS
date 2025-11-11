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
    $fname = $_POST['fname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];

    $sql = mysqli_query($con, "UPDATE users SET 
        fullName='$fname',
        address='$address',
        city='$city',
        gender='$gender'
        WHERE id='" . $_SESSION['id'] . "'");

    if ($sql) {
        $_SESSION['msg'] = "Profile Updated Successfully."; // store message in session
        header("Location: " . $_SERVER['PHP_SELF']); // reload page to display message
        exit();
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Edit Profile | HeavenKare Patient</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Dashboard CSS -->
    <link href="../src/output.css" rel="stylesheet" />
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
                        <h1>Edit Profile</h1>
                        <p>Update your personal information.</p>
                    </header>

                    <!-- SQL query block that fetches user data -->
                    <?php
                    $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
                    while ($data = mysqli_fetch_array($sql)) {
                    ?>

                    <!-- Edit Form -->
                    <form role="form" name="editprofile" method="post" class="adminui-form-accent">

                        <!-- Header Info -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width text-center">
                                <h4 class="text-text-muted">Profile:
                                    <?php echo htmlentities($data['fullName']); ?> </h4>
                                <p class="text-sm text-gray-600 mt-1"><b>Reg. Date:</b>
                                    <?php echo htmlentities($data['regDate']); ?></p>
                                <?php if ($data['updationDate']) { ?>
                                <p class="text-sm text-gray-600"><b>Last Update:</b>
                                    <?php echo htmlentities($data['updationDate']); ?></p>
                                <?php } ?>
                            </div>
                        </div>

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

                        <!-- Full Name -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="text" name="fname" value="<?php echo htmlentities($data['fullName']); ?>"
                                    placeholder=" ">
                                <label class="adminui-label">Full Name</label>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <textarea name="address"
                                    placeholder=" "><?php echo htmlentities($data['address']); ?></textarea>
                                <label class="adminui-label">Address</label>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="text" name="city" value="<?php echo htmlentities($data['city']); ?>"
                                    placeholder=" ">
                                <label class="adminui-label">City</label>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width relative">
                                <select name="gender" placeholder=" "
                                    class="w-full bg-[#1F1F35] text-white px-4 pt-5 pb-2 rounded-md border border-[#2C2C42] outline-none focus:ring-0 focus:border-[#FF6E7F]">
                                    <?php
                                        $genders = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
                                        foreach ($genders as $value => $label) {
                                            $selected = (strtolower($data['gender']) == $value) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$label</option>";
                                        }
                                        ?>
                                </select>
                                <label class="adminui-label">Gender</label>
                            </div>
                        </div>


                        <!-- Email -->
                        <div class="adminui-form-row">
                            <div class="adminui-input-group full-width">
                                <input type="email" name="uemail" readonly
                                    value="<?php echo htmlentities($data['email']); ?>" placeholder=" "
                                    class="bg-gray-100 cursor-not-allowed text-gray-600">
                                <label class="adminui-label">Email</label>

                                <div class="admin-card-section-full flex justify-end mr-3 mt-4">
                                    <a href="change-email.php" class="dashboard-card__link">
                                        Update your
                                        email address<i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>

                                <!-- <a href="change-email.php" class=" mt-2 text-sm inline-block">Update your
                                email id</a> -->
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="adminui-submit">
                            <button type="submit" name="submit" id="submit">Update Profile</button>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </main>

            <!-- FIXED FOOTER -->
            <footer class="dashboard-footer fixed-footer">© 2025 HeavenKare. All rights reserved.</footer>
        </div>
    </div>

    <script src="../dist/main.js"></script>
</body>

</html>