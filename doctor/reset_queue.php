<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

// ❌ Delete existing tokens for this doctor today
mysqli_query($con, "
    DELETE t FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE a.doctor_id = $doctor_id
    AND a.appointment_date = '$today'
");

// 🔢 Recreate tokens in order
$result = mysqli_query($con, "
    SELECT appointment_id 
    FROM appointments 
    WHERE doctor_id = $doctor_id 
    AND appointment_date = '$today'
    ORDER BY appointment_time ASC
");

$token_no = 1;

while ($row = mysqli_fetch_assoc($result)) {

    $status = ($token_no == 1) ? 'In Progress' : 'Waiting';

    mysqli_query($con, "
        INSERT INTO tokens (appointment_id, token_no, queue_position, status)
        VALUES ({$row['appointment_id']}, $token_no, $token_no, '$status')
    ");

    $token_no++;
}

// 🔁 Redirect back
header("Location: doctor.php");
exit();
?>