<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['patient_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $appointment_id = (int)$_POST['id'];
    $patient_id = $_SESSION['patient_id'];

    // Verify ownership and status
    $check = mysqli_query($con, "SELECT status FROM appointments WHERE appointment_id = $appointment_id AND patient_id = $patient_id");
    if($check && mysqli_num_rows($check) > 0) {
        $appt = mysqli_fetch_assoc($check);
        if($appt['status'] == 'Pending' || $appt['status'] == 'Confirmed') {
            $update = mysqli_query($con, "UPDATE appointments SET status = 'Cancelled' WHERE appointment_id = $appointment_id");
            if($update) {
                echo json_encode(['success' => true]);
                // Delete associated token if exists
                mysqli_query($con, "DELETE FROM tokens WHERE appointment_id = $appointment_id");
            } else {
                echo json_encode(['success' => false, 'error' => 'Database error']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Cannot cancel a completed or already cancelled appointment.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Appointment not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
