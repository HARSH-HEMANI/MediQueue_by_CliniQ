<?php
include_once "reception-auth.php";
include_once "../db.php"; // DB connection

date_default_timezone_set('Asia/Kolkata');

$content_page = 'Reception Dashboard | MediQueue';

/* =========================
   FETCH DYNAMIC DATA
========================= */

// Today's date
$today = date('Y-m-d');

// 1. Total Appointments Today
$todayAppointmentsQuery = mysqli_query($con, "
    SELECT COUNT(*) as total 
    FROM appointments 
    WHERE appointment_date = '$today'
");
$todayAppointments = mysqli_fetch_assoc($todayAppointmentsQuery)['total'] ?? 0;

// 2. Patients in Queue (Not Completed)
$queueQuery = mysqli_query($con, "
    SELECT COUNT(*) as total 
    FROM tokens 
    WHERE status != 'Completed'
");
$queueCount = mysqli_fetch_assoc($queueQuery)['total'] ?? 0;

// 3. Available Doctors
$doctorQuery = mysqli_query($con, "
    SELECT COUNT(*) as total 
    FROM doctors 
    WHERE is_active = 1
");
$doctorCount = mysqli_fetch_assoc($doctorQuery)['total'] ?? 0;

// 4. Completed Appointments Today
$completedQuery = mysqli_query($con, "
    SELECT COUNT(*) as total 
    FROM appointments 
    WHERE appointment_date = '$today' 
    AND status = 'Completed'
");
$completedCount = mysqli_fetch_assoc($completedQuery)['total'] ?? 0;

// 5. Today's Appointment List
$appointmentsQuery = mysqli_query($con, "
    SELECT a.*, d.full_name as doctor_name, p.full_name as patient_name
    FROM appointments a
    LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
    LEFT JOIN patients p ON a.patient_id = p.patient_id
    WHERE a.appointment_date = '$today'
    ORDER BY a.appointment_time ASC
    LIMIT 5
");

// 6. Live Queue
$queueListQuery = mysqli_query($con, "
    SELECT t.*, p.full_name 
    FROM tokens t
    LEFT JOIN appointments a ON t.appointment_id = a.appointment_id
    LEFT JOIN patients p ON a.patient_id = p.patient_id
    WHERE t.status != 'Completed'
    ORDER BY t.queue_position ASC
    LIMIT 3
");

ob_start();
?>

<div class="reception-dashboard">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand">Overview</small>
            <h1 class="dashboard-title mt-1">Reception <span>Dashboard</span></h1>
            <p class="dashboard-subtitle">Today's clinic activity at a glance</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success py-2 px-3">Live</span>
            <span class="text-muted"><?php echo date('D, d M Y'); ?></span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stats-row mb-4">
        <div class="rstat-card">
            <h6>Today's Appointments</h6>
            <h2><?php echo $todayAppointments; ?></h2>
        </div>
        <div class="rstat-card">
            <h6>Patients in Queue</h6>
            <h2><?php echo $queueCount; ?></h2>
        </div>
        <div class="rstat-card">
            <h6>Doctors Available</h6>
            <h2><?php echo $doctorCount; ?></h2>
        </div>
        <div class="rstat-card">
            <h6>Completed Today</h6>
            <h2><?php echo $completedCount; ?></h2>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <!-- Today's Appointments -->
        <div class="col-lg-8">
            <div class="rcard h-100">
                <div class="rcard-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Today's Appointments</h5>
                        <a href="manage_appointment.php" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="r-thead">
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($appointmentsQuery)) { ?>
                                    <tr>
                                        <td><?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
                                        <td><strong><?php echo $row['patient_name'] ?? 'N/A'; ?></strong></td>
                                        <td><?php echo $row['doctor_name'] ?? 'N/A'; ?></td>
                                        <td>
                                            <?php
                                            $status = $row['status'];
                                            $class = "badge-soft-warning";

                                            if ($status == "Completed") $class = "badge-soft-success";
                                            elseif ($status == "Consulting") $class = "badge-soft-primary";
                                            ?>
                                            <span class="<?php echo $class; ?>"><?php echo $status; ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>

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
                        <h5>Live Queue</h5>
                        <a href="live_queue.php" class="btn btn-sm btn-outline-secondary">Manage</a>
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <?php
                        $labels = ["Now", "Next", "Upcoming"];
                        $i = 0;

                        while ($q = mysqli_fetch_assoc($queueListQuery)) {
                        ?>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border">
                                <div>
                                    <div style="font-size:0.7rem;"><?php echo $labels[$i] ?? ''; ?></div>
                                    <strong><?php echo $q['full_name'] ?? 'N/A'; ?></strong>
                                </div>
                                <span class="badge-soft-warning"><?php echo $q['status']; ?></span>
                            </div>
                        <?php $i++;
                        } ?>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="rcard">
        <div class="rcard-body">
            <h5>Quick Actions</h5>
            <div class="d-flex flex-column gap-2">
                <a href="register_patient.php" class="quick-action-btn">Register Walk-in Patient</a>
                <a href="manage_appointment.php" class="quick-action-btn">Manage Appointments</a>
                <a href="live_queue.php" class="quick-action-btn">View Live Queue</a>
                <a href="patient_list.php" class="quick-action-btn">Patient Directory</a>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>