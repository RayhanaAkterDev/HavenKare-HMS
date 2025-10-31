<?php
session_start();
// error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | View Profile</title>

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
                                View Profile
                            </h2>
                            <p class="mt-2 text-base text-indigo-800/60">Your personal information overview</p>
                        </div>

                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
                        while ($data = mysqli_fetch_array($sql)) {
                        ?>
                        <!-- Profile Info Card -->
                        <div
                            class="space-y-6 w-11/12 max-w-lg mx-auto shadow-xl/30 p-6 mb-10 rounded-xl border-t border-gray-300/40 bg-white">

                            <!-- Profile Header -->
                            <div class="text-center mb-4">
                                <div
                                    class="mx-auto mb-4 size-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-4xl font-bold">
                                    <?php echo strtoupper(substr($data['fullName'], 0, 1)); ?>
                                </div>
                                <h4 class="text-lg font-semibold text-indigo-800">
                                    <?php echo htmlentities($data['fullName']); ?>
                                </h4>
                                <p class="text-sm text-gray-600 mt-1"><b>Reg. Date:</b>
                                    <?php echo htmlentities($data['regDate']); ?></p>
                                <?php if ($data['updationDate']) { ?>
                                <p class="text-sm text-gray-600"><b>Last Update:</b>
                                    <?php echo htmlentities($data['updationDate']); ?></p>
                                <?php } ?>
                            </div>

                            <hr class="border-gray-300 mb-4" />

                            <!-- Details -->
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 font-medium">Full Name:</span>
                                    <span class="text-indigo-800 font-semibold">
                                        <?php echo htmlentities($data['fullName']); ?>
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 font-medium">Gender:</span>
                                    <span class="text-indigo-800 font-semibold">
                                        <?php echo htmlentities($data['gender']); ?>
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 font-medium">City:</span>
                                    <span class="text-indigo-800 font-semibold">
                                        <?php echo htmlentities($data['city']); ?>
                                    </span>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-gray-500 font-medium">Address:</span>
                                    <span class="text-indigo-800 font-semibold text-right max-w-xs">
                                        <?php echo htmlentities($data['address']); ?>
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 font-medium">Email:</span>
                                    <span class="text-indigo-800 font-semibold">
                                        <?php echo htmlentities($data['email']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="pt-6">
                                <a href="edit-profile.php"
                                    class="btn btn-primary w-full flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
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