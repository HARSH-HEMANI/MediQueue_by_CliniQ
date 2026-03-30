<?php
require_once "admin-init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error_occured = false;

    foreach ($_POST as $key => $value) {
        $key = mysqli_real_escape_string($con, $key);
        $value = mysqli_real_escape_string($con, $value);

        // We use UPDATE because we assumed rows are already inserted via SQL above
        $query = "UPDATE site_settings SET content_value = '$value' WHERE section_key = '$key'";

        if (!mysqli_query($con, $query)) {
            $error_occured = true;
        }
    }

    if ($error_occured) {
        $_SESSION['admin_error'] = "Some changes could not be saved.";
    } else {
        $_SESSION['admin_success'] = "Homepage content updated successfully!";
    }

    header("Location: manage-site-content.php");
    exit();
}
