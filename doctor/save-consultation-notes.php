<?php
include "doctor-auth.php";
include "../db.php";

$appointment_id = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0;
$diagnosis = isset($_POST['diagnosis']) ? trim($_POST['diagnosis']) : '';
$note_text = isset($_POST['note_text']) ? trim($_POST['note_text']) : '';
$medicines = isset($_POST['medicines']) ? trim($_POST['medicines']) : '';
$follow_up_date = !empty($_POST['follow_up_date']) ? $_POST['follow_up_date'] : null;

if ($appointment_id) {
    // Check if a note already exists for this appointment
    $checkQ = mysqli_query($con, "SELECT note_id FROM consultation_notes WHERE appointment_id = " . $appointment_id);
    if (mysqli_num_rows($checkQ) > 0) {
        // Update
        $stmt = $con->prepare("UPDATE consultation_notes SET diagnosis=?, note_text=?, medicines=?, follow_up_date=? WHERE appointment_id=?");
        $stmt->bind_param("ssssi", $diagnosis, $note_text, $medicines, $follow_up_date, $appointment_id);
        $stmt->execute();
    } else {
        // Insert
        $stmt = $con->prepare("INSERT INTO consultation_notes (appointment_id, diagnosis, note_text, medicines, follow_up_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $appointment_id, $diagnosis, $note_text, $medicines, $follow_up_date);
        $stmt->execute();
    }
}

$referer = $_SERVER['HTTP_REFERER'] ?? 'appointment.php';
header("Location: $referer");
exit();
?>
