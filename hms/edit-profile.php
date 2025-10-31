<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

$msg = '';

if (isset($_POST['submit'])) {
	$fname = $_POST['fname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$gender = $_POST['gender'];

	$sql = mysqli_query($con, "UPDATE users SET fullName='$fname', address='$address', city='$city', gender='$gender' WHERE id='" . $_SESSION['id'] . "'");
	if ($sql) {
		$msg = "Your Profile updated Successfully";
	}
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | Edit Profile</title>

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
            <main class="flex flex-col w-full max-w-7xl mx-auto p-6 min-h-screen">
                <div class="card w-full">

                    <div class="card-body px-4 pt-6 rounded-xl">
                        <!-- Header -->
                        <div class="text-center bg-gradient-to-b from-gray-100 to-white rounded-t-md pt-12 pb-5">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-indigo-900/80 text-shadow-sm">
                                Edit Profile</h2>
                            <p class=" mt-2 text-base text-indigo-800/60">Update your personal information</p>
                        </div>

                        <!-- Message -->
                        <?php if ($msg) { ?>
                        <div
                            class="text-center bg-green-100 text-green-700 border border-green-300 rounded-md py-2 mt-4 mb-6">
                            <?php echo htmlentities($msg); ?>
                        </div>
                        <?php } ?>

                        <!-- Profile Form -->
                        <?php
						$sql = mysqli_query($con, "select * from users where id='" . $_SESSION['id'] . "'");
						while ($data = mysqli_fetch_array($sql)) {
						?>
                        <form role="form" name="edit" method="post"
                            class="space-y-6 w-11/12 max-w-lg mx-auto shadow-xl/30 p-6 mb-10 rounded-xl border-t border-gray-300/40 bg-white">

                            <!-- Profile Info -->
                            <div class="text-center mb-2">
                                <h4 class="text-lg font-semibold text-indigo-800">
                                    <?php echo htmlentities($data['fullName']); ?>'s
                                    Profile</h4>
                                <p class="text-sm text-gray-600 mt-1"><b>Reg. Date:</b>
                                    <?php echo htmlentities($data['regDate']); ?></p>
                                <?php if ($data['updationDate']) { ?>
                                <p class="text-sm text-gray-600"><b>Last Update:</b>
                                    <?php echo htmlentities($data['updationDate']); ?></p>
                                <?php } ?>
                            </div>

                            <hr class="border-gray-300 mb-4" />

                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">User Name</label>
                                <input type="text" name="fname" value="<?php echo htmlentities($data['fullName']); ?>"
                                    class="input input-bordered w-full" />
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Address</label>
                                <textarea name="address" class="textarea textarea-bordered w-full"
                                    rows="2"><?php echo htmlentities($data['address']); ?></textarea>
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">City</label>
                                <input type="text" name="city" required
                                    value="<?php echo htmlentities($data['city']); ?>"
                                    class="input input-bordered w-full" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Gender</label>
                                <select name="gender" required class="select select-bordered w-full">
                                    <option value="<?php echo htmlentities($data['gender']); ?>">
                                        <?php echo htmlentities($data['gender']); ?></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">User Email</label>
                                <input type="email" name="uemail" readonly
                                    value="<?php echo htmlentities($data['email']); ?>"
                                    class="input input-bordered w-full bg-gray-100 cursor-not-allowed text-gray-600" />
                                <a href="change-emaild.php"
                                    class="inline-block text-indigo-600 hover:text-indigo-800 mt-2 text-sm">Update your
                                    email id</a>
                            </div>

                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" name="submit"
                                    class="btn btn-primary w-full flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Update Profile
                                </button>
                            </div>
                        </form>
                        <?php } ?>
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