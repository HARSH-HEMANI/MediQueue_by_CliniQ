<?php
include_once "reception-auth.php";
$content_page = 'Reception Dashboard | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Overview</small>
            <h1 class="dashboard-title mt-1">Reception <span>Dashboard</span></h1>
            <p class="dashboard-subtitle">Today's clinic activity at a glance</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success py-2 px-3">
                <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Live
            </span>
            <span class="text-muted" style="font-size:0.85rem;"><?php echo date('D, d M Y'); ?></span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stats-row mb-4">
        <div class="rstat-card">
            <h6>Today's Appointments</h6>
            <h2>12</h2>
        </div>
        <div class="rstat-card">
            <h6>Patients in Queue</h6>
            <h2>5</h2>
        </div>
        <div class="rstat-card">
            <h6>Doctors Available</h6>
            <h2>3</h2>
        </div>
        <div class="rstat-card">
            <h6>Completed Today</h6>
            <h2>48</h2>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <!-- Today's Appointments -->
        <div class="col-lg-8">
            <div class="rcard h-100">
                <div class="rcard-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Today's Appointments</h5>
                        <a href="manage_appointment.php" class="btn btn-sm btn-outline-secondary rounded-pill">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="r-thead">
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-muted" style="font-size:0.88rem;">10:00 AM</td>
                                    <td><strong>John Doe</strong></td>
                                    <td>Dr. Smith</td>
                                    <td><span class="badge-soft-warning">Waiting</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="font-size:0.88rem;">10:30 AM</td>
                                    <td><strong>Sarah Khan</strong></td>
                                    <td>Dr. Ali</td>
                                    <td><span class="badge-soft-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="font-size:0.88rem;">11:00 AM</td>
                                    <td><strong>Michael Lee</strong></td>
                                    <td>Dr. Smith</td>
                                    <td><span class="badge-soft-primary">Consulting</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Queue -->
        <div class="col-lg-4">
            <div class="rcard h-100">
                <div class="rcard-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Live Queue</h5>
                        <a href="live_queue.php" class="btn btn-sm btn-outline-secondary rounded-pill">Manage</a>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-brand-soft">
                            <div>
                                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:#6b7280;font-weight:700;">Now</div>
                                <strong>John Doe</strong>
                            </div>
                            <span class="badge-soft-primary">Consulting</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border" style="border-color:rgba(0,0,0,0.06)!important;">
                            <div>
                                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:#6b7280;font-weight:700;">Next</div>
                                <strong>Michael Lee</strong>
                            </div>
                            <span class="badge-soft-warning">Waiting</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border" style="border-color:rgba(0,0,0,0.06)!important;">
                            <div>
                                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:#6b7280;font-weight:700;">Upcoming</div>
                                <strong>Sarah Khan</strong>
                            </div>
                            <span class="badge-soft-warning">Waiting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="rcard">
        <div class="rcard-body">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-flex flex-column gap-2">
                <a href="register_patient.php" class="quick-action-btn"><span class="qa-icon"><i class="bi bi-person-plus"></i></span>Register Walk-in Patient</a>
                <a href="manage_appointment.php" class="quick-action-btn"><span class="qa-icon"><i class="bi bi-calendar-check"></i></span>Manage Appointments</a>
                <a href="live_queue.php" class="quick-action-btn"><span class="qa-icon"><i class="bi bi-broadcast"></i></span>View Live Queue</a>
                <a href="patient_list.php" class="quick-action-btn"><span class="qa-icon"><i class="bi bi-people"></i></span>Patient Directory</a>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>