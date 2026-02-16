<?php
$content_page = 'live-queue';
ob_start();
?>

<main class="admin-dashboard" style="margin-top:20px;">
<div class="container">

    <!-- Page Header -->
    <div class="features-header text-center mb-2">
        <h2>Live <span>Queue Monitoring</span></h2>
        <div class="section-divider"></div>
        <p>Real-time overview of clinic queues (read-only)</p>
    </div>

    <!-- Queue Summary Cards -->
    <div class="row mb-2">

        <div class="col-md-4">
            <div class="feature-acard text-center">
                <h6>Total Waiting Patients</h6>
                <h3>18</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-acard text-center">
                <h6>Clinics Under Load</h6>
                <h3 class="text-warning">1</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-acard text-center">
                <h6>Emergency Cases Today</h6>
                <h3 class="text-danger">2</h3>
            </div>
        </div>

    </div>

    <!-- Queue Table -->
    <div class="feature-acard">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Clinic</th>
                    <th>Doctor</th>
                    <th>Current Token</th>
                    <th>Waiting</th>
                    <th>Avg Wait</th>
                    <th>Emergency</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Main Clinic</td>
                    <td>Dr. Raj Patel</td>
                    <td>12</td>
                    <td>6</td>
                    <td>15 min</td>
                    <td>
                        <span class="badge bg-danger">1</span>
                    </td>
                    <td>
                        <span class="badge bg-success">Smooth</span>
                    </td>
                </tr>

                <tr>
                    <td>Branch Clinic</td>
                    <td>Dr. Meena Shah</td>
                    <td>8</td>
                    <td>12</td>
                    <td>30 min</td>
                    <td>
                        <span class="badge bg-warning text-dark">1</span>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">Busy</span>
                    </td>
                </tr>

                <tr>
                    <td>Main Clinic</td>
                    <td>Dr. Amit Joshi</td>
                    <td>5</td>
                    <td>0</td>
                    <td>—</td>
                    <td>0</td>
                    <td>
                        <span class="badge bg-secondary">Idle</span>
                    </td>
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