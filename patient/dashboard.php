<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];

// Get Patient Info
$q_pat = mysqli_query($con, "SELECT full_name FROM patients WHERE patient_id = $patient_id");
$patient_name = ($q_pat && mysqli_num_rows($q_pat) > 0) ? mysqli_fetch_assoc($q_pat)['full_name'] : 'Patient User';

// Get Stats
$q_total = mysqli_query($con, "SELECT COUNT(*) as c FROM appointments WHERE patient_id = $patient_id AND status = 'Completed'");
$total_visits = ($q_total) ? mysqli_fetch_assoc($q_total)['c'] : 0;

$q_upc = mysqli_query($con, "SELECT COUNT(*) as c FROM appointments WHERE patient_id = $patient_id AND status IN ('Pending', 'Confirmed') AND appointment_date >= CURDATE()");
$upcoming_count = ($q_upc) ? mysqli_fetch_assoc($q_upc)['c'] : 0;

$rx_count = 0;
$q_tb = mysqli_query($con, "SHOW TABLES LIKE 'prescriptions'");
if($q_tb && mysqli_num_rows($q_tb) > 0) {
    $q_rx = mysqli_query($con, "SELECT COUNT(*) as c FROM prescriptions p JOIN appointments a ON p.appointment_id = a.appointment_id WHERE a.patient_id = $patient_id");
    if($q_rx) { $rx_count = mysqli_fetch_assoc($q_rx)['c']; }
}

$q_next = mysqli_query($con, "SELECT a.*, d.full_name as doctor_name, d.specialization, c.clinic_name 
                              FROM appointments a 
                              LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
                              LEFT JOIN clinics c ON a.clinic_id = c.clinic_id 
                              WHERE a.patient_id = $patient_id 
                              AND a.status IN ('Pending', 'Confirmed') 
                              AND a.appointment_date >= CURDATE() 
                              ORDER BY a.appointment_date ASC, a.appointment_time ASC LIMIT 1");
$next_appt = ($q_next && mysqli_num_rows($q_next) > 0) ? mysqli_fetch_assoc($q_next) : null;

$q_hist = mysqli_query($con, "SELECT a.*, d.full_name as doctor_name, d.specialization 
                              FROM appointments a 
                              LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
                              WHERE a.patient_id = $patient_id 
                              ORDER BY a.appointment_id DESC LIMIT 4");

$content_page = 'Patient Dashboard | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Welcome back</small>
            <h3 class="fw-bold mb-0 mt-1"><?php echo htmlspecialchars($patient_name); ?> <span class="text-brand">👋</span></h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="text" id="dashboardSearch" class="form-control rounded-pill"
                style="width:220px;" placeholder="Search appointments...">

            <div class="dropdown">
                <button class="notif-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <?php if($upcoming_count > 0): ?><span class="notif-dot"></span><?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                    <?php if($upcoming_count > 0): ?>
                    <li><a class="dropdown-item rounded-2 py-2"><i class="bi bi-check-circle text-success me-2"></i>You have upcoming appointments!</a></li>
                    <?php else: ?>
                    <li><a class="dropdown-item rounded-2 py-2 text-muted">No new notifications</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="dropdown">
                <div class="user-chip" data-bs-toggle="dropdown">
                    <img src="https://i.pravatar.cc/40?u=patient<?php echo $patient_id; ?>" alt="Profile">
                    <span class="fw-semibold" style="font-size:0.87rem;"><?php echo htmlspecialchars($patient_name); ?></span>
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
                    <?php if($next_appt): ?>
                    <div>
                        <span class="section-label">Next Appointment</span>
                        <p class="fw-bold fs-5 mb-1">Dr. <?php echo htmlspecialchars($next_appt['doctor_name']); ?></p>
                        <p class="text-muted mb-1" style="font-size:0.85rem;"><i class="bi bi-heart-pulse me-1 text-brand"></i><?php echo htmlspecialchars($next_appt['specialization']); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($next_appt['clinic_name'] ?? 'Clinic'); ?></p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;"><i class="bi bi-calendar3 me-1"></i><?php echo date('d M Y', strtotime($next_appt['appointment_date'])); ?> &nbsp;·&nbsp; <i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($next_appt['appointment_time']); ?></p>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span class="<?php echo ($next_appt['status']=='Confirmed') ? 'badge-soft-success' : 'badge-soft-warning'; ?>"><?php echo htmlspecialchars($next_appt['status']); ?></span>
                        <a href="my_appointment.php" class="btn btn-brand btn-sm mt-1">View Details</a>
                    </div>
                    <?php else: ?>
                    <div class="w-100 text-center py-3">
                        <span class="section-label d-block text-center mb-2">Next Appointment</span>
                        <p class="text-muted">You have no upcoming appointments.</p>
                        <a href="book_appointment.php" class="btn btn-brand btn-sm mt-2">Book Now</a>
                    </div>
                    <?php endif; ?>
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
                            <?php if($q_hist && mysqli_num_rows($q_hist) > 0): while($hist = mysqli_fetch_assoc($q_hist)): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($hist['appointment_date'])); ?></td>
                                <td><strong>Dr. <?php echo htmlspecialchars($hist['doctor_name'] ?? 'Unknown'); ?></strong></td>
                                <td><?php echo htmlspecialchars($hist['specialization'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    if ($hist['status'] == 'Completed') echo '<span class="badge-soft-success">Completed</span>';
                                    elseif ($hist['status'] == 'Cancelled') echo '<span class="badge bg-danger">Cancelled</span>';
                                    elseif ($hist['status'] == 'Confirmed') echo '<span class="badge-soft-success">Confirmed</span>';
                                    else echo '<span class="badge-soft-warning">'.$hist['status'].'</span>';
                                    ?>
                                </td>
                                <td><a href="my_appointment.php" class="btn btn-outline-secondary btn-sm rounded-pill">View</a></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">No appointment history found.</td>
                            </tr>
                            <?php endif; ?>
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
                            <h4><?php echo $total_visits; ?></h4><small>Total Visits</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4><?php echo $upcoming_count; ?></h4><small>Upcoming</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4><?php echo $rx_count; ?></h4><small>Prescriptions</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <h4>0</h4><small>Pending Pay</small>
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
                <p>Complete your profile settings to enjoy seamless appointment booking and medical history tracking.</p>
                <a href="profile_setting.php" class="btn btn-light btn-sm rounded-pill fw-semibold" style="color:var(--brand);">Update Profile</a>
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
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>