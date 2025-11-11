<?php
session_start();
include('include/config.php');

if (empty($_SESSION['isLoggedIn']) || empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (isset($_POST['id'])) {
    $appointmentId = intval($_POST['id']);

    // If admin is cancelling, set doctorStatus = 0
    $query = "UPDATE appointment SET doctorStatus = 0 WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $appointmentId);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo json_encode(['success' => true, 'by' => 'Admin']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}