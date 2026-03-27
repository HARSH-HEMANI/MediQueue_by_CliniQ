<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];

// Check if prescriptions table exists
$has_rx = false;
$check_rx = mysqli_query($con, "SHOW TABLES LIKE 'prescriptions'");
if($check_rx && mysqli_num_rows($check_rx) > 0) {
    $has_rx = true;
}

$q_visits = mysqli_query($con, "SELECT a.*, d.full_name as doctor_name, d.specialization, d.consultation_fee, c.clinic_name 
                                FROM appointments a 
                                LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
                                LEFT JOIN clinics c ON a.clinic_id = c.clinic_id 
                                WHERE a.patient_id = $patient_id AND a.status = 'Completed'
                                ORDER BY a.appointment_date DESC, a.appointment_time DESC");

$content_page = 'Visit History | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Your past consultations</small>
        <h3 class="fw-bold mb-0 mt-1">Visit History</h3>
    </div>

    <div class="p-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <select id="historyFilter" class="form-select rounded-pill w-auto">
                <option value="all">All Visits</option>
                <option value="recent">Last 30 Days</option>
                <option value="sixmonths">Last 6 Months</option>
                <option value="older">Older</option>
            </select>
            <input type="text" id="searchHistory" class="form-control rounded-pill"
                style="max-width:260px;" placeholder="Search doctor or clinic...">
        </div>
    </div>

    <div id="visitList">

        <?php if($q_visits && mysqli_num_rows($q_visits) > 0): while($visit = mysqli_fetch_assoc($q_visits)): 
            // Calculate date category
            $appt_time = strtotime($visit['appointment_date']);
            $now = time();
            $diff_days = round(($now - $appt_time) / (60 * 60 * 24));
            if($diff_days <= 30) $category = 'recent';
            elseif($diff_days <= 180) $category = 'sixmonths';
            else $category = 'older';
            
            // Prescription / Medical Data
            $diagnosis = 'Pending Update';
            $symptoms = 'N/A';
            $tests = 'None';
            $notes = 'No additional notes provided.';
            $medicine = 'No medicines prescribed.';
            $rx_notes = 'N/A';
            $followup = 'None';
            $rx_id = '';
            $has_valid_rx = false;

            if($has_rx) {
                // Assuming prescriptions schema has diagnosis, symptoms, medicines, etc.
                // We fallback to checking columns generically using a wide fetch
                $q_prx = mysqli_query($con, "SELECT * FROM prescriptions WHERE appointment_id = {$visit['appointment_id']} LIMIT 1");
                if($q_prx && mysqli_num_rows($q_prx) > 0) {
                    $rx = mysqli_fetch_assoc($q_prx);
                    $has_valid_rx = true;
                    $diagnosis = $rx['diagnosis'] ?? 'Not specified';
                    $symptoms = $rx['symptoms'] ?? 'Not specified';
                    $tests = $rx['tests_recommended'] ?? $rx['tests'] ?? 'None';
                    $notes = $rx['doctor_notes'] ?? $rx['notes'] ?? 'None';
                    $medicine = $rx['medicines'] ?? $rx['medicines_list'] ?? 'None';
                    $rx_notes = $rx['rx_notes'] ?? 'None';
                    $followup = $rx['follow_up_date'] ?? $rx['followup'] ?? 'None';
                    $rx_id = 'RX-' . date('Y', strtotime($visit['appointment_date'])) . '-' . $visit['appointment_id'];
                }
            }
            
            $total_fee = (int)$visit['consultation_fee'] + 20;
        ?>

        <div class="visit-card p-4 mb-3" data-category="<?php echo $category; ?>"
            data-doctor="Dr. <?php echo htmlspecialchars($visit['doctor_name']); ?>" 
            data-department="<?php echo htmlspecialchars($visit['specialization'] ?? ''); ?>"
            data-date="<?php echo date('d M Y', strtotime($visit['appointment_date'])); ?>" 
            data-diagnosis="<?php echo htmlspecialchars($diagnosis); ?>"
            data-symptoms="<?php echo htmlspecialchars($symptoms); ?>" 
            data-tests="<?php echo htmlspecialchars($tests); ?>"
            data-notes="<?php echo htmlspecialchars($notes); ?>" 
            data-fee="₹<?php echo $total_fee; ?>"
            data-rx-id="<?php echo htmlspecialchars($rx_id); ?>"
            data-medicine="<?php echo htmlspecialchars($medicine); ?>"
            data-rx-notes="<?php echo htmlspecialchars($rx_notes); ?>" 
            data-followup="<?php echo ($followup && $followup != 'None') ? date('d M Y', strtotime($followup)) : 'None'; ?>">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. <?php echo htmlspecialchars($visit['doctor_name']); ?></p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-heart-pulse me-1 text-brand"></i><?php echo htmlspecialchars($visit['specialization']); ?> &nbsp;·&nbsp; <?php echo date('d M Y', strtotime($visit['appointment_date'])); ?></p>
                </div>
                <span class="badge bg-secondary">Completed</span>
            </div>
            <hr class="my-3" style="border-color:rgba(0,0,0,0.06);">
            <div class="row g-2 mb-3" style="font-size:0.87rem;">
                <div class="col-md-6"><span class="text-muted">Diagnosis:</span> <strong><?php echo htmlspecialchars($diagnosis); ?></strong></div>
                <div class="col-md-6"><span class="text-muted">Fee:</span> <strong>₹<?php echo $total_fee; ?></strong></div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-brand btn-sm view-visit"><i class="bi bi-eye me-1"></i>View Details</button>
                <?php if($has_valid_rx): ?>
                <button class="btn btn-outline-success btn-sm view-prescription"><i class="bi bi-file-earmark-medical me-1"></i>Prescription</button>
                <?php else: ?>
                <button class="btn btn-outline-secondary btn-sm" disabled title="No prescription available"><i class="bi bi-file-earmark-medical me-1"></i>No Prescription</button>
                <?php endif; ?>
            </div>
        </div>

        <?php endwhile; else: ?>
        <div class="text-center py-5">
            <i class="bi bi-clock-history text-muted mb-3" style="font-size:3rem;"></i>
            <h5 class="text-muted">No past visits found.</h5>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Visit Detail Modal -->
<div class="modal fade" id="visitModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Visit Details</h5><button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <p><strong>Doctor:</strong> <span id="vDoctor"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Department:</strong> <span id="vDepartment"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <span id="vDate"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fee:</strong> <span id="vFee"></span></p>
                    </div>
                </div>
                <hr>
                <p><strong>Diagnosis:</strong> <span id="vDiagnosis" class="text-brand fw-bold"></span></p>
                <p><strong>Symptoms:</strong> <span id="vSymptoms"></span></p>
                <p><strong>Tests Done:</strong> <span id="vTests"></span></p>
                <p><strong>Notes:</strong> <span id="vNotes"></span></p>
            </div>
            <div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div class="modal fade" id="rxModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-medical text-brand me-2"></i>Prescription</h5><button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="rxId"></span></p>
                <p><strong>Follow-up:</strong> <span id="rxFollowup" class="text-brand fw-bold"></span></p>
                <hr>
                <p class="mb-2"><strong>Medicines:</strong></p>
                <div class="p-3 rounded-3 bg-brand-soft">
                    <pre id="rxMedicine" class="mb-0" style="white-space:pre-wrap;font-family:inherit;font-size:0.88rem;"></pre>
                </div>
                <p class="mt-3 mb-1"><strong>Notes:</strong></p>
                <p id="rxNotes" class="text-muted" style="font-size:0.88rem;"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-brand" onclick="window.print()"><i class="bi bi-download me-1"></i>Download</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".view-visit").forEach(btn => {
        btn.addEventListener("click", function() {
            const c = this.closest(".visit-card");
            document.getElementById("vDoctor").innerText = c.dataset.doctor;
            document.getElementById("vDepartment").innerText = c.dataset.department;
            document.getElementById("vDate").innerText = c.dataset.date;
            document.getElementById("vFee").innerText = c.dataset.fee;
            document.getElementById("vDiagnosis").innerText = c.dataset.diagnosis;
            document.getElementById("vSymptoms").innerText = c.dataset.symptoms;
            document.getElementById("vTests").innerText = c.dataset.tests;
            document.getElementById("vNotes").innerText = c.dataset.notes;
            new bootstrap.Modal(document.getElementById("visitModal")).show();
        });
    });
    document.querySelectorAll(".view-prescription").forEach(btn => {
        btn.addEventListener("click", function() {
            const c = this.closest(".visit-card");
            document.getElementById("rxId").innerText = c.dataset.rxId;
            document.getElementById("rxFollowup").innerText = c.dataset.followup;
            document.getElementById("rxMedicine").innerText = c.dataset.medicine;
            document.getElementById("rxNotes").innerText = c.dataset.rxNotes;
            new bootstrap.Modal(document.getElementById("rxModal")).show();
        });
    });
    document.getElementById("historyFilter").addEventListener("change", function() {
        const val = this.value;
        document.querySelectorAll(".visit-card").forEach(c => {
            c.style.display = (val === "all" || c.dataset.category === val) ? "block" : "none";
        });
    });
    document.getElementById("searchHistory").addEventListener("keyup", function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll(".visit-card").forEach(c => {
            c.style.display = c.innerText.toLowerCase().includes(val) ? "block" : "none";
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>