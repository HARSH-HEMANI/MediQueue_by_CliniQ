<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['patient_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$patient_id = $_SESSION['patient_id'];
$today = date('Y-m-d');

// Get token
$q_my_token = mysqli_query($con, "SELECT t.*, a.appointment_time, d.full_name as doctor_name, d.specialization, a.doctor_id
                                  FROM tokens t 
                                  JOIN appointments a ON t.appointment_id = a.appointment_id 
                                  JOIN doctors d ON a.doctor_id = d.doctor_id 
                                  WHERE a.patient_id = $patient_id 
                                  AND a.appointment_date = '$today' 
                                  AND t.status != 'Completed' LIMIT 1");

if (!$q_my_token || mysqli_num_rows($q_my_token) == 0) {
    echo json_encode(['no_queue' => true]);
    exit();
}

$my_token = mysqli_fetch_assoc($q_my_token);
$doc_id = $my_token['doctor_id'];

// Queue
$q_queue = mysqli_query($con, "SELECT t.*, p.full_name as patient_name, a.patient_id 
                               FROM tokens t 
                               JOIN appointments a ON t.appointment_id = a.appointment_id 
                               JOIN patients p ON a.patient_id = p.patient_id 
                               WHERE a.doctor_id = $doc_id 
                               AND a.appointment_date = '$today' 
                               AND t.status != 'Completed' 
                               ORDER BY t.token_no ASC");

$queue = [];
$current_serving = null;
$my_position = 0;

while ($row = mysqli_fetch_assoc($q_queue)) {
    $queue[] = $row;

    // detect current serving
    if ($row['status'] == 'In Progress' || $row['status'] == 'Consulting') {
        $current_serving = $row;
    }
}

// FIXED POSITION LOGIC
foreach ($queue as $row) {

    // stop when we reach current user
    if ($row['patient_id'] == $patient_id) break;

    // count only waiting patients
    if ($row['status'] == 'Waiting') {
        $my_position++;
    }
}

// Wait time
$avg_time = 7;
if ($current_serving && $current_serving['patient_id'] == $patient_id) {
    $my_position = 0;
    $wait_time = 0;
} else {
    $wait_time = $my_position * $avg_time;
}

// Progress
$progress = count($queue) > 1 ? max(10, 100 - ($my_position * (100 / count($queue)))) : 100;

echo json_encode([
    'queue' => $queue,
    'current_token' => $current_serving ? $current_serving['token_no'] : null,
    'position' => $my_position,
    'wait_time' => $wait_time,
    'progress' => $progress,
    'doctor_name' => $my_token['doctor_name'],
    'specialization' => $my_token['specialization'],
    'my_token' => $my_token['token_no']
]);
