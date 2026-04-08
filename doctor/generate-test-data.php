<?php
include "../db.php";

session_start();
$doctor_id = $_SESSION['doctor_id']; // change if needed
$clinic_id = 1;
$date = date('Y-m-d');

/* ================================
   🔥 CLEAR OLD DATA (optional)
================================ */
mysqli_query($con, "DELETE FROM tokens");
mysqli_query($con, "DELETE FROM appointments");

/* ================================
   🔥 FETCH REAL PATIENT IDS
================================ */
$patients = [];
$resPatients = mysqli_query($con, "SELECT patient_id FROM patients");

while ($p = mysqli_fetch_assoc($resPatients)) {
    $patients[] = $p['patient_id'];
}

// ❗ Safety check
if (count($patients) == 0) {
    die("❌ No patients found in database!");
}

/* ================================
   🔥 CREATE APPOINTMENTS
================================ */
for ($i = 1; $i <= 20; $i++) {

    $time = date("H:i:s", strtotime("09:00:00 +$i minutes"));

    $types = ['New','Follow Up','Emergency'];
    $type = $types[array_rand($types)];

    // ✅ USE REAL PATIENT ID
    $patient_id = $patients[array_rand($patients)];

    mysqli_query($con, "
        INSERT INTO appointments 
        (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, status)
        VALUES 
        ($patient_id, $doctor_id, $clinic_id, '$date', '$time', '$type', 'Confirmed')
    ");
}

/* ================================
   🔥 FETCH APPOINTMENTS
================================ */
$res = mysqli_query($con, "SELECT appointment_id, appointment_time FROM appointments");

$token_no = 1;

/* ================================
   🔥 GENERATE TOKENS + DELAYS
================================ */
while ($row = mysqli_fetch_assoc($res)) {

    // First half completed, rest waiting
    $status = ($token_no <= 10) ? 'Completed' : 'Waiting';

    $called = null;
    $completed = null;

    if ($status === 'Completed') {

        // Random delay (0–20 min)
        $delay = rand(0, 20);

        $called = date("Y-m-d H:i:s", strtotime(
            $date . " " . $row['appointment_time'] . " +$delay minutes"
        ));

        $completed = date("Y-m-d H:i:s", strtotime(
            $called . " +" . rand(5,15) . " minutes"
        ));
    }

    mysqli_query($con, "
        INSERT INTO tokens 
        (appointment_id, token_no, queue_position, status, called_at, completed_at)
        VALUES 
        ({$row['appointment_id']}, $token_no, $token_no, '$status', 
        " . ($called ? "'$called'" : "NULL") . ", 
        " . ($completed ? "'$completed'" : "NULL") . ")
    ");

    $token_no++;
}

echo "✅ Test Data Generated Successfully!";
?>