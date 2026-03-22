<?php
$content_page = 'My Appointments | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Manage your bookings</small>
        <h3 class="fw-bold mb-0 mt-1">My Appointments</h3>
    </div>

    <!-- Filter + Search -->
    <div class="p-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex gap-2 flex-wrap">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="upcoming">Upcoming</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="cancelled">Cancelled</button>
            </div>
            <input type="text" id="searchInput" class="form-control rounded-pill"
                style="max-width:260px;" placeholder="Search doctor or dept...">
        </div>
    </div>

    <!-- List -->
    <div id="appointmentList">

        <div class="appt-card upcoming appointment-card p-4 mb-3"
            data-status="upcoming" data-doctor="Dr. Sarah Wilson"
            data-department="Cardiology" data-hospital="CityCare Hospital"
            data-date="22 Feb 2026" data-time="10:30 AM"
            data-booking="MQ-1023" data-fee="₹520">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. Sarah Wilson</p>
                    <p class="text-muted mb-1" style="font-size:0.82rem;"><i class="bi bi-heart-pulse me-1 text-brand"></i>Cardiology &nbsp;·&nbsp; CityCare Hospital</p>
                    <p class="text-muted mb-1" style="font-size:0.84rem;"><i class="bi bi-calendar3 me-1"></i>22 Feb 2026 &nbsp;·&nbsp; 10:30 AM</p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-hash me-1"></i>MQ-1023</p>
                </div>
                <span class="badge-soft-success">Confirmed</span>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-brand btn-sm view-btn"><i class="bi bi-eye me-1"></i>View</button>
                <button class="btn btn-outline-danger btn-sm cancel-btn">Cancel</button>
            </div>
        </div>

        <div class="appt-card completed appointment-card p-4 mb-3"
            data-status="completed" data-doctor="Dr. Michael Ray"
            data-department="Orthopedics" data-hospital="HealthFirst Clinic"
            data-date="10 Feb 2026" data-time="12:00 PM"
            data-booking="MQ-1011" data-fee="₹450">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. Michael Ray</p>
                    <p class="text-muted mb-1" style="font-size:0.82rem;"><i class="bi bi-bandaid me-1" style="color:#22c55e"></i>Orthopedics &nbsp;·&nbsp; HealthFirst Clinic</p>
                    <p class="text-muted mb-1" style="font-size:0.84rem;"><i class="bi bi-calendar3 me-1"></i>10 Feb 2026 &nbsp;·&nbsp; 12:00 PM</p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-hash me-1"></i>MQ-1011</p>
                </div>
                <span class="badge bg-secondary">Completed</span>
            </div>
            <div class="mt-3">
                <button class="btn btn-outline-secondary btn-sm view-btn"><i class="bi bi-eye me-1"></i>View</button>
            </div>
        </div>

        <div class="appt-card cancelled appointment-card p-4 mb-3"
            data-status="cancelled" data-doctor="Dr. Emily Stone"
            data-department="Dermatology" data-hospital="SkinCare Center"
            data-date="05 Feb 2026" data-time="09:00 AM"
            data-booking="MQ-1004" data-fee="₹350">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">Dr. Emily Stone</p>
                    <p class="text-muted mb-1" style="font-size:0.82rem;"><i class="bi bi-droplet me-1" style="color:#ef4444"></i>Dermatology &nbsp;·&nbsp; SkinCare Center</p>
                    <p class="text-muted mb-1" style="font-size:0.84rem;"><i class="bi bi-calendar3 me-1"></i>05 Feb 2026 &nbsp;·&nbsp; 09:00 AM</p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-hash me-1"></i>MQ-1004</p>
                </div>
                <span class="badge-soft-danger">Cancelled</span>
            </div>
            <div class="mt-3">
                <button class="btn btn-outline-secondary btn-sm view-btn"><i class="bi bi-eye me-1"></i>View</button>
            </div>
        </div>

    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Doctor:</strong> <span id="modalDoctor"></span></p>
                <p><strong>Department:</strong> <span id="modalDepartment"></span></p>
                <p><strong>Hospital:</strong> <span id="modalHospital"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Time:</strong> <span id="modalTime"></span></p>
                <p><strong>Booking ID:</strong> <span id="modalBooking"></span></p>
                <p><strong>Consultation Fee:</strong> <span id="modalFee"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus" class="text-capitalize"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const c = this.closest(".appointment-card");
            document.getElementById("modalDoctor").innerText = c.dataset.doctor;
            document.getElementById("modalDepartment").innerText = c.dataset.department;
            document.getElementById("modalHospital").innerText = c.dataset.hospital;
            document.getElementById("modalDate").innerText = c.dataset.date;
            document.getElementById("modalTime").innerText = c.dataset.time;
            document.getElementById("modalBooking").innerText = c.dataset.booking;
            document.getElementById("modalFee").innerText = c.dataset.fee;
            document.getElementById("modalStatus").innerText = c.dataset.status;
            new bootstrap.Modal(document.getElementById("viewModal")).show();
        });
    });
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            const f = this.dataset.filter;
            document.querySelectorAll(".appointment-card").forEach(c => {
                c.style.display = (f === "all" || c.dataset.status === f) ? "block" : "none";
            });
        });
    });
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll(".appointment-card").forEach(c => {
            c.style.display = c.innerText.toLowerCase().includes(val) ? "block" : "none";
        });
    });
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            if (confirm("Cancel this appointment?")) {
                const c = this.closest(".appointment-card");
                c.dataset.status = "cancelled";
                c.classList.remove("upcoming");
                c.classList.add("cancelled");
                c.querySelector(".badge-soft-success, .badge-soft-warning").outerHTML = "<span class='badge-soft-danger'>Cancelled</span>";
                this.remove();
            }
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>