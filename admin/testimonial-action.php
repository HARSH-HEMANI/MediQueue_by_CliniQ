<?php
require_once "admin-init.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $loc = mysqli_real_escape_string($con, $_POST['location']);
    $feed = mysqli_real_escape_string($con, $_POST['feedback']);
    $rate = (int)$_POST['rating'];

    mysqli_query($con, "INSERT INTO testimonials (patient_name, location, feedback, rating) VALUES ('$name', '$loc', '$feed', $rate)");
    
    $_SESSION['admin_success'] = "Testimonial added!";
    header("Location: manage-testimonials.php");
}