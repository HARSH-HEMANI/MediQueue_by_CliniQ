<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | MediQueue</title>

    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>


<body>

    <?php include "./header.php"; ?>

    <!-- HERO -->
    <section class="explore-hero">
        <div class="container">
            <h1 class="fw-bold">
                Explore <span style="color:#FF5A5F;">MediQueue</span>
            </h1>
            <p class="mt-3 text-muted">
                A structured, role-based smart clinic management system.
            </p>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="explore-section">
        <div class="container text-center">
            <h2 class="section-title">System Workflow</h2>
            <div class="section-divider"></div>
        </div>

        <div class="container">

            <div class="workflow-box">
                <div class="workflow-step">Step 1</div>
                Patient registers appointment (online or walk-in).
            </div>

            <div class="workflow-box">
                <div class="workflow-step">Step 2</div>
                Receptionist assigns token and manages the live queue.
            </div>

            <div class="workflow-box">
                <div class="workflow-step">Step 3</div>
                Doctor consults patient and updates consultation status.
            </div>

            <div class="workflow-box">
                <div class="workflow-step">Step 4</div>
                System records consultation time and visit details.
            </div>

            <div class="workflow-box">
                <div class="workflow-step">Step 5</div>
                Admin monitors performance, analytics, and system health.
            </div>

        </div>
    </section>

    <!-- ROLE BASED MODULES -->
    <section class="explore-section">
        <div class="container text-center">
            <h2 class="section-title">Role-Based Modules</h2>
            <div class="section-divider"></div>
            <p class="text-muted">
                MediQueue divides responsibilities across secure user roles.
            </p>
        </div>

        <div class="container mt-4">
            <div class="row">

                <div class="col-md-4 mb-4">
                    <div class="module-card">
                        <h5 class="fw-bold">Doctor Module</h5>
                        <ul class="mt-3">
                            <li>Live queue management</li>
                            <li>Consultation tracking</li>
                            <li>Patient record access</li>
                            <li>Performance insights</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="module-card">
                        <h5 class="fw-bold">Receptionist Module</h5>
                        <ul class="mt-3">
                            <li>Walk-in registration</li>
                            <li>Appointment booking</li>
                            <li>Token generation</li>
                            <li>Queue updates</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="module-card">
                        <h5 class="fw-bold">Admin Module</h5>
                        <ul class="mt-3">
                            <li>Doctor & clinic management</li>
                            <li>System analytics</li>
                            <li>Audit logs</li>
                            <li>System configuration</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ARCHITECTURE -->
    <section class="explore-section">
        <div class="container text-center">
            <h2 class="section-title">System Architecture</h2>
            <div class="section-divider"></div>
        </div>

        <div class="container">
            <div class="architecture-box">
                <p>
                    MediQueue follows a modular architecture with session-based
                    authentication and strict role-based access control.
                    Each user interacts only with authorized modules,
                    ensuring operational clarity and data security.
                </p>

                <p class="mb-0">
                    The centralized system maintains consistent workflow
                    across clinics while enabling scalable management.
                </p>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="cta-section">
        <div class="container">
            <h4 class="fw-bold mb-3">Ready to Experience MediQueue?</h4>
            <a href="index.php" class="hero-btn">Back to Home</a>
        </div>
    </section>

    <?php include "./footer.php"; ?>

</body>

</html>