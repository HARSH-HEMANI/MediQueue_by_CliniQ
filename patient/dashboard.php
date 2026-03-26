<?php
$content_page = 'Patient Dashboard | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Welcome back</small>
            <h3 class="fw-bold mb-0 mt-1">John Doe <span class="text-brand">👋</span></h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="text" id="dashboardSearch" class="form-control rounded-pill"
                style="width:220px;" placeholder="Search appointments...">

            <div class="dropdown">
                <button class="notif-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                    <li><a class="dropdown-item rounded-2 py-2"><i class="bi bi-check-circle text-success me-2"></i>Appointment Confirmed</a></li>
                    <li><a class="dropdown-item rounded-2 py-2"><i class="bi bi-file-earmark-text text-brand me-2"></i>New Prescription Added</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <div class="user-chip" data-bs-toggle="dropdown">
                    <img src="https://i.pravatar.cc/40" alt="Profile">
                    <span class="fw-semibold" style="font-size:0.87rem;">John Doe</span>
                    <i class="bi bi-chevron-down text-muted" style="font-size:0.7rem;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                    <li><a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="profile_setting.php"><i class="bi bi-person"></i>My Profile</a></li>
                    <li><a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="profile_setting.php"><i class="bi bi-gear"></i>Settings</a></li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li><a class="dropdown-item rounded-2 py-2 text-danger d-flex align-items-center gap-2" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-xl-8">

            <!-- Upcoming Appointment -->
            <div class="upcoming-appt mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <span class="section-label">Next Appointment</span>
                        <p class="fw-bold fs-5 mb-1">Dr. Sarah Wilson</p>
                        <p class="text-muted mb-1" style="font-size:0.85rem;"><i class="bi bi-heart-pulse me-1 text-brand"></i>Cardiologist &nbsp;·&nbsp; CityCare Hospital</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;"><i class="bi bi-calendar3 me-1"></i>20 Feb 2026 &nbsp;·&nbsp; <i class="bi bi-clock me-1"></i>10:30 AM</p>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span class="badge-soft-success">Confirmed</span>
                        <button class="btn btn-brand btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Appointment History -->
            <div class="p-card">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0">Appointment History</h6>
                    <a href="my_appointment.php" class="btn btn-outline-secondary btn-sm rounded-pill">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Date</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Doctor</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Department</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10 Jan 2026</td>
                                <td><strong>Dr. Smith</strong></td>
                                <td>Dental</td>
                                <td><span class="badge-soft-success">Completed</span></td>
                                <td><button class="btn btn-outline-secondary btn-sm rounded-pill view-history">View</button></td>
                            </tr>
                            <tr>
                                <td>22 Dec 2025</td>
                                <td><strong>Dr. Ray</strong></td>
                                <td>Orthopedic</td>
                                <td><span class="badge-soft-warning">Pending</span></td>
                                <td><button class="btn btn-outline-secondary btn-sm rounded-pill view-history">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-xl-4">

            <!-- Health Summary -->
            <div class="p-card mb-4">
                <h6 class="fw-bold mb-3">Health Summary</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-box">
                            <h4>12</h4><small>Total Visits</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4>2</h4><small>Upcoming</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4>8</h4><small>Prescriptions</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4>1</h4><small>Pending Pay</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="p-card mb-4">
                <h6 class="fw-bold mb-3">Quick Actions</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="book_appointment.php" class="quick-btn"><span class="qb-icon"><i class="bi bi-calendar-plus"></i></span>Book Appointment</a>
                    <a href="prescription.php" class="quick-btn"><span class="qb-icon"><i class="bi bi-file-earmark-medical"></i></span>View Prescriptions</a>
                    <a href="live_queue.php" class="quick-btn"><span class="qb-icon"><i class="bi bi-broadcast"></i></span>Live Queue</a>
                    <a href="visit_history.php" class="quick-btn"><span class="qb-icon"><i class="bi bi-clock-history"></i></span>Visit History</a>
                </div>
            </div>

            <!-- Reminder -->
            <div class="reminder-card">
                <h5><i class="bi bi-bell-fill me-2"></i>Reminder</h5>
                <p>Your annual heart checkup is due next week. Don't miss it!</p>
                <a href="book_appointment.php" class="btn btn-light btn-sm rounded-pill fw-semibold" style="color:var(--brand);">Schedule Now</a>
            </div>

        </div>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Doctor:</strong> Dr. Sarah Wilson</p>
                <p><strong>Department:</strong> Cardiology</p>
                <p><strong>Date:</strong> 20 Feb 2026</p>
                <p><strong>Time:</strong> 10:30 AM</p>
                <p><strong>Location:</strong> CityCare Hospital</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="book_appointment.php" class="btn btn-brand">Reschedule</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("dashboardSearch").addEventListener("keyup", function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll("#historyTable tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
        });
    });
    document.querySelectorAll(".view-history").forEach(btn => {
        btn.addEventListener("click", () => new bootstrap.Modal(document.getElementById("appointmentModal")).show());
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>