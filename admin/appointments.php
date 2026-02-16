<?php
$content_page = 'appointment';
ob_start();
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <!-- Page Header -->
        <div class="features-header text-center mb-2">
            <h2>Appointment <span>Monitoring</span></h2>
            <div class="section-divider"></div>
            <p>System-wide appointment overview</p>
        </div>

        <!-- Filters -->
        <div class="feature-acard mb-2">
            <form class="row g-3 align-items-center">

                <div class="col-md-3">
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Clinics</option>
                        <option>Main Clinic</option>
                        <option>Branch Clinic</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Doctors</option>
                        <option>Dr. Raj Patel</option>
                        <option>Dr. Meena Shah</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select">
                        <option value="">All Status</option>
                        <option>Scheduled</option>
                        <option>Completed</option>
                        <option>Delayed</option>
                        <option>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="button" class="hero-btn w-90">
                        Filter
                    </button>
                </div>

            </form>
        </div>

        <!-- Appointment Table -->
        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Clinic</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Queue</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>APT-2401</td>
                        <td>Ramesh Patel</td>
                        <td>Dr. Raj Patel</td>
                        <td>Main Clinic</td>
                        <td>15 Feb 2026</td>
                        <td>10:30 AM</td>
                        <td>
                            <span class="badge bg-success">Completed</span>
                        </td>
                        <td>—</td>
                    </tr>

                    <tr>
                        <td>APT-2408</td>
                        <td>Sneha Mehta</td>
                        <td>Dr. Meena Shah</td>
                        <td>Branch Clinic</td>
                        <td>15 Feb 2026</td>
                        <td>11:15 AM</td>
                        <td>
                            <span class="badge bg-warning text-dark">Delayed</span>
                        </td>
                        <td>+12 min</td>
                    </tr>

                    <tr>
                        <td>APT-2412</td>
                        <td>Amit Joshi</td>
                        <td>Dr. Raj Patel</td>
                        <td>Main Clinic</td>
                        <td>15 Feb 2026</td>
                        <td>12:00 PM</td>
                        <td>
                            <span class="badge bg-secondary">Scheduled</span>
                        </td>
                        <td>Waiting</td>
                    </tr>

                    <tr>
                        <td>APT-2420</td>
                        <td>Neha Shah</td>
                        <td>Dr. Meena Shah</td>
                        <td>Branch Clinic</td>
                        <td>14 Feb 2026</td>
                        <td>04:30 PM</td>
                        <td>
                            <span class="badge bg-danger">Cancelled</span>
                        </td>
                        <td>—</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>