<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();
?>

<?php
$currentPage = basename($_SERVER['PHP_SELF']); // gets current filename
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Google fonts -->
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
    <div class="bg-base-200 flex min-h-screen flex-col">
        <!-- ---------- HEADER ---------- -->
        <?php include('include/header.php'); ?>
        <!-- ---------- END HEADER ---------- -->

        <!-- ---------- SIDEBAR ---------- -->
        <?php include('include/sidebar.php'); ?>
        <!-- ---------- END SIDEBAR ---------- -->

        <div class="flex grow flex-col lg:ps-75">
            <!-- ---------- MAIN CONTENT ---------- -->
            <main class="mx-auto w-full max-w-7xl flex-1 p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="card h-120 w-full">
                        <div class="card-body border-base-content/20 rounded-box skeleton-striped m-6 border"></div>
                    </div>
                    <div class="card h-120 w-full">
                        <div class="card-body border-base-content/20 rounded-box skeleton-striped m-6 border"></div>
                    </div>
                </div>
            </main>
            <!-- ---------- END MAIN CONTENT ---------- -->

            <!-- ---------- FOOTER CONTENT ---------- -->
            <?php include('include/footer.php'); ?>
            <!-- ---------- END FOOTER CONTENT ---------- -->
        </div>
    </div>

    <script src="../node_modules/flyonui/flyonui.js"></script>

</body>

</html>