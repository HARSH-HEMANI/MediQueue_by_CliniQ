<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$content_page = 'Book Appointment | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Schedule your visit</small>
        <h3 class="fw-bold mb-0 mt-1">Book Appointment</h3>
    </div>

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-xl-8 col-lg-7">
            <div class="p-card">
                <form action="book_appointment_action.php" method="POST" id="bookingForm">
                <?php
                include_once '../db.php';
                // Fetch distinct specializations
                $spec_query = "SELECT DISTINCT specialization FROM doctors WHERE is_active = 1";
                $spec_res = mysqli_query($con, $spec_query);
                
                // Fetch all active doctors to pass to JS
                $doc_query = "SELECT doctor_id, clinic_id, full_name, specialization, consultation_fee FROM doctors WHERE is_active = 1";
                $doc_res = mysqli_query($con, $doc_query);
                $all_doctors = [];
                while ($rd = mysqli_fetch_assoc($doc_res)) {
                    $all_doctors[] = $rd;
                }
                ?>

                <span class="section-label">Select Specialization</span>
                <div class="mb-4">
                    <select id="specSelect" class="form-select rounded-3">
                        <option value="">-- Select Specialization --</option>
                        <?php while ($row = mysqli_fetch_assoc($spec_res)): ?>
                            <option value="<?php echo htmlspecialchars($row['specialization']); ?>">
                                <?php echo htmlspecialchars($row['specialization']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <span class="section-label">Select Doctor</span>
                <div class="mb-4">
                    <select id="doctorSelect" name="doctor_id" class="form-select rounded-3" disabled required>
                        <option value="">-- First Select Specialization --</option>
                    </select>
                </div>
                
                <!-- Hidden inputs for clinic ID and time -->
                <input type="hidden" name="clinic_id" id="hiddenClinicId">
                <input type="hidden" name="appointment_time" id="hiddenTime">

                <span class="section-label">Select Date</span>
                <input type="date" id="appointmentDate" name="appointment_date"
                    class="form-control rounded-3 mb-1"
                    style="max-width:260px;"
                    data-validation="required" required>
                <small id="appointmentDate_error" class="text-danger d-block mb-4"></small>

                <span class="section-label">Select Time Slot</span>
                <div class="row g-2 mb-4">
                    <?php
                    $slots = ['09:00 AM', '10:30 AM', '12:00 PM', '02:00 PM', '03:30 PM'];
                    foreach ($slots as $s):
                    ?>
                        <div class="col-4 col-md-2">
                            <div class="slot"><?php echo $s; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <span class="section-label">Appointment Type</span>
                        <select name="appointment_type" class="form-select rounded-3" required>
                            <option value="New">New Consultation</option>
                            <option value="Follow Up">Follow Up</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <span class="section-label">Reason for Visit</span>
                        <textarea name="visit_reason" class="form-control rounded-3" rows="1" placeholder="Briefly describe your symptoms" required></textarea>
                    </div>
                </div>

                <span class="section-label">Payment Method</span>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="payment-card active-payment">
                            <i class="bi bi-cash-stack me-2"></i>Pay at Clinic
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card" style="opacity:0.5; cursor:not-allowed;" title="Coming Soon">
                            <i class="bi bi-phone me-2"></i>UPI
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card" style="opacity:0.5; cursor:not-allowed;" title="Coming Soon">
                            <i class="bi bi-credit-card me-2"></i>Card
                        </div>
                    </div>
                </div>

                <button type="submit" id="confirmBtn" class="btn-confirm" disabled>
                    <i class="bi bi-calendar-check me-2"></i>Confirm Appointment
                </button>
                </form>

            </div>
        </div>

        <!-- RIGHT — Summary -->
        <div class="col-xl-4 col-lg-5">
            <div class="p-card summary-sticky">

                <span class="section-label">Appointment Summary</span>

                <div class="summary-box mb-3">
                    <p class="mb-2 text-muted" style="font-size:0.87rem;"><strong class="text-dark">Department:</strong> <span id="summaryDept">Cardiology</span></p>
                    <p class="mb-2 text-muted" style="font-size:0.87rem;"><strong class="text-dark">Doctor:</strong> <span id="summaryDoctor">Dr. Sarah Wilson</span></p>
                    <p class="mb-2 text-muted" style="font-size:0.87rem;"><strong class="text-dark">Date:</strong> <span id="summaryDate">--</span></p>
                    <p class="mb-0 text-muted" style="font-size:0.87rem;"><strong class="text-dark">Time:</strong> <span id="summaryTime">--</span></p>
                </div>

                <div class="summary-box">
                    <p class="mb-2 text-muted" style="font-size:0.87rem;">Consultation Fee: <strong class="text-dark">₹<span id="summaryFee">500</span></strong></p>
                    <p class="mb-2 text-muted" style="font-size:0.87rem;">Platform Fee: <strong class="text-dark">₹20</strong></p>
                    <hr class="my-2" style="border-color:rgba(0,0,0,0.07);">
                    <h6 class="mb-0">Total: ₹<span id="summaryTotal">520</span></h6>
                </div>

                <div class="mt-3 p-3 rounded-3 bg-brand-soft" style="font-size:0.82rem;color:#6b7280;">
                    <i class="bi bi-shield-check text-brand me-2"></i>
                    Appointments confirmed instantly. Free cancellation up to 24 hrs before.
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body p-5">
                <div class="fs-1 mb-3">🎉</div>
                <h5 class="fw-bold mb-2">Appointment Booked!</h5>
                <p class="text-muted">Your appointment has been confirmed. We'll send a reminder before your visit.</p>
                <button class="btn btn-brand mt-2" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
    const allDoctors = <?php echo json_encode($all_doctors); ?>;
    let selectedTime = null;
    let selectedDate = null;
    let selectedDoctorId = null;

    // Specialization Selection
    document.getElementById("specSelect").addEventListener("change", function() {
        const spec = this.value;
        const docSelect = document.getElementById("doctorSelect");
        
        // Reset doctor dropdown
        docSelect.innerHTML = '<option value="">-- Select Doctor --</option>';
        docSelect.disabled = true;
        selectedDoctorId = null;
        document.getElementById("hiddenClinicId").value = "";
        
        // Reset summary info
        document.getElementById("summaryDept").innerText = spec || '--';
        document.getElementById("summaryDoctor").innerText = '--';
        document.getElementById("summaryFee").innerText = '0';
        document.getElementById("summaryTotal").innerText = '0';
        
        if (spec) {
            // Filter doctors by specialization
            const filteredDoctors = allDoctors.filter(d => d.specialization === spec);
            
            if (filteredDoctors.length > 0) {
                docSelect.disabled = false;
                
                // Add default
                const defaultOpt = document.createElement('option');
                defaultOpt.value = "";
                defaultOpt.textContent = "-- Select Doctor --";
                docSelect.appendChild(defaultOpt);
                
                filteredDoctors.forEach(doc => {
                    const opt = document.createElement('option');
                    opt.value = doc.doctor_id;
                    opt.dataset.clinic = doc.clinic_id;
                    opt.textContent = doc.full_name + ' (₹' + doc.consultation_fee + ')';
                    docSelect.appendChild(opt);
                });
            } else {
                docSelect.innerHTML = '<option value="">No doctors available</option>';
            }
        }
        validateForm();
    });

    // Doctor Selection
    document.getElementById("doctorSelect").addEventListener("change", function() {
        const docId = this.value;
        selectedDoctorId = docId;
        
        if (docId) {
            const selectedOpt = this.options[this.selectedIndex];
            document.getElementById("hiddenClinicId").value = selectedOpt.dataset.clinic;
            
            const doc = allDoctors.find(d => d.doctor_id == docId);
            const fee = parseInt(doc.consultation_fee);
            
            document.getElementById("summaryDoctor").innerText = doc.full_name;
            document.getElementById("summaryFee").innerText = fee;
            document.getElementById("summaryTotal").innerText = fee + 20; // 20 is platform fee
        } else {
            document.getElementById("hiddenClinicId").value = "";
            document.getElementById("summaryDoctor").innerText = '--';
            document.getElementById("summaryFee").innerText = '0';
            document.getElementById("summaryTotal").innerText = '0';
        }
        validateForm();
    });

    // Date
    document.getElementById("appointmentDate").addEventListener("change", function() {
        selectedDate = this.value;
        document.getElementById("summaryDate").innerText = selectedDate;
        validateForm();
    });

    // Time slot
    document.querySelectorAll(".slot").forEach(slot => {
        slot.addEventListener("click", function() {
            document.querySelectorAll(".slot").forEach(s => s.classList.remove("active-slot"));
            this.classList.add("active-slot");
            selectedTime = this.innerText;
            document.getElementById("hiddenTime").value = selectedTime;
            document.getElementById("summaryTime").innerText = selectedTime;
            validateForm();
        });
    });

    // Payment
    document.querySelectorAll(".payment-card").forEach(card => {
        if(card.style.opacity == "0.5") return; // disable coming soon cards
        card.addEventListener("click", function() {
            document.querySelectorAll(".payment-card").forEach(c => c.classList.remove("active-payment"));
            this.classList.add("active-payment");
        });
    });

    function validateForm() {
        document.getElementById("confirmBtn").disabled = !(selectedDoctorId && selectedDate && selectedTime);
    }
    
    // Check for success session flag
    <?php if(isset($_SESSION['booking_success'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        new bootstrap.Modal(document.getElementById("successModal")).show();
    });
    <?php unset($_SESSION['booking_success']); endif; ?>
    
    <?php if(isset($_SESSION['booking_error'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        alert("Error: <?php echo $_SESSION['booking_error']; ?>");
    });
    <?php unset($_SESSION['booking_error']); endif; ?>
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>