<?php
require_once "admin-init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'] ?? '';

    // ==========================================
    // 1. ADD NEW PATIENT
    // ==========================================
    if ($action == 'add') {
        $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $dob = !empty($_POST['date_of_birth']) ? "'" . mysqli_real_escape_string($con, $_POST['date_of_birth']) . "'" : "NULL";
        $blood_group = mysqli_real_escape_string($con, $_POST['blood_group']);

        $address = mysqli_real_escape_string($con, $_POST['address']);
        $pincode = mysqli_real_escape_string($con, $_POST['pincode']);

        $em_name = mysqli_real_escape_string($con, $_POST['emergency_contact_name']);
        $em_rel = mysqli_real_escape_string($con, $_POST['emergency_contact_relation']);
        $em_phone = mysqli_real_escape_string($con, $_POST['emergency_contact_phone']);

        $query = "INSERT INTO patients (
                    full_name, phone, email, password, gender, date_of_birth, 
                    blood_group, address, pincode, 
                    emergency_contact_name, emergency_contact_relation, emergency_contact_phone, is_active
                  ) VALUES (
                    '$full_name', '$phone', '$email', '$password', '$gender', $dob, 
                    '$blood_group', '$address', '$pincode', 
                    '$em_name', '$em_rel', '$em_phone', 1
                  )";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Patient registered successfully!";
        } else {
            $_SESSION['admin_error'] = "Error adding patient: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 2. EDIT / UPDATE PATIENT
    // ==========================================
    elseif ($action == 'edit') {
        $patient_id = (int)$_POST['patient_id'];

        $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $dob = !empty($_POST['date_of_birth']) ? "'" . mysqli_real_escape_string($con, $_POST['date_of_birth']) . "'" : "NULL";
        $blood_group = mysqli_real_escape_string($con, $_POST['blood_group']);

        $address = mysqli_real_escape_string($con, $_POST['address']);
        $pincode = mysqli_real_escape_string($con, $_POST['pincode']);

        $em_name = mysqli_real_escape_string($con, $_POST['emergency_contact_name']);
        $em_rel = mysqli_real_escape_string($con, $_POST['emergency_contact_relation']);
        $em_phone = mysqli_real_escape_string($con, $_POST['emergency_contact_phone']);

        $query = "UPDATE patients SET 
                    full_name = '$full_name',
                    phone = '$phone',
                    email = '$email',
                    gender = '$gender',
                    date_of_birth = $dob,
                    blood_group = '$blood_group',
                    address = '$address',
                    pincode = '$pincode',
                    emergency_contact_name = '$em_name',
                    emergency_contact_relation = '$em_rel',
                    emergency_contact_phone = '$em_phone'";

        if (!empty($_POST['password'])) {
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query .= ", password = '$hashed_password'";
        }

        $query .= " WHERE patient_id = $patient_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Patient details updated successfully!";
        } else {
            $_SESSION['admin_error'] = "Error updating patient: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 3. TOGGLE STATUS (Active / Inactive)
    // ==========================================
    elseif ($action == 'toggle_status') {
        $patient_id = (int)$_POST['patient_id'];

        $query = "UPDATE patients SET is_active = NOT is_active WHERE patient_id = $patient_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Patient status updated successfully!";
        } else {
            $_SESSION['admin_error'] = "Error updating status: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 4. DELETE PATIENT
    // ==========================================
    elseif ($action == 'delete') {
        $patient_id = (int)$_POST['patient_id'];

        $query = "DELETE FROM patients WHERE patient_id = $patient_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Patient completely removed from the system.";
        } else {
            $_SESSION['admin_error'] = "Error deleting patient. They might be linked to existing appointments.";
        }
    } else {
        $_SESSION['admin_error'] = "Invalid action requested.";
    }

    header("Location: patient-management.php");
    exit();
} else {
    header("Location: patient-management.php");
    exit();
}
