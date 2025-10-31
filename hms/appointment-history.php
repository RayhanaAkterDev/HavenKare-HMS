<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

$userid = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient | Appointment History</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

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
            <main class="flex flex-col lg:flex-row w-full max-w-7xl mx-auto p-6 min-h-screen">
                <div class="card w-full">

                    <div class="card-body px-4 pt-6 rounded-xl">
                        <!-- Page Title -->
                        <div class="mb-4">
                            <h1 class="text-2xl sm:text-3xl font-semibold text-indigo-900/80">Appointment History</h1>
                            <p class="text-gray-600 mt-1 pb-3 border-b border-indigo-300/30">View all your appointments
                            </p>
                        </div>

                        <!-- Appointment Table -->
                        <div class="overflow-x-auto rounded-lg shadow-md">
                            <table class="min-w-full bg-white divide-y divide-gray-200">
                                <thead class="bg-indigo-100 text-indigo-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-medium">#</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Doctor Specialization</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Doctor</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Consultancy Fees</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Appointment Date</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Appointment Time</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">User Status</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium">Doctor Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM appointment WHERE userId='$userid' ORDER BY id DESC");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo $cnt++; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo htmlentities($row['doctorSpecialization']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo htmlentities($row['doctorId']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo htmlentities($row['consultancyFees']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo htmlentities($row['appointmentDate']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <?php echo htmlentities($row['appointmentTime']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <?php echo $row['userStatus'] ? 'Active' : 'Cancelled'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <?php echo $row['doctorStatus'] ? 'Active' : 'Cancelled'; ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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