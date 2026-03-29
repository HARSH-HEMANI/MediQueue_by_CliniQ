<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = $_SESSION['doctor_id'];

if (isset($_POST['status'])) {
    $status = (int) $_POST['status'];

    $query = "UPDATE doctors SET is_active = $status WHERE doctor_id = $doctor_id";

    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}