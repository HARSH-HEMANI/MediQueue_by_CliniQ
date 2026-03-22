<?php
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

                <span class="section-label">Select Department</span>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="dept-card active-department"
                            data-dept="Cardiology"
                            data-doctor="Dr. Sarah Wilson"
                            data-spec="Cardiologist"
                            data-initials="SW"
                            data-fee="500">
                            <div class="fs-3 mb-2">🫀</div>
                            <h6 class="fw-bold mb-1">Cardiology</h6>
                            <small class="text-muted">Heart Specialists</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dept-card"
                            data-dept="Orthopedics"
                            data-doctor="Dr. Michael Ray"
                            data-spec="Orthopedist"
                            data-initials="MR"
                            data-fee="450">
                            <div class="fs-3 mb-2">🦴</div>
                            <h6 class="fw-bold mb-1">Orthopedics</h6>
                            <small class="text-muted">Bone & Joint Care</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dept-card"
                            data-dept="Dermatology"
                            data-doctor="Dr. Emily Stone"
                            data-spec="Dermatologist"
                            data-initials="ES"
                            data-fee="350">
                            <div class="fs-3 mb-2">🩺</div>
                            <h6 class="fw-bold mb-1">Dermatology</h6>
                            <small class="text-muted">Skin Treatment</small>
                        </div>
                    </div>
                </div>

                <span class="section-label">Assigned Doctor</span>
                <div class="doctor-info-card mb-4">
                    <div class="avatar" id="doctorAvatar">SW</div>
                    <div>
                        <strong id="doctorName" class="d-block">Dr. Sarah Wilson</strong>
                        <span id="doctorSpec" class="text-muted" style="font-size:0.82rem;">Cardiologist</span>
                    </div>
                </div>

                <span class="section-label">Select Date</span>
                <input type="date" id="appointmentDate" name="appointmentDate"
                    class="form-control rounded-3 mb-1"
                    style="max-width:260px;"
                    data-validation="required">
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

                <span class="section-label">Payment Method</span>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="payment-card active-payment">
                            <i class="bi bi-cash-stack me-2"></i>Cash
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card">
                            <i class="bi bi-phone me-2"></i>UPI
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card">
                            <i class="bi bi-credit-card me-2"></i>Card
                        </div>
                    </div>
                </div>

                <button id="confirmBtn" class="btn-confirm" disabled>
                    <i class="bi bi-calendar-check me-2"></i>Confirm Appointment
                </button>

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
    let selectedTime = null;
    let selectedDate = null;

    // Department selection
    document.querySelectorAll(".dept-card").forEach(card => {
        card.addEventListener("click", function() {
            document.querySelectorAll(".dept-card").forEach(c => c.classList.remove("active-department"));
            this.classList.add("active-department");

            const dept = this.dataset.dept;
            const doctor = this.dataset.doctor;
            const spec = this.dataset.spec;
            const initials = this.dataset.initials;
            const fee = parseInt(this.dataset.fee);

            document.getElementById("summaryDept").innerText = dept;
            document.getElementById("summaryDoctor").innerText = doctor;
            document.getElementById("summaryFee").innerText = fee;
            document.getElementById("summaryTotal").innerText = fee + 20;
            document.getElementById("doctorName").innerText = doctor;
            document.getElementById("doctorSpec").innerText = spec;
            document.getElementById("doctorAvatar").innerText = initials;
        });
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
            document.getElementById("summaryTime").innerText = selectedTime;
            validateForm();
        });
    });

    // Payment
    document.querySelectorAll(".payment-card").forEach(card => {
        card.addEventListener("click", function() {
            document.querySelectorAll(".payment-card").forEach(c => c.classList.remove("active-payment"));
            this.classList.add("active-payment");
        });
    });

    function validateForm() {
        document.getElementById("confirmBtn").disabled = !(selectedDate && selectedTime);
    }

    document.getElementById("confirmBtn").addEventListener("click", function() {
        new bootstrap.Modal(document.getElementById("successModal")).show();
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>