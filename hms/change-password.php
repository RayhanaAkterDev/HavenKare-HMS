<?php
session_start();
// error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

date_default_timezone_set('Asia/Kolkata'); // change according timezone
$currentTime = date('d-m-Y h:i:s A', time());
$_SESSION['msg1'] = $_SESSION['msg1'] ?? ''; // ✅ prevent undefined notice

if (isset($_POST['submit'])) {
    $sql = mysqli_query($con, "SELECT password FROM users WHERE password='" . md5($_POST['cpass']) . "' && id='" . $_SESSION['id'] . "'");
    $num = mysqli_fetch_array($sql);

    if ($num > 0) {
        mysqli_query($con, "UPDATE users SET password='" . md5($_POST['npass']) . "', updationDate='$currentTime' WHERE id='" . $_SESSION['id'] . "'");
        $_SESSION['msg1'] = "Password Changed Successfully !!";
    } else {
        $_SESSION['msg1'] = "Old Password not match !!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | Change Password</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet" />

    <script>
    function valid() {
        if (document.chngpwd.cpass.value == "") {
            alert("Current Password Field is Empty !!");
            document.chngpwd.cpass.focus();
            return false;
        } else if (document.chngpwd.npass.value == "") {
            alert("New Password Field is Empty !!");
            document.chngpwd.npass.focus();
            return false;
        } else if (document.chngpwd.cfpass.value == "") {
            alert("Confirm Password Field is Empty !!");
            document.chngpwd.cfpass.focus();
            return false;
        } else if (document.chngpwd.npass.value != document.chngpwd.cfpass.value) {
            alert("Password and Confirm Password Field do not match !!");
            document.chngpwd.cfpass.focus();
            return false;
        }
        return true;
    }
    </script>
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
            <main class="flex flex-col w-full max-w-7xl mx-auto p-6">
                <div class="card w-full">
                    <div class="card-body px-4 pt-6 rounded-xl">
                        <!-- Header -->
                        <div class="text-center bg-gradient-to-b from-gray-100 to-white rounded-t-md pt-12 pb-5">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-indigo-900/80 text-shadow-sm">
                                Change Password
                            </h2>
                            <p class="mt-2 text-base text-indigo-800/60">
                                Update your account password securely
                            </p>
                        </div>

                        <!-- Message -->
                        <?php if (!empty($_SESSION['msg1'])) { ?>
                        <div
                            class="text-center bg-green-100 text-green-700 border border-green-300 rounded-md py-2 mt-4 mb-6">
                            <?php echo htmlentities($_SESSION['msg1']); ?>
                        </div>
                        <?php $_SESSION['msg1'] = "";
                        } ?>

                        <!-- Change Password Form -->
                        <form role="form" name="chngpwd" method="post" onSubmit="return valid();"
                            class="space-y-6 w-11/12 max-w-lg mx-auto shadow-xl/30 p-6 mb-10 rounded-xl border-t border-gray-300/40 bg-white">

                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Current Password</label>
                                <input type="password" name="cpass" placeholder="Enter Current Password"
                                    class="input input-bordered w-full" />
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">New Password</label>
                                <input type="password" name="npass" placeholder="New Password"
                                    class="input input-bordered w-full" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Confirm Password</label>
                                <input type="password" name="cfpass" placeholder="Confirm Password"
                                    class="input input-bordered w-full" />
                            </div>

                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" name="submit"
                                    class="btn btn-primary w-full flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-key"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
            <!-- ---------- END MAIN CONTENT ---------- -->

            <!-- ---------- FOOTER ---------- -->
            <?php include('include/footer.php'); ?>
            <!-- ---------- END FOOTER ---------- -->
        </div>
    </div>

    <script src="../node_modules/flyonui/flyonui.js"></script>
</body>

</html>