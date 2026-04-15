<?php
require_once "reception-init.php";
$mode = 'offline';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $clinic_id = $_SESSION['clinic_id'] ?? null;

    if (!$clinic_id) {
        $_SESSION['error'] = "Session Error: Clinic ID missing. Please login again.";
        header("Location: register-patient.php");
        exit();
    }

    if ($action == 'add') {
        $name = mysqli_real_escape_string($con, $_POST['full_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $dob = mysqli_real_escape_string($con, $_POST['birthdate']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $address = mysqli_real_escape_string($con, $_POST['address']);

        $doc_id = (int)$_POST['doctor_id'];
        $app_date = mysqli_real_escape_string($con, $_POST['appointment_date']);
        $app_type = mysqli_real_escape_string($con, $_POST['appointment_type']);
        $app_time = date('H:i:s');

        mysqli_begin_transaction($con);
        try {
            // ================= PATIENT INSERT =================
            $p_sql = "INSERT INTO patients 
                (full_name, email, phone, date_of_birth, gender, address) 
                VALUES ('$name', '$email', '$phone', '$dob', '$gender', '$address')";

            if (!mysqli_query($con, $p_sql)) {
                throw new Exception("Patient Error: " . mysqli_error($con));
            }

            $p_id = mysqli_insert_id($con);


            // ================= 🔥 DOCTOR RULE CHECK =================

            // Fetch doctor settings
            $doc_query = mysqli_query($con, "SELECT max_patients_offline, allow_walkins 
                                            FROM doctors 
                                            WHERE doctor_id = $doc_id");

            if (!$doc_query || mysqli_num_rows($doc_query) == 0) {
                throw new Exception("Doctor not found.");
            }

            $doc_data = mysqli_fetch_assoc($doc_query);

            $max_offline   = (int)$doc_data['max_patients_offline'];
            $allow_walkins = (int)$doc_data['allow_walkins'];

            // 🚫 Walk-ins disabled
            if ($allow_walkins == 0) {
                throw new Exception("Doctor has disabled walk-in appointments.");
            }

            // Count today's offline patients
            $offline_q = mysqli_query($con, "SELECT COUNT(*) as total FROM appointments 
                            WHERE doctor_id = $doc_id 
                            AND appointment_date = '$app_date'
                            AND appointment_mode = 'offline'");

            $offline_data = mysqli_fetch_assoc($offline_q);
            $offline_count = (int)$offline_data['total'];
            // 🚫 Limit reached
            if ($offline_count >= $max_offline) {
                throw new Exception("Offline patient limit reached for this doctor.");
            }


            // ================= APPOINTMENT INSERT =================

            $a_sql = "INSERT INTO appointments 
    (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, status, appointment_mode) 
    VALUES ($p_id, $doc_id, $clinic_id, '$app_date', '$app_time', '$app_type', 'Pending', '$mode')";

            if (!mysqli_query($con, $a_sql)) {
                throw new Exception("Appointment Error: " . mysqli_error($con));
            }

            // ✅ NOW get correct appointment id
            $appointment_id = mysqli_insert_id($con);

            $today = $app_date; // use selected date, not current date

            $tokenQ = mysqli_query($con, "
SELECT MAX(t.token_no) as max_token
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doc_id
AND a.appointment_date = '$today'
");

            $data = mysqli_fetch_assoc($tokenQ);
            $nextToken = ($data['max_token'] ?? 0) + 1;

            // ✅ Insert token
            mysqli_query($con, "
INSERT INTO tokens (appointment_id, token_no, queue_position, status)
VALUES ($appointment_id, $nextToken, $nextToken, 'Waiting')
");

            mysqli_commit($con);
            $_SESSION['success'] = "Registration Successful!";
        } catch (Exception $e) {
            mysqli_rollback($con);
            $_SESSION['error'] = $e->getMessage();
        }
    } elseif ($action == 'edit') {

        $p_id = (int)$_POST['patient_id'];
        $fields = ['full_name', 'email', 'phone', 'birthdate', 'gender', 'address'];
        $vals = [];

        foreach ($fields as $f) {
            $vals[$f] = mysqli_real_escape_string($con, $_POST[$f]);
        }

        $sql = "UPDATE patients SET 
            full_name='{$vals['full_name']}', 
            email='{$vals['email']}', 
            phone='{$vals['phone']}', 
            date_of_birth='{$vals['birthdate']}', 
            gender='{$vals['gender']}', 
            address='{$vals['address']}' 
            WHERE patient_id=$p_id";

        if (mysqli_query($con, $sql)) {
            $_SESSION['success'] = "Record Updated!";
        } else {
            $_SESSION['error'] = "Update failed: " . mysqli_error($con);
        }
    } elseif ($action == 'delete') {

        $p_id = (int)$_POST['patient_id'];

        mysqli_query($con, "DELETE FROM appointments WHERE patient_id=$p_id AND clinic_id=$clinic_id");

        if (mysqli_query($con, "DELETE FROM patients WHERE patient_id=$p_id")) {
            $_SESSION['success'] = "Deleted.";
        } else {
            $_SESSION['error'] = "Delete failed.";
        }
    }

    header("Location: register-patient.php");
    exit();
}
