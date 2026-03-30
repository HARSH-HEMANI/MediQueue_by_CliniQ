<?php
require_once "reception-init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $id = (int)$_POST['appointment_id'];
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $clinic_id = $_SESSION['clinic_id'];

    // Update status to match the Enum selected from the dropdown
    $query = "UPDATE appointments SET status = '$status' 
              WHERE appointment_id = $id AND clinic_id = $clinic_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update status.";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
