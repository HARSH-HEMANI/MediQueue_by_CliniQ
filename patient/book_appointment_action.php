<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id       = $_SESSION['patient_id'];
    $doctor_id        = (int)$_POST['doctor_id'];
    $clinic_id        = (int)$_POST['clinic_id'];
    $appointment_date = mysqli_real_escape_string($con, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($con, $_POST['appointment_time']);
    $appointment_type = mysqli_real_escape_string($con, $_POST['appointment_type']);
    $visit_reason     = mysqli_real_escape_string($con, trim($_POST['visit_reason']));

    // Validation
    if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time) || empty($appointment_type) || empty($visit_reason)) {
        $_SESSION['booking_error'] = "All fields are required. Please ensure date and time are selected.";
        header("Location: book_appointment.php");
        exit();
    }
    
    // Check if clinic_id is missing and try to fetch it from doctor
    if (empty($clinic_id)) {
        $doc_query = mysqli_query($con, "SELECT clinic_id FROM doctors WHERE doctor_id = $doctor_id");
        if(mysqli_num_rows($doc_query) > 0) {
            $doc_row = mysqli_fetch_assoc($doc_query);
            $clinic_id = $doc_row['clinic_id'];
        } else {
            $_SESSION['booking_error'] = "Invalid Doctor selection.";
            header("Location: book_appointment.php");
            exit();
        }
    }

    // 1. Insert into appointments
    $query = "INSERT INTO appointments (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, status, visit_reason) 
              VALUES ($patient_id, $doctor_id, $clinic_id, '$appointment_date', '$appointment_time', '$appointment_type', 'Pending', '$visit_reason')";

    if (mysqli_query($con, $query)) {
        $appointment_id = mysqli_insert_id($con);

        // 2. Generate Token Number (auto-increment per doctor per day)
        $token_query = "SELECT MAX(t.token_no) AS last_token 
                        FROM tokens t 
                        JOIN appointments a ON t.appointment_id = a.appointment_id 
                        WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$appointment_date'";
        $token_res = mysqli_query($con, $token_query);
        $row = mysqli_fetch_assoc($token_res);
        
        $token_no = ($row['last_token'] !== null) ? (int)$row['last_token'] + 1 : 1;
        $queue_position = $token_no; // Initial queue position is the token number itself

        // 3. Insert into tokens table
        $token_insert = "INSERT INTO tokens (appointment_id, token_no, queue_position, status) 
                         VALUES ($appointment_id, $token_no, $queue_position, 'Waiting')";
        
        if (mysqli_query($con, $token_insert)) {
            $_SESSION['booking_success'] = true;
            header("Location: book_appointment.php");
            exit();
        } else {
            $_SESSION['booking_error'] = "Failed to generate token: " . mysqli_error($con);
            header("Location: book_appointment.php");
            exit();
        }
    } else {
        $_SESSION['booking_error'] = "Failed to book appointment: " . mysqli_error($con);
        header("Location: book_appointment.php");
        exit();
    }
} else {
    header("Location: book_appointment.php");
    exit();
}
?>
