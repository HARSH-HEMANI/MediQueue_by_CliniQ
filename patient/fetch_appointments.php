<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['patient_id'])) {
    echo json_encode([]);
    exit();
}

$patient_id = $_SESSION['patient_id'];

$q = mysqli_query($con, "
    SELECT a.*, d.full_name as doctor_name, d.specialization, c.clinic_name, d.consultation_fee 
    FROM appointments a 
    LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
    LEFT JOIN clinics c ON a.clinic_id = c.clinic_id 
    WHERE a.patient_id = $patient_id 
    ORDER BY 
    a.appointment_date DESC,
    TIME(a.appointment_time) DESC,
    a.appointment_id DESC");

$data = [];

while ($row = mysqli_fetch_assoc($q)) {
    $row['total_fee'] = (int)$row['consultation_fee'] + 20;
    $data[] = $row;
}

echo json_encode($data);
