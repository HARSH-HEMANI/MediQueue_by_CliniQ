<?php
$current_page = basename($_SERVER['PHP_SELF']);
require_once __DIR__ . '/../db.php';
$patient_id = $_SESSION['patient_id'] ?? 0;

if ($patient_id > 0) {
    $q_pat = mysqli_query($con, "SELECT full_name FROM patients WHERE patient_id = $patient_id");
    if($q_pat && mysqli_num_rows($q_pat) > 0) {
        $pdata = mysqli_fetch_assoc($q_pat);
        $sidebar_patient_name = $pdata['full_name'];
    } else {
        $sidebar_patient_name = 'Patient User';
    }
} else {
    $sidebar_patient_name = 'Patient User';
}
$sidebar_display_id = str_pad($patient_id, 4, '0', STR_PAD_LEFT);
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
                <!-- Use actual generic UI faces avatar with deterministic ID based seed -->
                <!-- <img src="https://i.pravatar.cc/150?u=patient<?php echo $patient_id; ?>" alt="Patient"> -->
                <div class="user-info">
                    <span class="name"><?php echo htmlspecialchars($sidebar_patient_name); ?></span>
                    <span class="tag">#P-<?php echo $sidebar_display_id; ?></span>
                </div>
            </div>
            <a href="../patient/logout.php" class="text-danger fs-5"
                onclick="return confirm('Are you sure you want to logout?');" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

</div>