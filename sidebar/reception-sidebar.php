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
                    <a href="../reception/reception_dashboard.php" class="nav-link <?= ($current_page == 'reception_dashboard.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/register_patient.php" class="nav-link <?= ($current_page == 'register_patient.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Register Patient</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/manage_appointment.php" class="nav-link <?= ($current_page == 'manage_appointment.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Manage Appointment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/patient_list.php" class="nav-link <?= ($current_page == 'patient_list.php') ? 'active' : '' ?>">
                        <i class="bi bi-card-list"></i>
                        <span>Patient List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/live_queue.php" class="nav-link <?= ($current_page == 'live_queue.php') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Live Queue Status</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/profile_setting.php" class="nav-link <?= ($current_page == 'profile_setting.php') ? 'active' : '' ?>">
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
                <img src="https://i.pravatar.cc/150?u=rec" alt="Reception">
                <div class="user-info">
                    <span class="name">Receptionist</span>
                    <span class="tag">#rec-789</span>
                </div>
            </div>
            <a href="../reception/logout.php" class="text-danger fs-5" onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>