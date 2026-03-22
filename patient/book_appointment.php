<?php
$content_page = 'book-appointment | MediQueue';
ob_start();
?>

<!-- FULL WIDTH WRAPPER -->
<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Schedule your visit</small>
        <h3>Book Appointment</h3>
    </div>

    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-xl-8 col-lg-7">

            <div class="card-glass">

                <h6 class="section-title">Select Department</h6>

                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="department-card active-department"
                            data-dept="Cardiology"
                            data-doctor="Dr. Sarah Wilson"
                            data-fee="500">
                            <h6>Cardiology</h6>
                            <small>Heart Specialists</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="department-card"
                            data-dept="Orthopedics"
                            data-doctor="Dr. Michael Ray"
                            data-fee="450">
                            <h6>Orthopedics</h6>
                            <small>Bone & Joint Care</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="department-card"
                            data-dept="Dermatology"
                            data-doctor="Dr. Emily Stone"
                            data-fee="350">
                            <h6>Dermatology</h6>
                            <small>Skin Treatment</small> 
                        </div>
                    </div>

                </div>

                <h6 class="section-title">Doctor</h6>
                <div class="doctor-card mb-4">
                    <strong id="doctorName">Dr. Sarah Wilson</strong>
                </div>

                <h6 class="section-title">Select Date</h6>
                <input type="date" id="appointmentDate" class="form-control mb-4" name="appointmentDate" data-validation="required">
                <small id="appointmentDate_error"></small>

                <h6 class="section-title">Select Time Slot</h6>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="slot">09:00 AM</div>
                    </div>
                    <div class="col-4">
                        <div class="slot">10:30 AM</div>
                    </div>
                    <div class="col-4">
                        <div class="slot">12:00 PM</div>
                    </div>
                    <div class="col-4">
                        <div class="slot">02:00 PM</div>
                    </div>
                    <div class="col-4">
                        <div class="slot">03:30 PM</div>
                    </div>
                </div>

                <h6 class="section-title">Payment Method</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="payment-card active-payment">Cash</div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card">UPI</div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-card">Card</div>
                    </div>
                </div>

                <button id="confirmBtn" class="btn-confirm w-100">
                    Confirm Appointment
                </button>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-xl-4 col-lg-5">

            <div class="card-glass summary-sticky">

                <h6 class="section-title">Appointment Summary</h6>

                <div class="summary-box mb-3">
                    <p><strong>Department:</strong> <span id="summaryDept">Cardiology</span></p>
                    <p><strong>Doctor:</strong> <span id="summaryDoctor">Dr. Sarah Wilson</span></p>
                    <p><strong>Date:</strong> <span id="summaryDate">--</span></p>
                    <p><strong>Time:</strong> <span id="summaryTime">--</span></p>
                </div>

                <div class="summary-box">
                    <p>Consultation Fee: ₹<span id="summaryFee">500</span></p>
                    <p>Platform Fee: ₹20</p>
                    <hr>
                    <h6>Total: ₹<span id="summaryTotal">520</span></h6>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5>Appointment Booked Successfully 🎉</h5>
                <p>Your appointment has been scheduled.</p>
                <button class="btn btn-brand" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedTime = null;
    let selectedDate = null;
    const confirmBtn = document.getElementById("confirmBtn");

    /* Department Selection */
    document.querySelectorAll(".department-card").forEach(card => {
        card.addEventListener("click", function() {
            document.querySelectorAll(".department-card").forEach(c => c.classList.remove("active-department"));
            this.classList.add("active-department");

            let dept = this.dataset.dept;
            let doctor = this.dataset.doctor;
            let fee = parseInt(this.dataset.fee);

            document.getElementById("summaryDept").innerText = dept;
            document.getElementById("summaryDoctor").innerText = doctor;
            document.getElementById("doctorName").innerText = doctor;
            document.getElementById("summaryFee").innerText = fee;
            document.getElementById("summaryTotal").innerText = fee + 20;
        });
    });

    /* Date */
    document.getElementById("appointmentDate").addEventListener("change", function() {
        selectedDate = this.value;
        document.getElementById("summaryDate").innerText = selectedDate;
        validateForm();
    });

    /* Time */
    document.querySelectorAll(".slot").forEach(slot => {
        slot.addEventListener("click", function() {
            document.querySelectorAll(".slot").forEach(s => s.classList.remove("active-slot"));
            this.classList.add("active-slot");
            selectedTime = this.innerText;
            document.getElementById("summaryTime").innerText = selectedTime;
            validateForm();
        });
    });

    /* Payment */
    document.querySelectorAll(".payment-card").forEach(card => {
        card.addEventListener("click", function() {
            document.querySelectorAll(".payment-card").forEach(c => c.classList.remove("active-payment"));
            this.classList.add("active-payment");
        });
    });

    /* Validation */
    function validateForm() {
        confirmBtn.disabled = !(selectedDate && selectedTime);
    }

    /* Confirm */
    confirmBtn.addEventListener("click", function() {
        new bootstrap.Modal(document.getElementById("successModal")).show();
    });
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>