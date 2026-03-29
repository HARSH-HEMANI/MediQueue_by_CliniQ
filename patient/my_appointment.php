<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];

$q_appts = mysqli_query($con, "SELECT a.*, d.full_name as doctor_name, d.specialization, c.clinic_name, d.consultation_fee 
                               FROM appointments a 
                               LEFT JOIN doctors d ON a.doctor_id = d.doctor_id 
                               LEFT JOIN clinics c ON a.clinic_id = c.clinic_id 
                               WHERE a.patient_id = $patient_id 
                               ORDER BY a.appointment_date DESC, a.appointment_time DESC");

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

        <?php if($q_appts && mysqli_num_rows($q_appts) > 0): while($appt = mysqli_fetch_assoc($q_appts)): 
            $status_class = '';
            $status_filter = strtolower($appt['status']);
            $badge = '';
            $icon_color = 'text-brand';
            
            if ($appt['status'] == 'Confirmed' || $appt['status'] == 'Pending') {
                $status_class = 'upcoming';
                $status_filter = 'upcoming';
                if($appt['status'] == 'Confirmed') $badge = '<span class="badge-soft-success">Confirmed</span>';
                else $badge = '<span class="badge-soft-warning">Pending</span>';
            } elseif ($appt['status'] == 'Completed') {
                $status_class = 'completed';
                $status_filter = 'completed';
                $badge = '<span class="badge bg-secondary">Completed</span>';
                $icon_color = 'text-success';
            } elseif ($appt['status'] == 'Cancelled') {
                $status_class = 'cancelled';
                $status_filter = 'cancelled';
                $badge = '<span class="badge-soft-danger">Cancelled</span>';
                $icon_color = 'text-danger';
            }
            
            // Total fee calculation (Consultation + 20 Platform fee)
            $total_fee = (int)$appt['consultation_fee'] + 20;
        ?>

        <div class="appt-card <?php echo $status_class; ?> appointment-card p-4 mb-3"
            data-status="<?php echo $status_filter; ?>" 
            data-doctor="Dr. <?php echo htmlspecialchars($appt['doctor_name']); ?>"
            data-department="<?php echo htmlspecialchars($appt['specialization'] ?? ''); ?>" 
            data-hospital="<?php echo htmlspecialchars($appt['clinic_name'] ?? 'Clinic'); ?>"
            data-date="<?php echo date('d M Y', strtotime($appt['appointment_date'])); ?>" 
            data-time="<?php echo htmlspecialchars($appt['appointment_time']); ?>"
            data-booking="MQ-<?php echo str_pad($appt['appointment_id'], 4, '0', STR_PAD_LEFT); ?>" 
            data-fee="₹<?php echo $total_fee; ?>"
            data-id="<?php echo $appt['appointment_id']; ?>">
            
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="fw-bold fs-6 mb-1"><?php echo htmlspecialchars($appt['doctor_name']); ?></p>
                    <p class="text-muted mb-1" style="font-size:0.82rem;"><i class="bi bi-heart-pulse me-1 <?php echo $icon_color; ?>"></i><?php echo htmlspecialchars($appt['specialization'] ?? ''); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($appt['clinic_name'] ?? 'Clinic'); ?></p>
                    <p class="text-muted mb-1" style="font-size:0.84rem;"><i class="bi bi-calendar3 me-1"></i><?php echo date('d M Y', strtotime($appt['appointment_date'])); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($appt['appointment_time']); ?></p>
                    <p class="text-muted mb-0" style="font-size:0.82rem;"><i class="bi bi-hash me-1"></i>MQ-<?php echo str_pad($appt['appointment_id'], 4, '0', STR_PAD_LEFT); ?></p>
                </div>
                <div class="status-badge-container"><?php echo $badge; ?></div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-outline-secondary btn-sm view-btn"><i class="bi bi-eye me-1"></i>View</button>
                <?php if($status_class == 'upcoming'): ?>
                <button class="btn btn-outline-danger btn-sm cancel-btn" data-id="<?php echo $appt['appointment_id']; ?>">Cancel</button>
                <?php endif; ?>
            </div>
        </div>

        <?php endwhile; else: ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar-x text-muted mb-3" style="font-size:3rem;"></i>
            <h5 class="text-muted">No appointments found.</h5>
            <a href="book_appointment.php" class="btn btn-brand mt-2">Book Now</a>
        </div>
        <?php endif; ?>

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
                <p><strong>Total Fee:</strong> <span id="modalFee"></span></p>
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
            const textContent = c.innerText.toLowerCase();
            c.style.display = textContent.includes(val) ? "block" : "none";
        });
    });
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            if (confirm("Are you sure you want to cancel this appointment?")) {
                const appointmentId = this.dataset.id;
                const c = this.closest(".appointment-card");
                
                fetch('cancel_appointment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + appointmentId
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        c.dataset.status = "cancelled";
                        c.classList.remove("upcoming");
                        c.classList.add("cancelled");
                        c.querySelector(".status-badge-container").innerHTML = "<span class='badge-soft-danger'>Cancelled</span>";
                        this.remove(); // remove cancel button
                    } else {
                        alert(data.error || "Failed to cancel appointment.");
                    }
                })
                .catch(err => alert("Error processing request"));
            }
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>