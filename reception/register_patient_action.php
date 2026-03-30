<?php
require_once "reception-init.php";

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
            // Patient Insert
            $p_sql = "INSERT INTO patients (full_name, email, phone, date_of_birth, gender, address) VALUES ('$name', '$email', '$phone', '$dob', '$gender', '$address')";
            if (!mysqli_query($con, $p_sql)) throw new Exception("Patient Error: " . mysqli_error($con));

            $p_id = mysqli_insert_id($con);

            // Appointment Insert (Using 'Pending' as requested by earlier DB state)
            $a_sql = "INSERT INTO appointments (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, status) VALUES ($p_id, $doc_id, $clinic_id, '$app_date', '$app_time', '$app_type', 'Pending')";
            if (!mysqli_query($con, $a_sql)) throw new Exception("Appointment Error: " . mysqli_error($con));

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
        foreach ($fields as $f) $vals[$f] = mysqli_real_escape_string($con, $_POST[$f]);

        $sql = "UPDATE patients SET full_name='{$vals['full_name']}', email='{$vals['email']}', phone='{$vals['phone']}', date_of_birth='{$vals['birthdate']}', gender='{$vals['gender']}', address='{$vals['address']}' WHERE patient_id=$p_id";

        if (mysqli_query($con, $sql)) $_SESSION['success'] = "Record Updated!";
        else $_SESSION['error'] = "Update failed: " . mysqli_error($con);
    } elseif ($action == 'delete') {
        $p_id = (int)$_POST['patient_id'];
        mysqli_query($con, "DELETE FROM appointments WHERE patient_id=$p_id AND clinic_id=$clinic_id");
        if (mysqli_query($con, "DELETE FROM patients WHERE patient_id=$p_id")) $_SESSION['success'] = "Deleted.";
        else $_SESSION['error'] = "Delete failed.";
    }

    header("Location: register-patient.php");
    exit();
}
