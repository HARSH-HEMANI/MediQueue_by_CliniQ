<?php
require_once "reception-init.php";

$content_page = 'Patient Directory | Reception | MediQueue';
ob_start();

$clinic_id = $_SESSION['clinic_id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$today = date('Y-m-d');

/**
 * SQL Logic:
 * Fetch appointments for the current clinic and today's date.
 */
$query = "SELECT 
            p.full_name, p.date_of_birth, p.gender, p.phone, p.email, 
            d.full_name as doctor_name, 
            a.appointment_time, a.appointment_type, a.status as app_status
          FROM appointments a
          JOIN patients p ON a.patient_id = p.patient_id
          JOIN doctors d ON a.doctor_id = d.doctor_id
          WHERE a.appointment_date = '$today' AND a.clinic_id = '$clinic_id'";

if (!empty($search)) {
    $query .= " AND (p.full_name LIKE '%$search%' OR p.phone LIKE '%$search%')";
}

$query .= " ORDER BY a.appointment_time ASC";
$result = mysqli_query($con, $query);

function calculateAge($dob)
{
    if (empty($dob)) return "—";
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    return $today->diff($birthDate)->y;
}
?>

<div class="reception-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">All scheduled patients</small>
            <h1 class="dashboard-title mt-1">Patient <span>Directory</span></h1>
            <p class="dashboard-subtitle">Patients scheduled for today (<?= date('d M Y') ?>)</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form method="GET" action="patient_list.php" class="d-flex gap-2">
                <input type="text" name="search" class="form-control rounded-pill" style="width:220px;"
                    placeholder="Search name or phone..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-brand rounded-pill">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <a href="register-patient.php" class="btn btn-outline-brand rounded-pill">
                <i class="bi bi-person-plus me-1"></i>Register Patient
            </a>
        </div>
    </div>

    <div class="rcard">
        <div class="rcard-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="r-thead">
                            <th>Name</th>
                            <th>Age / Gender</th>
                            <th>Phone</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)):
                                // Status Badges
                                $status = $row['app_status'];
                                $badgeClass = 'badge-soft-warning';
                                if ($status == 'Completed') $badgeClass = 'badge-soft-success';
                                if ($status == 'Confirmed') $badgeClass = 'badge-soft-primary';
                                if ($status == 'Cancelled') $badgeClass = 'badge-soft-danger';

                                // --- BOOTSTRAP ONLY TYPE BADGES ---
                                $type = $row['appointment_type'];
                                if ($type == 'Emergency') {
                                    $typeBadge = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                } elseif ($type == 'Follow Up') {
                                    $typeBadge = 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25';
                                } else {
                                    // Default for 'New' - using a dark grey/slate theme for better contrast
                                    $typeBadge = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
                                }
                            ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['full_name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($row['email'] ?: 'No Email') ?></div>
                                    </td>
                                    <td class="text-muted"><?= calculateAge($row['date_of_birth']) ?> / <?= $row['gender'] ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($row['phone']) ?></td>
                                    <td>Dr. <?= htmlspecialchars($row['doctor_name']) ?></td>
                                    <td class="text-muted" style="font-size:0.88rem;">
                                        <?= date('h:i A', strtotime($row['appointment_time'])) ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 fw-medium <?= $typeBadge ?>">
                                            <?= $type ?>
                                        </span>
                                    </td>
                                    <td><span class="badge <?= $badgeClass ?> rounded-pill"><?= $status ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-exclamation fs-2 d-block mb-2"></i>
                                    No patients found for today.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted mt-3 mb-0" style="font-size:0.85rem;">
                <i class="bi bi-info-circle me-1"></i>Showing appointments for clinic ID: <?= htmlspecialchars($clinic_id) ?>
            </p>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>