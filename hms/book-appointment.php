<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

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
        echo "<script>alert('Your appointment has been successfully booked');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User | Book Appointment</title>

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

    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    function getdoctor(val) {
        $.ajax({
            type: "POST",
            url: "get_doctor.php",
            data: {
                specilizationid: val
            },
            success: function(data) {
                $("#doctor").html(data);
            }
        });
    }

    function getfee(val) {
        $.ajax({
            type: "POST",
            url: "get_doctor.php",
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
                        <!-- Header -->
                        <div class="text-center bg-gradient-to-b from-gray-100 to-white rounded-t-md pt-12 pb-5">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-indigo-900/80 text-shadow-sm">
                                Book
                                Appointment</h2>
                            <p class=" mt-2 text-base text-indigo-800/60">Pick your doctor and preferred time slot</p>
                        </div>


                        <!-- Form -->
                        <form method="post" name="book"
                            class="space-y-6 w-11/12 max-w-lg mx-auto shadow-xl/30 p-6 mb-10 rounded-xl border-t border-gray-300/40">

                            <!-- Doctor Specialization -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Doctor
                                    Specialization</label>
                                <select name="Doctorspecialization" onChange="getdoctor(this.value);" required
                                    class="select select-bordered w-full">
                                    <option value="">Select Specialization</option>
                                    <?php
                                    $ret = mysqli_query($con, "select * from doctorspecilization");
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <option value="<?php echo htmlentities($row['specilization']); ?>">
                                        <?php echo htmlentities($row['specilization']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Doctor -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Doctor</label>
                                <select name="doctor" id="doctor" onChange="getfee(this.value);" required
                                    class="select select-bordered w-full">
                                    <option value="">Select Doctor</option>
                                </select>
                            </div>

                            <!-- Consultancy Fees -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Consultancy Fees</label>
                                <select name="fees" id="fees" readonly
                                    class="select select-bordered w-full bg-gray-100 cursor-not-allowed text-gray-600">
                                </select>
                            </div>

                            <!-- Appointment Date -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Appointment Date</label>
                                <input type="date" name="appdate" required class="input input-bordered w-full" />
                            </div>

                            <!-- Appointment Time -->
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-500">Appointment Time</label>
                                <input type="time" name="apptime" required class="input input-bordered w-full" />
                            </div>

                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" name="submit"
                                    class="btn btn-gradient btn-primary w-full flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    Book Appointment
                                </button>
                            </div>

                        </form>
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