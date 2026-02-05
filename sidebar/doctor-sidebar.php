<nav class="navbar fixed-top navbar-expand-lg" id="nav-home">
    <div class="container-fluid px-4">

        <button class="btn p-0 me-3"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#doctorSidebar">
            <i class="bi bi-list fs-2"></i>
        </button>

        <a class="navbar-brand" href="./index.php">
            <img src="./img/mediq.png" alt="MediQueue Logo" style="height:60px;">
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="./doctor.php">Doctor Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="./contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="doctorSidebar">
    <div class="offcanvas-header border-bottom" style="background:#fef9f9;">
        <img src="./img/mediq.png" style="height:65px;" alt="MediQueue">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-0" style="background:#fef9f9;">

        <ul class="list-group list-group-flush px-3 py-3">
            <li class="list-group-item bg-transparent border-0">
                <a href="./doctor.php" class="nav-link active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="list-group-item bg-transparent border-0">
                <a href="./appointments.php" class="nav-link">
                    <i class="bi bi-calendar-check me-2"></i> Appointments
                </a>
            </li>

            <li class="list-group-item bg-transparent border-0">
                <a href="./queue.php" class="nav-link">
                    <i class="bi bi-ticket-perforated me-2"></i> Live Queue
                </a>
            </li>

            <li class="list-group-item bg-transparent border-0">
                <a href="./patients.php" class="nav-link">
                    <i class="bi bi-person-lines-fill me-2"></i> Patient Records
                </a>
            </li>

            <li class="list-group-item bg-transparent border-0">
                <a href="./analytics.php" class="nav-link">
                    <i class="bi bi-graph-up-arrow me-2"></i> Consultation Analytics
                </a>
            </li>

            <li class="list-group-item bg-transparent border-0">
                <a href="./profile.php" class="nav-link">
                    <i class="bi bi-gear me-2"></i> Profile Settings
                </a>
            </li>
        </ul>

        <div class="border-top px-4 py-3 mt-3">
            <a href="./logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>
</div>