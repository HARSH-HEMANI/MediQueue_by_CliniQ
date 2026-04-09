<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    echo json_encode([]);
    exit();
}

$patient_id = $_SESSION['patient_id'];
$search = $_GET['search'] ?? '';

$sql = "SELECT 
            cn.note_id as prescription_id,
            cn.diagnosis,
            cn.note_text as doctor_notes,
            cn.medicines,
            cn.follow_up_date,
            a.appointment_date,
            d.full_name as doctor_name,
            d.specialization
        FROM consultation_notes cn
        JOIN appointments a ON cn.appointment_id = a.appointment_id
        LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = ?";

if (!empty($search)) {
    $sql .= " AND (d.full_name LIKE ? OR cn.diagnosis LIKE ?)";
}

$sql .= " ORDER BY a.appointment_date DESC";

$stmt = mysqli_prepare($con, $sql);

if (!empty($search)) {
    $like = "%$search%";
    mysqli_stmt_bind_param($stmt, "iss", $patient_id, $like, $like);
} else {
    mysqli_stmt_bind_param($stmt, "i", $patient_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ✅ CREATE $data BEFORE USING IT
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// ✅ OUTPUT AT THE END
echo json_encode($data);
exit;
