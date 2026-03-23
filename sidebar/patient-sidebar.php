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
<div class="offcanvas-lg offcanvas-start custom-sidebar" tabindex="-1" id="appSidebar" data-bs-backdrop="false" data-bs-scroll="true">

    <!-- Mobile header inside offcanvas -->
    <div class="offcanvas-header d-lg-none">
        <div class="sidebar-logo">
            <img src="../img/mediq.png" alt="MediQueue Logo">
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar"></button>
    </div>

    <!-- Desktop logo -->
    <div class="d-none d-lg-block">
        <div class="sidebar-logo" style="padding:20px 20px 16px;">
            <img src="../img/mediq.png" alt="MediQueue Logo">
        </div>
    </div>

    <!-- Nav -->
    <div class="sidebar-menu offcanvas-body flex-column p-0">
        <div class="w-100 p-3">
            <div class="menu-label">NAVIGATION</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="../patient/dashboard.php" class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/book_appointment.php" class="nav-link <?= ($current_page == 'book_appointment.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-plus-fill"></i><span>Book Appointment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/live_queue.php" class="nav-link <?= ($current_page == 'live_queue.php') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i><span>Live Queue</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/my_appointment.php" class="nav-link <?= ($current_page == 'my_appointment.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-check-fill"></i><span>My Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/prescription.php" class="nav-link <?= ($current_page == 'prescription.php') ? 'active' : '' ?>">
                        <i class="bi bi-file-medical-fill"></i><span>Prescriptions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/visit_history.php" class="nav-link <?= ($current_page == 'visit_history.php') ? 'active' : '' ?>">
                        <i class="bi bi-clock-history"></i><span>Visit History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../patient/profile_setting.php" class="nav-link <?= ($current_page == 'profile_setting.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-fill-gear"></i><span>Profile Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- User account -->
    <div class="user-account mt-auto">
        <div class="menu-label text-uppercase mb-2">USER ACCOUNT</div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u=a04258" alt="Patient">
                <div class="user-info">
                    <span class="name">Patient User</span>
                    <span class="tag">#patient-1234</span>
                </div>
            </div>
            <a href="../patient/logout.php" class="text-danger fs-5"
                onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

</div>