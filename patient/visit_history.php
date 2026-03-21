<?php
$content_page = 'Visit History | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Your past consultations</small>
        <h3>Visit History</h3>
    </div>

    <!-- FILTER + SEARCH -->
    <div class="card-glass mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <select id="historyFilter" class="form-select w-auto">
                <option value="all">All Visits</option>
                <option value="recent">Last 30 Days</option>
                <option value="sixmonths">Last 6 Months</option>
                <option value="older">Older</option>
            </select>

            <input type="text"
                id="searchHistory"
                class="form-control w-auto"
                placeholder="Search doctor or diagnosis">

        </div>
    </div>

    <!-- VISIT LIST -->
    <div id="visitList">

        <!-- VISIT CARD -->
        <div class="visit-card card-glass mb-4"
            data-category="recent"
            data-doctor="Dr. Sarah Wilson"
            data-department="Cardiology"
            data-date="22 Feb 2026"
            data-diagnosis="Hypertension"
            data-symptoms="High blood pressure, dizziness"
            data-tests="Blood Pressure Monitoring"
            data-notes="Continue medication regularly."
            data-fee="₹520"
            data-rx-id="RX-2026-1023"
            data-medicine="Amlodipine 5mg - Once Daily
Aspirin 75mg - After Dinner"
            data-rx-notes="Reduce salt intake. Monitor BP daily."
            data-followup="22 Mar 2026">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="mb-1">Dr. Sarah Wilson</h6>
                    <small>Cardiology • 22 Feb 2026</small>
                </div>

                <span class="badge bg-secondary">Completed</span>

            </div>

            <hr>

            <p><strong>Diagnosis:</strong> Hypertension</p>
            <p><strong>Consultation Fee:</strong> ₹520</p>

            <div class="mt-3 d-flex gap-2 flex-wrap">
                <button class="btn btn-sm btn-outline-primary view-visit">
                    <i class="bi bi-eye me-1"></i> View Details
                </button>

                <button class="btn btn-sm btn-outline-success view-prescription">
                    <i class="bi bi-file-earmark-medical me-1"></i> Prescription
                </button>
            </div>

        </div>

        <!-- SECOND VISIT -->
        <div class="visit-card card-glass mb-4"
            data-category="sixmonths"
            data-doctor="Dr. Michael Ray"
            data-department="Orthopedics"
            data-date="10 Jan 2026"
            data-diagnosis="Knee Pain"
            data-symptoms="Joint stiffness"
            data-tests="X-ray"
            data-notes="Avoid heavy lifting."
            data-fee="₹450"
            data-rx-id="RX-2026-1011"
            data-medicine="Ibuprofen 400mg - Twice Daily
Calcium Tablet - Once Daily"
            data-rx-notes="Rest for 2 weeks."
            data-followup="24 Jan 2026">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="mb-1">Dr. Michael Ray</h6>
                    <small>Orthopedics • 10 Jan 2026</small>
                </div>

                <span class="badge bg-secondary">Completed</span>

            </div>

            <hr>

            <p><strong>Diagnosis:</strong> Knee Pain</p>
            <p><strong>Consultation Fee:</strong> ₹450</p>

            <div class="mt-3 d-flex gap-2 flex-wrap">
                <button class="btn btn-sm btn-outline-primary view-visit">
                    <i class="bi bi-eye me-1"></i> View Details
                </button>

                <button class="btn btn-sm btn-outline-success view-prescription">
                    <i class="bi bi-file-earmark-medical me-1"></i> Prescription
                </button>
            </div>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>