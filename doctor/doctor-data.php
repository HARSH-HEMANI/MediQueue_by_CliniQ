<?php
include "../db.php";
session_start();

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

/* CURRENT PATIENT */
$currentQ = mysqli_query($con, "
SELECT 
    t.token_id, 
    t.token_no, 
    a.appointment_id,
    p.full_name
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id
AND a.appointment_date = '$today'
AND t.status = 'In Progress'
ORDER BY t.called_at DESC
LIMIT 1
");

$current = mysqli_fetch_assoc($currentQ);

/* UPCOMING */
$upcoming = [];
$upQ = mysqli_query($con, "
SELECT t.token_no, p.full_name, a.appointment_type
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id
AND a.appointment_date = '$today'
AND t.status = 'Waiting'
ORDER BY t.token_no ASC
LIMIT 5
");

while ($row = mysqli_fetch_assoc($upQ)) {
    $upcoming[] = $row;
}

/* RESPONSE */
echo json_encode([
    "current" => $current,
    "upcoming" => $upcoming
]);