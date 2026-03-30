<?php
require_once "admin-init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    $q = mysqli_real_escape_string($con, $_POST['question']);
    $a = mysqli_real_escape_string($con, $_POST['answer']);
    $order = (int)$_POST['sort_order'];

    if ($action === 'add') {
        // --- ADD LOGIC ---
        $query = "INSERT INTO faqs (question, answer, sort_order) VALUES ('$q', '$a', $order)";
        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "FAQ added successfully!";
        } else {
            $_SESSION['admin_error'] = "Failed to add FAQ.";
        }
    } elseif ($action === 'edit') {
        // --- EDIT LOGIC ---
        $id = (int)$_POST['faq_id'];
        $query = "UPDATE faqs SET question = '$q', answer = '$a', sort_order = $order WHERE id = $id";

        if (mysqli_query($con, $query)) {
            $_SESSION['admin_success'] = "FAQ updated successfully!";
        } else {
            $_SESSION['admin_error'] = "Failed to update FAQ.";
        }
    }

    header("Location: manage-faqs.php");
    exit();
}
