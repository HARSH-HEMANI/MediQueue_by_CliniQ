<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

$content_page = 'My Appointments | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">
            Manage your bookings
        </small>
        <h3 class="fw-bold mb-0 mt-1">My Appointments</h3>
    </div>

    <!-- FILTER + SEARCH -->
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

    <!-- DYNAMIC LIST -->
    <div id="appointmentList">
        <div class="text-center py-5 text-muted">
            Loading appointments...
        </div>
    </div>

</div>

<!-- VIEW MODAL -->
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
                <p><strong>Total Fee:</strong> <span id="modalFee"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let lastData = "";

    /* ========================
       FETCH APPOINTMENTS
    ======================== */
    function loadAppointments() {
        fetch("fetch_appointments.php")
            .then(res => res.json())
            .then(data => {

                let newData = JSON.stringify(data);

                if (newData !== lastData) {
                    lastData = newData;
                    renderAppointments(data);
                }
            })
            .catch(() => {
                document.getElementById("appointmentList").innerHTML =
                    "<div class='text-danger text-center'>Error loading data</div>";
            });
    }

    /* ========================
       RENDER UI
    ======================== */
    function renderAppointments(data) {

        let html = "";

        if (data.length === 0) {
            html = `
            <div class="text-center py-5">
                <i class="bi bi-calendar-x text-muted mb-3" style="font-size:3rem;"></i>
                <h5 class="text-muted">No appointments found.</h5>
                <a href="book_appointment.php" class="btn btn-brand mt-2">Book Now</a>
            </div>
        `;
            document.getElementById("appointmentList").innerHTML = html;
            return;
        }

        data.forEach(appt => {

            let status_class = "";
            let badge = "";
            let icon_color = "text-brand";

            if (appt.status === "Confirmed" || appt.status === "Pending") {
                status_class = "upcoming";
                badge = appt.status === "Confirmed" ?
                    "<span class='badge-soft-success'>Confirmed</span>" :
                    "<span class='badge-soft-warning'>Pending</span>";
            } else if (appt.status === "Completed") {
                status_class = "completed";
                badge = "<span class='badge bg-secondary'>Completed</span>";
                icon_color = "text-success";
            } else if (appt.status === "Cancelled") {
                status_class = "cancelled";
                badge = "<span class='badge-soft-danger'>Cancelled</span>";
                icon_color = "text-danger";
            }

            let total_fee = parseInt(appt.consultation_fee || 0) + 20;

            html += `
        <div class="appt-card ${status_class} appointment-card p-4 mb-3"
            data-status="${status_class}"
            data-id="${appt.appointment_id}"
            data-doctor="${appt.doctor_name}"
            data-department="${appt.specialization || ''}"
            data-hospital="${appt.clinic_name || 'Clinic'}"
            data-date="${appt.appointment_date}"
            data-time="${appt.appointment_time}"
            data-booking="MQ-${String(appt.appointment_id).padStart(4,'0')}"
            data-fee="₹${total_fee}"
            data-status-text="${appt.status}">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1">${appt.doctor_name}</p>
                    <p class="text-muted mb-1" style="font-size:0.82rem;">
                        <i class="bi bi-heart-pulse me-1 ${icon_color}"></i>
                        ${appt.specialization || ''} · ${appt.clinic_name || 'Clinic'}
                    </p>
                    <p class="text-muted mb-1" style="font-size:0.84rem;">
                        <i class="bi bi-calendar3 me-1"></i>
                        ${appt.appointment_date} · ${appt.appointment_time}
                    </p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;">
                        <i class="bi bi-hash me-1"></i>
                        MQ-${String(appt.appointment_id).padStart(4,'0')}
                    </p>
                </div>

                <div class="status-badge-container">${badge}</div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-outline-secondary btn-sm view-btn">
                    <i class="bi bi-eye me-1"></i>View
                </button>

                ${
                    status_class === "upcoming"
                    ? `<button class="btn btn-outline-danger btn-sm cancel-btn" data-id="${appt.appointment_id}">Cancel</button>`
                    : ""
                }
            </div>
        </div>`;
        });

        document.getElementById("appointmentList").innerHTML = html;
    }

    /* ========================
       EVENTS
    ======================== */

    // VIEW
    document.addEventListener("click", function(e) {
        if (e.target.closest(".view-btn")) {
            let c = e.target.closest(".appointment-card");

            document.getElementById("modalDoctor").innerText = c.dataset.doctor;
            document.getElementById("modalDepartment").innerText = c.dataset.department;
            document.getElementById("modalHospital").innerText = c.dataset.hospital;
            document.getElementById("modalDate").innerText = c.dataset.date;
            document.getElementById("modalTime").innerText = c.dataset.time;
            document.getElementById("modalBooking").innerText = c.dataset.booking;
            document.getElementById("modalFee").innerText = c.dataset.fee;
            document.getElementById("modalStatus").innerText = c.dataset.statusText;

            new bootstrap.Modal(document.getElementById("viewModal")).show();
        }
    });

    // CANCEL
    document.addEventListener("click", function(e) {
        if (e.target.closest(".cancel-btn")) {

            if (!confirm("Cancel this appointment?")) return;

            let id = e.target.dataset.id;

            fetch('cancel_appointment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + id
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) loadAppointments();
                    else alert("Failed to cancel");
                });
        }
    });

    // FILTER
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("filter-btn")) {

            document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
            e.target.classList.add("active");

            let f = e.target.dataset.filter;

            document.querySelectorAll(".appointment-card").forEach(c => {
                c.style.display = (f === "all" || c.dataset.status === f) ? "block" : "none";
            });
        }
    });

    // SEARCH
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let val = this.value.toLowerCase();

        document.querySelectorAll(".appointment-card").forEach(c => {
            c.style.display = c.innerText.toLowerCase().includes(val) ? "block" : "none";
        });
    });

    /* ========================
       AUTO REFRESH
    ======================== */
    loadAppointments();
    setInterval(loadAppointments, 10000);
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>