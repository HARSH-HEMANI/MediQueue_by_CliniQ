<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <link rel="stylesheet" href="../css/admin.css?v=vibrant_admin3">
</head>

<body class="layout-with-sidebar">

    <?php include "admin-header.php"; ?>
    <?php include "admin-sidebar.php"; ?>

    <?php
    if (isset($content_page)) {
        echo $content;
    }
    ?>
</body>

</html>