<!-- <?php include "admin-auth.php"; ?> -->
<!DOCTYPE html>
<html>

<head>
    <title>Doctor Management</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php include "admin-header.php"; ?>
    <?php include "admin-sidebar.php"; ?>

    <main class="features" style="margin-top:50px;">
        <div class="container">

            <div class="features-header text-center">
                <h2>Doctor <span>Management</span></h2>
                <div class="section-divider"></div>
            </div>

            <div class="feature-card">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Clinic</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dr. Raj Patel</td>
                            <td>Cardiology</td>
                            <td>Main Clinic</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger">
                                    Deactivate
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

</body>

</html>