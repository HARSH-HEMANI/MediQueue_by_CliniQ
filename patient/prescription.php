<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

$content_page = 'Prescriptions | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <!-- Header -->
    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">
            Your medical records
        </small>
        <h3 class="fw-bold mb-0 mt-1">Prescriptions</h3>
    </div>

    <!-- Search -->
    <div class="p-card mb-4">
        <input type="text" id="searchPrescription" class="form-control rounded-pill"
            placeholder="Search by doctor or diagnosis...">
    </div>

    <!-- Dynamic List -->
    <div id="prescriptionList"></div>

</div>

<!-- Modal -->
<div class="modal fade" id="prescriptionModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">
            <div class="my-3 text-center">
                <h4 class="fw-bold">MediQueue Clinic</h4>
                <small>Digital Prescription</small>
                <hr>
            </div>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-medical text-brand me-2"></i>
                    Prescription Details
                </h5>
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

                <p><strong>Diagnosis:</strong>
                    <span id="modalDiagnosis" class="text-brand fw-bold"></span>
                </p>

                <hr>

                <p class="mb-2"><strong>Medicines:</strong></p>
                <div class="p-3 rounded-3 bg-brand-soft">
                    <ul id="modalMedicine" class="mb-0"
                        style="white-space:pre-wrap;font-family:inherit;font-size:0.88rem;"></ul>
                </div>

                <p class="mt-3 mb-1"><strong>Doctor Notes:</strong></p>
                <p id="modalNotes" class="text-muted" style="font-size:0.88rem;"></p>

                <p>
                    <strong>Follow-up:</strong>
                    <span id="modalFollowup" class="text-brand fw-bold"></span>
                </p>

            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-brand" onclick="printPrescription()">
                    <i class="bi bi-download me-1"></i>Download PDF
                </button>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    function escapeHTML(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    // 🔥 Load prescriptions (AJAX)
    function loadPrescriptions(search = '') {

        fetch('fetch_prescription.php?search=' + encodeURIComponent(search))
            .then(res => res.json())
            .then(data => {

                const container = document.getElementById("prescriptionList");
                container.innerHTML = "";

                if (data.length === 0) {
                    container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-medical text-muted mb-3" style="font-size:3rem;"></i>
                        <h5 class="text-muted">No prescriptions available.</h5>
                    </div>`;
                    return;
                }

                data.forEach(rx => {

                    const diagnosis = rx.diagnosis || 'Not specified';
                    const medicine = rx.medicines || 'None';
                    const notes = rx.doctor_notes || 'None';

                    const followup = rx.follow_up_date ?
                        new Date(rx.follow_up_date).toDateString() :
                        'None';

                    const date = new Date(rx.appointment_date);

                    const rx_id = `RX-${date.getFullYear()}-${rx.prescription_id}`;

                    const card = document.createElement("div");
                    card.className = "rx-card p-3 mb-3 d-flex align-items-center gap-3 prescription-card";

                    // dataset
                    card.dataset.id = rx_id;
                    card.dataset.doctor = "Dr. " + rx.doctor_name;
                    card.dataset.department = rx.specialization;
                    card.dataset.date = date.toDateString();
                    card.dataset.diagnosis = diagnosis;
                    card.dataset.medicine = encodeURIComponent(medicine);
                    card.dataset.notes = encodeURIComponent(notes);
                    card.dataset.followup = followup;

                    // UI
                    card.innerHTML = `
                    <div class="rx-icon"><i class="bi bi-file-earmark-medical"></i></div>

                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">
                            Dr. ${escapeHTML(rx.doctor_name)}
                        </h6>

                        <small class="text-muted">
                            ${escapeHTML(rx.specialization)} · ${date.toDateString()}
                        </small>

                        <p class="mb-0 mt-1" style="font-size:0.84rem;">
                            <strong>Diagnosis:</strong> ${escapeHTML(diagnosis)}
                        </p>
                    </div>

                    <button class="btn btn-brand btn-sm view-btn">
                        <i class="bi bi-eye me-1"></i>View
                    </button>
                `;

                    container.appendChild(card);
                });

                attachViewEvents();
            });
    }

    // 🔥 Modal events
    function attachViewEvents() {
        document.querySelectorAll(".view-btn").forEach(btn => {

            btn.onclick = function() {

                const c = this.closest(".prescription-card");

                document.getElementById("modalId").innerText = c.dataset.id;
                document.getElementById("modalDoctor").innerText = c.dataset.doctor;
                document.getElementById("modalDepartment").innerText = c.dataset.department;
                document.getElementById("modalDate").innerText = c.dataset.date;
                document.getElementById("modalDiagnosis").innerText = c.dataset.diagnosis;
                const medData = decodeURIComponent(c.dataset.medicine || '');

                let html = '';

                if (medData.trim() === '' || medData === 'None') {
                    html = '<li>No medicines prescribed</li>';
                } else {

                    const parts = medData.split('--');

                    if (parts.length === 2) {
                        html = `<li>${parts[0].trim()} → ${parts[1].trim()}</li>`;
                    } else {
                        html = `<li>${medData}</li>`;
                    }
                }

                document.getElementById("modalMedicine").innerHTML = html;

                document.getElementById("modalMedicine").innerHTML = html;
                document.getElementById("modalNotes").innerText =
                    decodeURIComponent(c.dataset.notes || 'No notes available');
                document.getElementById("modalFollowup").innerText = c.dataset.followup;

                new bootstrap.Modal(document.getElementById("prescriptionModal")).show();
            };
        });
    }

    // 🔍 Live search
    document.getElementById("searchPrescription").addEventListener("keyup", function() {
        loadPrescriptions(this.value);
    });

    // 🚀 Initial load
    loadPrescriptions();
</script>
<script>
    function printPrescription() {

        const modal = document.getElementById("prescriptionModal");

        modal.classList.add("show");
        modal.style.display = "block";

        // ⏳ WAIT for content to render
        setTimeout(() => {
            window.print();
        }, 300);
    }
</script>
<?php
$content = ob_get_clean();
include './patient-layout.php';
?>