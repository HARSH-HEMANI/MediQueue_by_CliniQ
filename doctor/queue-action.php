<?php
include "doctor-auth.php";
include "../db.php";

$action = $_GET['action'] ?? '';
$token_id = isset($_GET['token_id']) ? (int)$_GET['token_id'] : 0;
$token_no = isset($_GET['token_no']) ? (int)$_GET['token_no'] : 0;

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');


// =============================
// ✅ COMPLETE CURRENT PATIENT
// =============================
if ($action === 'complete') {

    // Complete current token
    mysqli_query($con, "
        UPDATE tokens t
        JOIN appointments a ON t.appointment_id = a.appointment_id
        SET t.status = 'Completed', t.completed_at = NOW()
        WHERE a.doctor_id = $doctor_id
        AND a.appointment_date = '$today'
        AND t.status = 'In Progress'
    ");

    // Start next patient automatically
    $findNext = mysqli_query($con, "
        SELECT t.token_id 
        FROM tokens t
        JOIN appointments a ON t.appointment_id = a.appointment_id
        WHERE a.doctor_id = $doctor_id
        AND a.appointment_date = '$today'
        AND t.status = 'Waiting'
        ORDER BY 
            (a.appointment_type = 'Emergency') DESC,
            t.token_no ASC
        LIMIT 1
    ");

    if ($next = mysqli_fetch_assoc($findNext)) {

        mysqli_query($con, "
            UPDATE tokens 
            SET status = 'In Progress', called_at = NOW()
            WHERE token_id = {$next['token_id']}
        ");
    }
}


// =============================
// ✅ HOLD / SKIP PATIENT
// =============================
elseif ($action === 'skip' && $token_id) {

    mysqli_query($con, "
        UPDATE tokens 
        SET status = 'Waiting'
        WHERE token_id = $token_id
    ");
}


// =============================
// ✅ NEXT PATIENT (SMART)
// =============================
elseif ($action === 'next') {

    // 🔥 Only move if NO one is currently in progress
    $check = mysqli_query($con, "
        SELECT t.token_id
        FROM tokens t
        JOIN appointments a ON t.appointment_id = a.appointment_id
        WHERE a.doctor_id = $doctor_id
        AND a.appointment_date = '$today'
        AND t.status = 'In Progress'
        LIMIT 1
    ");

    if (mysqli_num_rows($check) == 0) {

        // Start next patient
        $findNext = mysqli_query($con, "
            SELECT t.token_id 
            FROM tokens t
            JOIN appointments a ON t.appointment_id = a.appointment_id
            WHERE a.doctor_id = $doctor_id
            AND a.appointment_date = '$today'
            AND t.status = 'Waiting'
            ORDER BY 
                (a.appointment_type = 'Emergency') DESC,
                t.token_no ASC
            LIMIT 1
        ");

        if ($next = mysqli_fetch_assoc($findNext)) {

            mysqli_query($con, "
                UPDATE tokens 
                SET status = 'In Progress', called_at = NOW()
                WHERE token_id = {$next['token_id']}
            ");
        }
    }
}


// =============================
// ✅ CALL EMERGENCY
// =============================
elseif ($action === 'call_emergency' && $token_no) {

    // Put current on hold
    mysqli_query($con, "
        UPDATE tokens t 
        JOIN appointments a ON t.appointment_id = a.appointment_id 
        SET t.status = 'Waiting'
        WHERE a.doctor_id = $doctor_id
        AND a.appointment_date = '$today'
        AND t.status = 'In Progress'
    ");

    // Start emergency
    mysqli_query($con, "
        UPDATE tokens t 
        JOIN appointments a ON t.appointment_id = a.appointment_id 
        SET t.status = 'In Progress', t.called_at = NOW()
        WHERE a.doctor_id = $doctor_id
        AND a.appointment_date = '$today'
        AND t.token_no = $token_no
        AND t.status = 'Waiting'
        LIMIT 1
    ");
}


// =============================
// 🔁 REDIRECT BACK
// =============================
$referer = $_SERVER['HTTP_REFERER'] ?? 'doctor.php';
header("Location: $referer");
exit();
