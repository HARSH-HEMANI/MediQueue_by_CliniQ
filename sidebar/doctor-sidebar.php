<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Mobile Header -->
<div class="mobile-navbar d-lg-none">
    <div class="d-flex align-items-center gap-3">
        <button class="btn-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar">
            <i class="bi bi-list"></i>
        </button>
        <a href="../index.php" class="text-decoration-none">
            <span class="fs-5 fw-bold">MediQueue</span>
        </a>
    </div>
</div>

<!-- Sidebar -->
<div class="offcanvas-lg offcanvas-start custom-sidebar" tabindex="-1" id="appSidebar">
    <div class="offcanvas-header d-lg-none border-bottom border-secondary">
        <div class="sidebar-logo">
            <span class="fs-5 fw-bold text-dark">MediQueue</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar"></button>
    </div>
    
    <div class="d-none d-lg-block">
        <div class="sidebar-logo">
           <img src="../img/mediq.png" alt="Logo"> 
        </div>
    </div>

    <div class="sidebar-menu offcanvas-body flex-column p-0">
        <div class="w-100 p-3">
            <div class="menu-label">NAVIGATION</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="../doctor/doctor.php" class="nav-link <?= ($current_page == 'doctor.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../doctor/appointment.php" class="nav-link <?= ($current_page == 'appointment.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../doctor/live-queue.php" class="nav-link <?= ($current_page == 'live-queue.php') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Live Queue</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../doctor/patient-records.php" class="nav-link <?= ($current_page == 'patient-records.php') ? 'active' : '' ?>">
                        <i class="bi bi-folder-fill"></i>
                        <span>Patient Records</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../doctor/analytics.php" class="nav-link <?= ($current_page == 'analytics.php') ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Consultation Time Analytics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../doctor/doctor-profile.php" class="nav-link <?= ($current_page == 'doctor-profile.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-fill-gear"></i>
                        <span>Profile Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="user-account mt-auto">
        <div class="menu-label text-uppercase mb-2">USER ACCOUNT</div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u=doc" alt="Doctor">
                <div class="user-info">
                    <span class="name">Dr. <?php echo isset($_SESSION['doctor_name']) ? $_SESSION['doctor_name'] : 'Doctor'; ?></span>
                    <span class="tag">#doc-1234</span>
                </div>
            </div>
            <a href="../doctor/logout.php" class="text-danger fs-5" onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>