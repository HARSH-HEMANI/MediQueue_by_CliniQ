<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $content_page; ?></title>
    <link rel="stylesheet" href="../css/patient.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/footer.css?v=vibrant">
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <style>
        @media print {

            /* Hide everything */
            body * {
                visibility: hidden !important;
            }

            /* Show only prescription modal */
            #prescriptionModal,
            #prescriptionModal * {
                visibility: visible !important;
            }

            /* Full page layout */
            #prescriptionModal {
                position: absolute !important;
                top: 0;
                left: 0;
                width: 100% !important;
                height: auto !important;
                background: #fff !important;
                padding: 30px !important;
                margin: 0 !important;
                overflow: visible !important;
            }

            /* Remove modal restrictions */
            .modal-dialog {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-content {
                width: 100% !important;
                border: none !important;
                box-shadow: none !important;
            }

            /* Hide unnecessary UI */
            .modal-header,
            .modal-footer,
            .btn,
            button {
                display: none !important;
            }

            /* Fix scrolling cut issue */
            .modal-body {
                overflow: visible !important;
                height: auto !important;
            }

            /* Fix background visibility */
            .bg-brand-soft {
                background: #fff !important;
                border: 1px solid #ccc !important;
            }

            /* Ensure text visibility */
            body,
            p,
            span,
            li {
                color: #000 !important;
            }

            /* Prevent content breaking */
            ul,
            li,
            p,
            div {
                page-break-inside: avoid !important;
            }

            /* Improve spacing */
            hr {
                border-top: 1px solid #ccc !important;
            }

            /* Remove shadows/extra UI */
            * {
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body class="layout-with-sidebar">
    <?php include '../sidebar/patient-sidebar.php'; ?>

    <?php
    if (isset($content_page)) {
        echo $content;
    }
    ?>


    <?php include '../footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>
</body>

</html>