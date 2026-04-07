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

    if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $_SESSION['booking_error'] = "All fields required.";
        header("Location: book_appointment.php");
        exit();
    }

    // Insert appointment
    $insert = mysqli_query($con, "
        INSERT INTO appointments 
        (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, status, visit_reason) 
        VALUES 
        ($patient_id, $doctor_id, $clinic_id, '$appointment_date', '$appointment_time', '$appointment_type', 'Confirmed', '$visit_reason')
    ");

    if (!$insert) {
        $_SESSION['booking_error'] = mysqli_error($con);
        header("Location: book_appointment.php");
        exit();
    }

    $appointment_id = mysqli_insert_id($con);

    // 🔥 Get next token number (doctor-wise)
    $q = mysqli_query($con, "
        SELECT MAX(t.token_no) as last_token
        FROM tokens t
        JOIN appointments a ON t.appointment_id = a.appointment_id
        WHERE a.doctor_id = $doctor_id 
        AND a.appointment_date = '$appointment_date'
    ");

    $row = mysqli_fetch_assoc($q);
    $token_no = ($row['last_token'] ?? 0) + 1;

    // 🔥 Check if first patient
    $status = ($token_no == 1) ? 'In Progress' : 'Waiting';

    // Insert token
    mysqli_query($con, "
        INSERT INTO tokens (appointment_id, token_no, queue_position, status)
        VALUES ($appointment_id, $token_no, $token_no, '$status')
    ");

    $_SESSION['booking_success'] = true;
    header("Location: book_appointment.php");
    exit();
}
