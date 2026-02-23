<?php
$content_page = 'Prescriptions | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Your medical records</small>
        <h3>Prescriptions</h3>
    </div>

    <!-- Search -->
    <div class="card-glass mb-4">
        <input type="text"
            id="searchPrescription"
            class="form-control"
            placeholder="Search by doctor or diagnosis">
    </div>

    <!-- Prescription List -->
    <div id="prescriptionList">

        <!-- Prescription 1 -->
        <div class="prescription-card mb-4"
            data-doctor="Dr. Sarah Wilson"
            data-department="Cardiology"
            data-date="22 Feb 2026"
            data-diagnosis="Hypertension"
            data-id="RX-2026-1023"
            data-medicine="Amlodipine 5mg - Once daily
Aspirin 75mg - After dinner"
            data-notes="Reduce salt intake. Follow-up after 1 month."
            data-followup="22 Mar 2026">

            <div class="card-glass d-flex justify-content-between align-items-center">

                <div>
                    <h6>Dr. Sarah Wilson</h6>
                    <small>Cardiology • 22 Feb 2026</small>
                    <p class="mt-2 mb-0">
                        <strong>Diagnosis:</strong> Hypertension
                    </p>
                </div>

                <button class="btn btn-sm btn-outline-primary view-prescription">
                    <i class="bi bi-file-earmark-medical me-1"></i> View
                </button>

            </div>
        </div>

        <!-- Prescription 2 -->
        <div class="prescription-card mb-4"
            data-doctor="Dr. Michael Ray"
            data-department="Orthopedics"
            data-date="10 Feb 2026"
            data-diagnosis="Knee Pain"
            data-id="RX-2026-1011"
            data-medicine="Ibuprofen 400mg - Twice daily
Calcium Tablet - Once daily"
            data-notes="Avoid heavy lifting for 2 weeks."
            data-followup="24 Feb 2026">

            <div class="card-glass d-flex justify-content-between align-items-center">

                <div>
                    <h6>Dr. Michael Ray</h6>
                    <small>Orthopedics • 10 Feb 2026</small>
                    <p class="mt-2 mb-0">
                        <strong>Diagnosis:</strong> Knee Pain
                    </p>
                </div>

                <button class="btn btn-sm btn-outline-primary view-prescription">
                    <i class="bi bi-file-earmark-medical me-1"></i> View
                </button>

            </div>
        </div>

    </div>

</div>

<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><strong>Prescription ID:</strong> <span id="modalId"></span></p>
                <p><strong>Doctor:</strong> <span id="modalDoctor"></span></p>
                <p><strong>Department:</strong> <span id="modalDepartment"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>

                <hr>

                <p><strong>Diagnosis:</strong> <span id="modalDiagnosis"></span></p>

                <hr>

                <p><strong>Medicines:</strong></p>
                <pre id="modalMedicine" class="bg-light p-3 rounded"></pre>

                <p><strong>Doctor Notes:</strong></p>
                <p id="modalNotes"></p>

                <p><strong>Follow-up Date:</strong> <span id="modalFollowup"></span></p>

            </div>

            <div class="modal-footer">
                <button class="btn btn-brand">
                    <i class="bi bi-download me-1"></i> Download PDF
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    /* VIEW PRESCRIPTION */
    document.querySelectorAll(".view-prescription").forEach(btn => {
        btn.addEventListener("click", function() {

            const card = this.closest(".prescription-card");

            modalId.innerText = card.dataset.id;
            modalDoctor.innerText = card.dataset.doctor;
            modalDepartment.innerText = card.dataset.department;
            modalDate.innerText = card.dataset.date;
            modalDiagnosis.innerText = card.dataset.diagnosis;
            modalMedicine.innerText = card.dataset.medicine;
            modalNotes.innerText = card.dataset.notes;
            modalFollowup.innerText = card.dataset.followup;

            new bootstrap.Modal(
                document.getElementById("prescriptionModal")
            ).show();
        });
    });

    /* SEARCH */
    document.getElementById("searchPrescription")
        .addEventListener("keyup", function() {

            let value = this.value.toLowerCase();

            document.querySelectorAll(".prescription-card")
                .forEach(card => {
                    card.style.display =
                        card.innerText.toLowerCase().includes(value) ?
                        "block" : "none";
                });
        });
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>