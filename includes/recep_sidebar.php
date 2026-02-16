    <nav class="navbar fixed-top navbar-expand-lg" id="nav-home">
        <div class="container-fluid px-4">
            <button class="px-5" type="button" data-bs-toggle="offcanvas" id="menu" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <i class="bi bi-list fs-2 fw-bolder"></i>
            </button>
            <!-- Logo -->
            <a class="navbar-brand" href="../index.php">
                <img src="../img/mediq.png" alt="MediQ Logo" style="height:60px;">
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Center Menu -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link " href="./index1.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./doctor/doctor.php">Doctor</a></li>
                    <li class="nav-item"><a class="nav-link active" href="./reception.php">Hospital</a></li>
                    <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="./contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="./faq.php">FAQs</a></li>
                </ul>

                <!-- Right Buttons -->
                <div class="d-flex">

                </div>

            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header" style="background-color: #fef9f9;">
            <img src="../img/mediq.png" style="height: 70px;" class="mx-5" alt="">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" style="background-color: #fef9f9;">
            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/dashboard.php" type="none" class="nav-link">Dashboard</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/register_patient.php" type="none" class="nav-link">Register Patient</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/manage_appointments.php" type="none" class="nav-link">Manage Appointment</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/patient_list.php" type="none" class="nav-link">Patient List</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/live_queue_status.php" type="none" class="nav-link">Live Queue Status</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="../reception/profile_setting.php" type="none" class="nav-link">Profile Settings</a>
            </div>

            <div class="navbar-nav mb-2 px-5 text-lg-center">
                <a href="#"
                    class="nav-link text-danger"
                    onclick="return confirm('Are you sure you want to logout?');">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>