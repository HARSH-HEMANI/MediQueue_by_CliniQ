<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: hospital_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($content_page); ?></title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/reception-sidebar.php'; ?>

    <?php if (isset($content_page)) echo $content; ?>

    <?php include './reception_footer.php'; ?>

    <!-- Scripts at bottom -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>

</body>

</html>