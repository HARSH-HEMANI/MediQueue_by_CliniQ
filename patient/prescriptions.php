<?php
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

        <div class="rx-card p-3 mb-3 d-flex align-items-center gap-3 prescription-card"
            data-doctor="Dr. Sarah Wilson" data-department="Cardiology"
            data-date="22 Feb 2026" data-diagnosis="Hypertension"
            data-id="RX-2026-1023"
            data-medicine="Amlodipine 5mg — Once daily&#10;Aspirin 75mg — After dinner"
            data-notes="Reduce salt intake. Follow-up after 1 month."
            data-followup="22 Mar 2026">
            <div class="rx-icon"><i class="bi bi-file-earmark-medical"></i></div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">Dr. Sarah Wilson</h6>
                <small class="text-muted">Cardiology &nbsp;·&nbsp; 22 Feb 2026</small>
                <p class="mb-0 mt-1" style="font-size:0.84rem;"><strong>Diagnosis:</strong> Hypertension</p>
            </div>
            <button class="btn btn-brand btn-sm view-prescription" style="white-space:nowrap;">
                <i class="bi bi-eye me-1"></i>View
            </button>
        </div>

        <div class="rx-card p-3 mb-3 d-flex align-items-center gap-3 prescription-card"
            data-doctor="Dr. Michael Ray" data-department="Orthopedics"
            data-date="10 Feb 2026" data-diagnosis="Knee Pain"
            data-id="RX-2026-1011"
            data-medicine="Ibuprofen 400mg — Twice daily&#10;Calcium Tablet — Once daily"
            data-notes="Avoid heavy lifting for 2 weeks."
            data-followup="24 Feb 2026">
            <div class="rx-icon"><i class="bi bi-file-earmark-medical"></i></div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">Dr. Michael Ray</h6>
                <small class="text-muted">Orthopedics &nbsp;·&nbsp; 10 Feb 2026</small>
                <p class="mb-0 mt-1" style="font-size:0.84rem;"><strong>Diagnosis:</strong> Knee Pain</p>
            </div>
            <button class="btn btn-brand btn-sm view-prescription" style="white-space:nowrap;">
                <i class="bi bi-eye me-1"></i>View
            </button>
        </div>

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
                    <div class="col-md-6">
                        <p><strong>ID:</strong> <span id="modalId"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Doctor:</strong> <span id="modalDoctor"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Department:</strong> <span id="modalDepartment"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    </div>
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
            c.style.display = c.innerText.toLowerCase().includes(val) ? "flex" : "none";
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>