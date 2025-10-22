<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <script src="../node_modules/flyonui/flyonui.js"></script>

</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <?php include('./include/sidebar.php'); ?>

    <!-- Main content area -->
    <div class="flex-1 flex flex-col">


    </div>

</body>

</html>