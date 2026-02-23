<?php
$content_page = 'My Appointments | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Manage your bookings</small>
        <h3>My Appointments</h3>
    </div>

    <!-- FILTER + SEARCH -->
    <div class="card-glass mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div class="btn-group">
                <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="upcoming">Upcoming</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="completed">Completed</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="cancelled">Cancelled</button>
            </div>

            <input type="text"
                id="searchInput"
                class="form-control"
                style="max-width:300px;"
                placeholder="Search doctor or department">
        </div>
    </div>

    <!-- APPOINTMENT LIST -->
    <div id="appointmentList">

        <!-- UPCOMING -->
        <div class="appointment-card upcoming"
            data-status="upcoming"
            data-doctor="Dr. Sarah Wilson"
            data-department="Cardiology"
            data-hospital="CityCare Hospital"
            data-date="22 Feb 2026"
            data-time="10:30 AM"
            data-booking="MQ-1023"
            data-fee="₹520">

            <div class="appointment-header d-flex justify-content-between">
                <div>
                    <h6>Dr. Sarah Wilson</h6>
                    <small>Cardiology • CityCare Hospital</small>
                </div>
                <span class="badge bg-success">Confirmed</span>
            </div>

            <div class="appointment-body mt-2">
                <p><strong>22 Feb 2026</strong> | 10:30 AM</p>
                <p>Booking ID: MQ-1023</p>
            </div>

            <div class="appointment-actions mt-3">
                <button class="btn btn-sm btn-outline-secondary view-btn">
                    <i class="bi bi-eye me-1"></i> View
                </button>
                <button class="btn btn-sm btn-outline-danger cancel-btn">
                    Cancel
                </button>
            </div>
        </div>

        <!-- COMPLETED -->
        <div class="appointment-card completed mt-4"
            data-status="completed"
            data-doctor="Dr. Michael Ray"
            data-department="Orthopedics"
            data-hospital="HealthFirst Clinic"
            data-date="10 Feb 2026"
            data-time="12:00 PM"
            data-booking="MQ-1011"
            data-fee="₹450">

            <div class="appointment-header d-flex justify-content-between">
                <div>
                    <h6>Dr. Michael Ray</h6>
                    <small>Orthopedics • HealthFirst Clinic</small>
                </div>
                <span class="badge bg-secondary">Completed</span>
            </div>

            <div class="appointment-body mt-2">
                <p><strong>10 Feb 2026</strong> | 12:00 PM</p>
                <p>Booking ID: MQ-1011</p>
            </div>

            <div class="appointment-actions mt-3">
                <button class="btn btn-sm btn-outline-secondary view-btn">
                    <i class="bi bi-eye me-1"></i> View
                </button>
            </div>
        </div>

        <!-- CANCELLED -->
        <div class="appointment-card cancelled mt-4"
            data-status="cancelled"
            data-doctor="Dr. Emily Stone"
            data-department="Dermatology"
            data-hospital="SkinCare Center"
            data-date="05 Feb 2026"
            data-time="09:00 AM"
            data-booking="MQ-1004"
            data-fee="₹350">

            <div class="appointment-header d-flex justify-content-between">
                <div>
                    <h6>Dr. Emily Stone</h6>
                    <small>Dermatology • SkinCare Center</small>
                </div>
                <span class="badge bg-danger">Cancelled</span>
            </div>

            <div class="appointment-body mt-2">
                <p><strong>05 Feb 2026</strong> | 09:00 AM</p>
                <p>Booking ID: MQ-1004</p>
            </div>

            <div class="appointment-actions mt-3">
                <button class="btn btn-sm btn-outline-secondary view-btn">
                    <i class="bi bi-eye me-1"></i> View
                </button>
            </div>
        </div>

    </div>

</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
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
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-brand" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    /* VIEW */
    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const card = this.closest(".appointment-card");

            modalDoctor.innerText = card.dataset.doctor;
            modalDepartment.innerText = card.dataset.department;
            modalHospital.innerText = card.dataset.hospital;
            modalDate.innerText = card.dataset.date;
            modalTime.innerText = card.dataset.time;
            modalBooking.innerText = card.dataset.booking;
            modalFee.innerText = card.dataset.fee;
            modalStatus.innerText = card.dataset.status;

            new bootstrap.Modal(document.getElementById("viewModal")).show();
        });
    });

    /* FILTER */
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            let filter = this.dataset.filter;

            document.querySelectorAll(".appointment-card").forEach(card => {
                card.style.display =
                    filter === "all" || card.dataset.status === filter ?
                    "block" : "none";
            });
        });
    });

    /* SEARCH */
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();

        document.querySelectorAll(".appointment-card").forEach(card => {
            card.style.display =
                card.innerText.toLowerCase().includes(value) ?
                "block" : "none";
        });
    });

    /* CANCEL */
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            if (confirm("Cancel this appointment?")) {

                let card = this.closest(".appointment-card");

                card.dataset.status = "cancelled";
                card.classList.remove("upcoming");
                card.classList.add("cancelled");

                let badge = card.querySelector(".badge");
                badge.innerText = "Cancelled";
                badge.className = "badge bg-danger";

                this.remove();
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>