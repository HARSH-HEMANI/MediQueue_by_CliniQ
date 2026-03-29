<?php
include "doctor-auth.php";
include "../db.php";

$action = $_GET['action'] ?? '';
$token_id = isset($_GET['token_id']) ? (int)$_GET['token_id'] : 0;
$token_no = isset($_GET['token_no']) ? (int)$_GET['token_no'] : 0;

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

if ($action === 'complete' && $token_id) {
    mysqli_query($con, "UPDATE tokens SET status = 'Completed', completed_at = NOW() WHERE token_id = $token_id");
} elseif ($action === 'skip' && $token_id) {
    // Hold / Skip
    mysqli_query($con, "UPDATE tokens SET status = 'Skipped' WHERE token_id = $token_id");
} elseif ($action === 'next') {
    // 1. Mark currently 'In Progress' as 'Completed' if any
    mysqli_query($con, "UPDATE tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                        SET t.status = 'Completed', t.completed_at = NOW() 
                        WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'In Progress'");
    
    // 2. Find next waiting token. Prioritize emergencies if any.
    $findNextQ = mysqli_query($con, "SELECT t.token_id FROM tokens t 
                                     JOIN appointments a ON t.appointment_id = a.appointment_id 
                                     WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'Waiting' 
                                     ORDER BY (a.appointment_type = 'Emergency') DESC, t.queue_position ASC LIMIT 1");
    if ($next = mysqli_fetch_assoc($findNextQ)) {
        mysqli_query($con, "UPDATE tokens SET status = 'In Progress', called_at = NOW() WHERE token_id = " . $next['token_id']);
    }
} elseif ($action === 'call_emergency' && $token_no) {
    // 1. Mark current 'In Progress' as 'Skipped' (put on hold basically)
    mysqli_query($con, "UPDATE tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                        SET t.status = 'Skipped' 
                        WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'In Progress'");
                        
    // 2. Start this emergency token
    mysqli_query($con, "UPDATE tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                        SET t.status = 'In Progress', t.called_at = NOW() 
                        WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.token_no = $token_no AND t.status IN ('Waiting', 'Skipped') LIMIT 1");
}

$referer = $_SERVER['HTTP_REFERER'] ?? 'doctor.php';
header("Location: $referer");
exit();
?>
