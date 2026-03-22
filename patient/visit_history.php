<?php
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
                style="max-width:260px;" placeholder="Search doctor or diagnosis...">
        </div>
    </div>

    <div id="visitList">

        <div class="visit-card p-4 mb-3" data-category="recent"
            data-doctor="Dr. Sarah Wilson" data-department="Cardiology"
            data-date="22 Feb 2026" data-diagnosis="Hypertension"
            data-symptoms="High blood pressure, dizziness" data-tests="Blood Pressure Monitoring"
            data-notes="Continue medication regularly." data-fee="₹520"
            data-rx-id="RX-2026-1023"
            data-medicine="Amlodipine 5mg — Once Daily&#10;Aspirin 75mg — After Dinner"
            data-rx-notes="Reduce salt intake. Monitor BP daily." data-followup="22 Mar 2026">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. Sarah Wilson</p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-heart-pulse me-1 text-brand"></i>Cardiology &nbsp;·&nbsp; 22 Feb 2026</p>
                </div>
                <span class="badge bg-secondary">Completed</span>
            </div>
            <hr class="my-3" style="border-color:rgba(0,0,0,0.06);">
            <div class="row g-2 mb-3" style="font-size:0.87rem;">
                <div class="col-md-6"><span class="text-muted">Diagnosis:</span> <strong>Hypertension</strong></div>
                <div class="col-md-6"><span class="text-muted">Fee:</span> <strong>₹520</strong></div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-brand btn-sm view-visit"><i class="bi bi-eye me-1"></i>View Details</button>
                <button class="btn btn-outline-success btn-sm view-prescription"><i class="bi bi-file-earmark-medical me-1"></i>Prescription</button>
            </div>
        </div>

        <div class="visit-card p-4 mb-3" data-category="sixmonths"
            data-doctor="Dr. Michael Ray" data-department="Orthopedics"
            data-date="10 Jan 2026" data-diagnosis="Knee Pain"
            data-symptoms="Joint stiffness" data-tests="X-ray"
            data-notes="Avoid heavy lifting." data-fee="₹450"
            data-rx-id="RX-2026-1011"
            data-medicine="Ibuprofen 400mg — Twice Daily&#10;Calcium Tablet — Once Daily"
            data-rx-notes="Rest for 2 weeks." data-followup="24 Jan 2026">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. Michael Ray</p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-bandaid me-1" style="color:#22c55e"></i>Orthopedics &nbsp;·&nbsp; 10 Jan 2026</p>
                </div>
                <span class="badge bg-secondary">Completed</span>
            </div>
            <hr class="my-3" style="border-color:rgba(0,0,0,0.06);">
            <div class="row g-2 mb-3" style="font-size:0.87rem;">
                <div class="col-md-6"><span class="text-muted">Diagnosis:</span> <strong>Knee Pain</strong></div>
                <div class="col-md-6"><span class="text-muted">Fee:</span> <strong>₹450</strong></div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-brand btn-sm view-visit"><i class="bi bi-eye me-1"></i>View Details</button>
                <button class="btn btn-outline-success btn-sm view-prescription"><i class="bi bi-file-earmark-medical me-1"></i>Prescription</button>
            </div>
        </div>

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