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
<div class="offcanvas-lg offcanvas-start custom-sidebar" tabindex="-1" id="appSidebar" data-bs-scroll="true">

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
                    <a href="../reception/reception_dashboard.php" class="nav-link <?= ($current_page == 'reception_dashboard.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/register-patient.php" class="nav-link <?= ($current_page == 'register-patient.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-plus-fill"></i><span>Register Patient</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../reception/manage_appointment.php" class="nav-link <?= ($current_page == 'manage_appointment.php') ? 'active' : '' ?>">
                        <i class="bi bi-calendar-event-fill"></i><span>Manage Appointments</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="../reception/patient_list.php" class="nav-link <?= ($current_page == 'patient_list.php') ? 'active' : '' ?>">
                        <i class="bi bi-card-list"></i><span>Patient Directory</span>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="../reception/live_queue.php" class="nav-link <?= ($current_page == 'live_queue.php') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i><span>Live Queue</span>
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
                <!-- <img src="https://i.pravatar.cc/150?u=rec" alt="Receptionist"> -->
                <div class="user-info">
                    <span class="name"><?php echo isset($_SESSION['receptionist_name']) ? htmlspecialchars($_SESSION['receptionist_name']) : 'Receptionist'; ?></span>
                    <span class="tag">#REC-<?php echo isset($_SESSION['receptionist_id']) ? str_pad($_SESSION['receptionist_id'], 4, '0', STR_PAD_LEFT) : '0000'; ?></span>
                </div>
            </div>
            <a href="../reception/logout.php" class="text-danger fs-5"
                onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

</div>