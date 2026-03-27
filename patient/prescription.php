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

$q_rx = null;
if($has_rx) {
    $q_rx = mysqli_query($con, "SELECT p.*, a.appointment_date, d.full_name as doctor_name, d.specialization 
                                FROM prescriptions p 
                                JOIN appointments a ON p.appointment_id = a.appointment_id 
                                LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
                                WHERE a.patient_id = $patient_id 
                                ORDER BY a.appointment_date DESC");
}

$content_page = 'Prescriptions | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Your medical records</small>
        <h3 class="fw-bold mb-0 mt-1">Prescriptions</h3>
    </div>

    <div class="p-card mb-4">
        <input type="text" id="searchPrescription" class="form-control rounded-pill"
            placeholder="Search by doctor or diagnosis...">
    </div>

    <div id="prescriptionList">

        <?php if($q_rx && mysqli_num_rows($q_rx) > 0): while($rx = mysqli_fetch_assoc($q_rx)): 
            $diagnosis = $rx['diagnosis'] ?? 'Not specified';
            $medicine = $rx['medicines'] ?? $rx['medicines_list'] ?? 'None';
            $notes = $rx['doctor_notes'] ?? $rx['notes'] ?? 'None';
            $followup = $rx['follow_up_date'] ?? $rx['followup'] ?? 'None';
            $rx_id = 'RX-' . date('Y', strtotime($rx['appointment_date'])) . '-' . $rx['prescription_id'];
        ?>

        <div class="rx-card p-3 mb-3 d-flex align-items-center gap-3 prescription-card"
            data-doctor="Dr. <?php echo htmlspecialchars($rx['doctor_name']); ?>" 
            data-department="<?php echo htmlspecialchars($rx['specialization']); ?>"
            data-date="<?php echo date('d M Y', strtotime($rx['appointment_date'])); ?>" 
            data-diagnosis="<?php echo htmlspecialchars($diagnosis); ?>"
            data-id="<?php echo htmlspecialchars($rx_id); ?>"
            data-medicine="<?php echo htmlspecialchars($medicine); ?>"
            data-notes="<?php echo htmlspecialchars($notes); ?>"
            data-followup="<?php echo ($followup && $followup != 'None') ? date('d M Y', strtotime($followup)) : 'None'; ?>">
            
            <div class="rx-icon"><i class="bi bi-file-earmark-medical"></i></div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">Dr. <?php echo htmlspecialchars($rx['doctor_name']); ?></h6>
                <small class="text-muted"><?php echo htmlspecialchars($rx['specialization']); ?> &nbsp;·&nbsp; <?php echo date('d M Y', strtotime($rx['appointment_date'])); ?></small>
                <p class="mb-0 mt-1" style="font-size:0.84rem;"><strong>Diagnosis:</strong> <?php echo htmlspecialchars($diagnosis); ?></p>
            </div>
            <button class="btn btn-brand btn-sm view-prescription" style="white-space:nowrap;">
                <i class="bi bi-eye me-1"></i>View
            </button>
        </div>

        <?php endwhile; else: ?>
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-medical text-muted mb-3" style="font-size:3rem;"></i>
            <h5 class="text-muted">No prescriptions available.</h5>
        </div>
        <?php endif; ?>

    </div>
</div>

<div class="modal fade" id="prescriptionModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-medical text-brand me-2"></i>Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><p><strong>ID:</strong> <span id="modalId"></span></p></div>
                    <div class="col-md-6"><p><strong>Doctor:</strong> <span id="modalDoctor"></span></p></div>
                    <div class="col-md-6"><p><strong>Department:</strong> <span id="modalDepartment"></span></p></div>
                    <div class="col-md-6"><p><strong>Date:</strong> <span id="modalDate"></span></p></div>
                </div>
                <hr>
                <p><strong>Diagnosis:</strong> <span id="modalDiagnosis" class="text-brand fw-bold"></span></p>
                <hr>
                <p class="mb-2"><strong>Medicines:</strong></p>
                <div class="p-3 rounded-3 bg-brand-soft">
                    <pre id="modalMedicine" class="mb-0" style="white-space:pre-wrap;font-family:inherit;font-size:0.88rem;"></pre>
                </div>
                <p class="mt-3 mb-1"><strong>Doctor Notes:</strong></p>
                <p id="modalNotes" class="text-muted" style="font-size:0.88rem;"></p>
                <p><strong>Follow-up:</strong> <span id="modalFollowup" class="text-brand fw-bold"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-brand" onclick="window.print()"><i class="bi bi-download me-1"></i>Download PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".view-prescription").forEach(btn => {
        btn.addEventListener("click", function() {
            const c = this.closest(".prescription-card");
            document.getElementById("modalId").innerText = c.dataset.id;
            document.getElementById("modalDoctor").innerText = c.dataset.doctor;
            document.getElementById("modalDepartment").innerText = c.dataset.department;
            document.getElementById("modalDate").innerText = c.dataset.date;
            document.getElementById("modalDiagnosis").innerText = c.dataset.diagnosis;
            document.getElementById("modalMedicine").innerText = c.dataset.medicine;
            document.getElementById("modalNotes").innerText = c.dataset.notes;
            document.getElementById("modalFollowup").innerText = c.dataset.followup;
            new bootstrap.Modal(document.getElementById("prescriptionModal")).show();
        });
    });
    document.getElementById("searchPrescription").addEventListener("keyup", function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll(".prescription-card").forEach(c => {
            const textContent = c.innerText.toLowerCase();
            c.style.display = textContent.includes(val) ? "flex" : "none";
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>