<?php
$content_page = 'patient-dashboard | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <small class="text-muted">Welcome back, Krishna</small>
            <h3 class="fw-semibold mb-0">Patient Dashboard</h3>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">

            <!-- Search -->
            <input type="text"
                id="dashboardSearch"
                class="form-control rounded-pill"
                placeholder="Search appointments..."
                style="width:250px;">

            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        2
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item">✔ Appointment Confirmed</a></li>
                    <li><a class="dropdown-item">📄 New Prescription Added</a></li>
                </ul>
            </div>

            <!-- Profile -->
            <div class="dropdown">
                <button class="btn d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">
                    <img src="https://i.pravatar.cc/40"
                        class="rounded-circle"
                        width="40">
                    <span>John Doe</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile_setting.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="profile_setting.php">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- CONTENT GRID -->
    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-xl-8">

            <!-- Upcoming Appointment -->
            <div class="card-glass mb-4" id="upcomingCard">

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointment</h5>
                    <span class="badge-confirmed">Confirmed</span>
                </div>

                <hr>

                <p><strong id="doctorName">Dr. Sarah Wilson</strong> – Cardiologist</p>
                <p id="appointmentDate">20 Feb 2026 | 10:30 AM</p>
                <p>CityCare Hospital</p>

                <button class="btn btn-brand mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#appointmentModal">
                    View Details
                </button>

            </div>

            <!-- Appointment History -->
            <div class="card-glass">
                <h5>Appointment History</h5>

                <div class="table-responsive mt-3">
                    <table class="table align-middle" id="historyTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>10 Jan 2026</td>
                                <td>Dr. Smith</td>
                                <td>Dental</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary view-history">
                                        View
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>22 Dec 2025</td>
                                <td>Dr. Ray</td>
                                <td>Orthopedic</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary view-history">
                                        View
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-xl-4">

            <!-- Health Summary -->
            <div class="card-glass mb-4">
                <h5>Health Summary</h5>

                <div class="row g-3 mt-2">

                    <div class="col-6">
                        <div class="stat-box text-center">
                            <h4>12</h4>
                            <small>Total Appointments</small>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box text-center">
                            <h4>2</h4>
                            <small>Upcoming Visits</small>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box text-center">
                            <h4>8</h4>
                            <small>Prescriptions</small>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="stat-box text-center">
                            <h4>1</h4>
                            <small>Pending Payments</small>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-glass mb-4">
                <h5>Quick Actions</h5>

                <div class="d-grid gap-3 mt-3">
                    <a href="book_appointment.php"
                        class="btn btn-light rounded-pill">
                        Book New Appointment
                    </a>

                    <a href="prescriptions.php"
                        class="btn btn-light rounded-pill">
                        View Prescriptions
                    </a>

                    <a href="live_queue.php"
                        class="btn btn-light rounded-pill">
                        Live Queue
                    </a>
                </div>
            </div>

            <!-- Reminder -->
            <div class="reminder">
                <h5>Reminder</h5>
                <p>Your annual heart checkup is due next week.</p>
                <a href="book_appointment.php"
                    class="btn btn-light rounded-pill mt-2">
                    Schedule Now
                </a>
            </div>

        </div>

    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5>Appointment Details</h5>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Doctor:</strong> Dr. Sarah Wilson</p>
                <p><strong>Department:</strong> Cardiology</p>
                <p><strong>Date:</strong> 20 Feb 2026</p>
                <p><strong>Time:</strong> 10:30 AM</p>
                <p><strong>Location:</strong> CityCare Hospital</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <a href="book_appointment.php"
                    class="btn btn-brand">
                    Reschedule
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    /* Dashboard Search */
    document.getElementById("dashboardSearch")
        .addEventListener("keyup", function() {

            let value = this.value.toLowerCase();

            document.querySelectorAll("#historyTable tbody tr")
                .forEach(row => {
                    row.style.display =
                        row.innerText.toLowerCase().includes(value) ?
                        "" : "none";
                });
        });

    /* View History (demo modal reuse) */
    document.querySelectorAll(".view-history")
        .forEach(btn => {
            btn.addEventListener("click", function() {
                new bootstrap.Modal(
                    document.getElementById("appointmentModal")
                ).show();
            });
        });
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>