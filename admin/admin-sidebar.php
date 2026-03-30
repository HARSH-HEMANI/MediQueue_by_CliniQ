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
            <!-- <img src="../img/mediq.png" alt="Logo"> -->
        </div>
    </div>

    <div class="sidebar-menu offcanvas-body flex-column p-0">
        <div class="w-100 p-3">
            <div class="menu-label">NAVIGATION</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="./admin.php" class="nav-link <?= ($current_page == 'admin.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./doctor.php" class="nav-link <?= ($current_page == 'doctor.php') ? 'active' : '' ?>">
                        <i class="bi bi-heart-pulse-fill"></i>
                        <span>Doctor Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./staff.php" class="nav-link <?= ($current_page == 'staff.php') ? 'active' : '' ?>">
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Staff Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./clinic.php" class="nav-link <?= ($current_page == 'clinic.php') ? 'active' : '' ?>">
                        <i class="bi bi-building-fill"></i>
                        <span>Clinic Management</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label mt-3">OPERATIONS</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="./patient-management.php" class="nav-link <?= ($current_page == 'patient-management.php') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./appointments.php" class="nav-link <?= ($current_page == 'appointments.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-range-fill"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./queues.php" class="nav-link <?= ($current_page == 'queues.php') ? 'active' : '' ?>">
                        <i class="bi bi-sort-numeric-down"></i>
                        <span>Live Queues</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="./admin-messages.php" class="nav-link <?= ($content_page == 'admin-messages') ? 'active' : '' ?>">
                        <i class="bi bi-envelope"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>
            </ul>

            <div class="menu-label mt-3">SYSTEM</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="./analytics.php" class="nav-link <?= ($current_page == 'analytics.php') ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="./manage-site-content.php" class="nav-link <?= ($current_page == 'manage-site-content.php') ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill"></i>
                        <span>Site Content</span>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="./settings.php" class="nav-link <?= ($current_page == 'settings.php') ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill"></i>
                        <span>System Settings</span>
                    </a>
                </li> -->
            </ul>
        </div>
    </div>

    <div class="user-account mt-auto">
        <div class="menu-label text-uppercase mb-2">USER ACCOUNT</div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="user-profile">
                <div class="user-info">
                    <span class="name">System Admin</span>
                    <span class="tag">#admin-001</span>
                </div>
            </div>
            <a href="./admin-logout.php" class="text-danger fs-5" onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>