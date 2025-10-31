<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

$msg = '';

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$sql = mysqli_query($con, "UPDATE users SET email='$email' WHERE id='" . $_SESSION['id'] . "'");
	if ($sql) {
		$msg = "Your email updated Successfully";
	}
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | Change Email</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Tailwind / FlyonUI -->
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
            <main class="flex flex-col w-full max-w-7xl mx-auto p-6">
                <div class="card w-full">
                    <div class="card-body px-4 pt-6 rounded-xl">
                        <!-- Header -->
                        <div class="text-center bg-gradient-to-b from-gray-100 to-white rounded-t-md pt-12 pb-5">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-indigo-900/80 text-shadow-sm">
                                Change Email
                            </h2>
                            <p class="mt-2 text-base text-indigo-800/60">
                                Update your registered email address
                            </p>
                        </div>

                        <!-- Message -->
                        <?php if ($msg) { ?>
                        <div
                            class="text-center bg-green-100 text-green-700 border border-green-300 rounded-md py-2 mt-4 mb-6">
                            <?php echo htmlentities($msg); ?>
                        </div>
                        <?php } ?>

                        <!-- Change Email Form -->
                        <form name="updatemail" id="updatemail" method="post"
                            class="space-y-6 w-11/12 max-w-lg mx-auto shadow-xl/30 p-6 mb-10 rounded-xl border-t border-gray-300/40 bg-white">

                            <div class="text-center mb-2">
                                <h4 class="text-lg font-semibold text-indigo-800">Change Email</h4>
                                <p class="text-sm text-gray-600 mt-1">Enter your new email address below</p>
                            </div>

                            <hr class="border-gray-300 mb-4" />

                            <!-- User Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2 text-gray-500">New
                                    Email</label>
                                <input type="email" name="email" id="email" onBlur="userAvailability()"
                                    placeholder="Enter new email" class="input input-bordered w-full" required>
                                <span id="user-availability-status1" class="text-sm text-gray-500 mt-1 block"></span>
                            </div>

                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" name="submit"
                                    class="btn btn-primary w-full flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                    Update Email
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function userAvailability() {
        $.ajax({
            url: "check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status1").html(data);
            },
            error: function() {}
        });
    }
    </script>
</body>

</html>