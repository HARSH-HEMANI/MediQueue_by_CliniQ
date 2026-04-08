<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$queue_date = $_GET['date'] ?? date('Y-m-d');


$avgQ = mysqli_query($con, "
SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_time
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date'
AND t.status = 'Completed'
");

$avgTime = round(mysqli_fetch_assoc($avgQ)['avg_time'] ?? 5);

/* CURRENT TOKEN */
$currentQ = mysqli_query($con, "
SELECT t.token_no, p.full_name, a.appointment_type
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date'
AND t.status = 'In Progress'
LIMIT 1
");

$current = mysqli_fetch_assoc($currentQ);

/* QUEUE */
$queueQ = mysqli_query($con, "
SELECT t.token_no, p.full_name, a.appointment_type
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date'
AND t.status = 'Waiting'
ORDER BY 
    (a.appointment_type = 'Emergency') DESC,
    t.queue_position ASC,
    t.token_no ASC
");

$queue = [];
$position = 0;

while ($row = mysqli_fetch_assoc($queueQ)) {
    $position++;

    // ADD WAIT TIME
    $row['wait_time'] = $position * max($avgTime, 1);

    $queue[] = $row;
}

echo json_encode([
    "current" => $current,
    "queue" => $queue
]);