<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$patientId = (int)$_GET['patient'];

$response = [];

/* -------- FETCH PATIENT -------- */
$q = mysqli_query($con, "SELECT * FROM patients WHERE patient_id = $patientId");
$current = mysqli_fetch_assoc($q);

if ($current) {
    $age = "N/A";
    if ($current['date_of_birth']) {
        $age = (new DateTime())->diff(new DateTime($current['date_of_birth']))->y;
    }

    $response['profile'] = [
        'name' => $current['full_name'],
        'age' => $age,
        'gender' => $current['gender'],
        'phone' => $current['phone']
    ];

    /* -------- FETCH HISTORY -------- */
    $histQ = mysqli_query($con, "
        SELECT a.appointment_date, a.appointment_type, cn.note_text, cn.diagnosis 
        FROM appointments a 
        LEFT JOIN consultation_notes cn ON a.appointment_id = cn.appointment_id 
        WHERE a.patient_id = $patientId AND a.doctor_id = $doctor_id
        ORDER BY a.appointment_date DESC
    ");

    $history = [];
    while ($row = mysqli_fetch_assoc($histQ)) {
        $history[] = $row;
    }

    $response['history'] = $history;
}

echo json_encode($response);