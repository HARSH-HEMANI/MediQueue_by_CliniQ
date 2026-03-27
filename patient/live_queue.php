<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];
$today = date('Y-m-d');

// Find if patient has an active token today
$q_my_token = mysqli_query($con, "SELECT t.*, a.appointment_time, d.full_name as doctor_name, d.specialization, a.doctor_id
                                  FROM tokens t 
                                  JOIN appointments a ON t.appointment_id = a.appointment_id 
                                  JOIN doctors d ON a.doctor_id = d.doctor_id 
                                  WHERE a.patient_id = $patient_id 
                                  AND a.appointment_date = '$today' 
                                  AND t.status != 'Completed' LIMIT 1");
$my_token = ($q_my_token && mysqli_num_rows($q_my_token) > 0) ? mysqli_fetch_assoc($q_my_token) : null;

// If a token exists, fetch the queue for that doctor
$queue_list = [];
$current_serving = null;
$my_position_index = 0;
$total_patients_today = 0;

if ($my_token) {
    $doc_id = $my_token['doctor_id'];
    
    // Total patients for this doc today
    $q_total = mysqli_query($con, "SELECT COUNT(*) as c FROM appointments WHERE doctor_id = $doc_id AND appointment_date = '$today'");
    if($q_total) $total_patients_today = mysqli_fetch_assoc($q_total)['c'];
    
    // Active queue
    $q_queue = mysqli_query($con, "SELECT t.*, p.full_name as patient_name, a.patient_id 
                                   FROM tokens t 
                                   JOIN appointments a ON t.appointment_id = a.appointment_id 
                                   JOIN patients p ON a.patient_id = p.patient_id 
                                   WHERE a.doctor_id = $doc_id 
                                   AND a.appointment_date = '$today' 
                                   AND t.status != 'Completed' 
                                   ORDER BY t.token_no ASC");
                                   
    if($q_queue) {
        $index = 0;
        while($row = mysqli_fetch_assoc($q_queue)) {
            $queue_list[] = $row;
            if($row['status'] == 'In Progress' || $row['status'] == 'Consulting') {
                $current_serving = $row;
            }
            if($row['patient_id'] == $patient_id) {
                $my_position_index = $index;
            }
            $index++;
        }
        
        // If no one is explicitly "In Progress", assume the first in line is up next
        if(!$current_serving && count($queue_list) > 0) {
            $current_serving = $queue_list[0];
        }
    }
}

// Calculate Wait Time
$patients_ahead = $my_position_index;
$avg_consultation_time = 7; // mins
$wait_time = $patients_ahead * $avg_consultation_time;
$progress_width = 100;
if(count($queue_list) > 1) {
    $progress_width = max(10, 100 - ($patients_ahead * (100 / count($queue_list))));
}

$content_page = 'Live Queue | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Real-time clinic updates</small>
        <h3 class="fw-bold mb-0 mt-1">Live Queue Monitor</h3>
    </div>

    <div class="row g-4">
        
        <?php if($my_token): ?>
        <div class="col-xl-8 col-lg-7">

            <div class="now-serving-display mb-4">
                <span class="section-label">Now Serving</span>
                <div class="token-number" id="currentToken">#<?php echo $current_serving ? $current_serving['token_no'] : '--'; ?></div>
                <div class="token-label">Dr. <?php echo htmlspecialchars($my_token['doctor_name']); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($my_token['specialization']); ?></div>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue List</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Token</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Patient</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="queueTable">
                            <?php foreach($queue_list as $q_item): 
                                $is_me = ($q_item['patient_id'] == $patient_id);
                                $is_serving = ($current_serving && $current_serving['token_id'] == $q_item['token_id']);
                                
                                $row_class = '';
                                if($is_serving) $row_class = 'queue-row-active';
                                elseif($is_me) $row_class = 'queue-row-you';
                                
                                $badge = '<span class="badge-soft-warning">Waiting</span>';
                                if($is_serving) $badge = '<span class="badge-soft-success">Consulting</span>';
                                elseif($is_me) $badge = '<span class="badge bg-primary text-white">Waiting</span>';
                            ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td><strong>#<?php echo $q_item['token_no']; ?></strong></td>
                                <td>
                                    <?php if($is_me): ?>
                                        You <i class="bi bi-person-fill text-brand ms-1"></i>
                                    <?php else: ?>
                                        <?php 
                                        // Anonymise other patient names slightly for privacy (e.g. "John D.")
                                        $parts = explode(' ', $q_item['patient_name']);
                                        if(count($parts)>1) echo htmlspecialchars($parts[0] . ' ' . substr($parts[1],0,1) . '.');
                                        else echo htmlspecialchars($parts[0]);
                                        ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $badge; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-5">

            <div class="p-card mb-4 text-center">
                <span class="section-label">Your Position</span>
                <div class="display-4 fw-bold text-brand" id="positionNumber"><?php echo $patients_ahead; ?></div>
                <p class="text-muted mb-3" style="font-size:0.85rem;">Patients Ahead</p>

                <div class="progress mb-3" style="height:8px;border-radius:20px;">
                    <div id="queueProgress" class="progress-bar progress-bar-brand" style="width:<?php echo $progress_width; ?>%"></div>
                </div>

                <p class="text-muted" style="font-size:0.9rem;">
                    <i class="bi bi-clock me-1"></i>Estimated Wait: <strong id="waitTime"><?php echo $wait_time; ?></strong> min
                </p>

                <?php if($patients_ahead == 0): ?>
                <div id="alertArea" class="alert alert-light border-0 mt-3" style="font-size:0.84rem;background:rgba(34, 197, 94, 0.1)!important;color:#166534;">
                    <strong class="text-success"><i class="bi bi-check-circle-fill me-1"></i>It's your turn! Please proceed to the doctor.</strong>
                </div>
                <?php else: ?>
                <div id="alertArea" class="alert alert-light border-0 mt-3 text-muted" style="font-size:0.84rem;background:var(--brand-soft)!important;">
                    <i class="bi bi-info-circle me-1 text-brand"></i>Please wait. You will be notified when it's your turn.
                </div>
                <?php endif; ?>

                <button class="btn btn-brand w-100 mt-2" onclick="location.reload();">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh Status
                </button>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue Metrics</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Total Patients Today</span><strong><?php echo $total_patients_today; ?></strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Your Token</span><strong>#<?php echo $my_token['token_no']; ?></strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Doctor Status</span>
                        <strong class="text-success"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Available</strong>
                    </div>
                </div>
            </div>

        </div>
        
        <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-person-lines-fill text-muted mb-3" style="font-size:3rem;"></i>
                <h5 class="fw-bold">No Active Queue</h5>
                <p class="text-muted">You do not have any active appointments or tokens for today.</p>
                <a href="book_appointment.php" class="btn btn-brand mt-2">Book Appointment</a>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</div>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>