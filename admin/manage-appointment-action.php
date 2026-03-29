<?php
require_once "admin-init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'] ?? '';

    // ==========================================
    // 1. ADD NEW APPOINTMENT
    // ==========================================
    if ($action == 'add') {
        $patient_id = (int)$_POST['patient_id'];
        $doctor_id = (int)$_POST['doctor_id'];
        $clinic_id = (int)$_POST['clinic_id'];

        $date = mysqli_real_escape_string($con, $_POST['appointment_date']);
        $time = mysqli_real_escape_string($con, $_POST['appointment_time']);
        $type = mysqli_real_escape_string($con, $_POST['appointment_type']);
        $reason = mysqli_real_escape_string($con, $_POST['visit_reason'] ?? '');

        // Insert default status as 'Pending' as per DB schema
        $query = "INSERT INTO appointments (patient_id, doctor_id, clinic_id, appointment_date, appointment_time, appointment_type, visit_reason, status) 
                  VALUES ($patient_id, $doctor_id, $clinic_id, '$date', '$time', '$type', '$reason', 'Pending')";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Appointment scheduled successfully as Pending!";
        } else {
            $_SESSION['admin_error'] = "Error scheduling appointment: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 2. EDIT APPOINTMENT
    // ==========================================
    elseif ($action == 'edit') {
        $appointment_id = (int)$_POST['appointment_id'];

        $doctor_id = (int)$_POST['doctor_id'];
        $clinic_id = (int)$_POST['clinic_id'];
        $date = mysqli_real_escape_string($con, $_POST['appointment_date']);
        $time = mysqli_real_escape_string($con, $_POST['appointment_time']);
        $type = mysqli_real_escape_string($con, $_POST['appointment_type']);
        $reason = mysqli_real_escape_string($con, $_POST['visit_reason'] ?? '');
        $status = mysqli_real_escape_string($con, $_POST['status']);

        $query = "UPDATE appointments SET 
                    doctor_id = $doctor_id,
                    clinic_id = $clinic_id,
                    appointment_date = '$date',
                    appointment_time = '$time',
                    appointment_type = '$type',
                    visit_reason = '$reason',
                    status = '$status'
                  WHERE appointment_id = $appointment_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Appointment details updated successfully!";
        } else {
            $_SESSION['admin_error'] = "Error updating appointment: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 3. QUICK UPDATE STATUS (e.g., Cancel)
    // ==========================================
    elseif ($action == 'update_status') {
        $appointment_id = (int)$_POST['appointment_id'];
        $status = mysqli_real_escape_string($con, $_POST['status']);

        $query = "UPDATE appointments SET status = '$status' WHERE appointment_id = $appointment_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Appointment marked as " . $status . "!";
        } else {
            $_SESSION['admin_error'] = "Error updating status: " . mysqli_error($con);
        }
    }

    // ==========================================
    // 4. DELETE APPOINTMENT
    // ==========================================
    elseif ($action == 'delete') {
        $appointment_id = (int)$_POST['appointment_id'];

        $query = "DELETE FROM appointments WHERE appointment_id = $appointment_id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "Appointment completely removed from the database.";
        } else {
            $_SESSION['admin_error'] = "Error deleting appointment.";
        }
    } else {
        $_SESSION['admin_error'] = "Invalid action requested.";
    }

    // Redirect back to the UI page
    header("Location: appointments.php");
    exit();
} else {
    header("Location: appointments.php");
    exit();
}
