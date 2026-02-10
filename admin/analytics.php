<?php
$content_page = 'analytics';
ob_start();
?>
<main class="admin-dashboard" style="margin-top:20px;">
<div class="container">

    <!-- Page Header -->
    <div class="features-header text-center mb-2">
        <h2>System <span>Analytics</span></h2>
        <div class="section-divider"></div>
        <p>Performance insights and operational metrics</p>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-2">

        <div class="col-md-3">
            <div class="feature-acard text-center">
                <h6>Appointments Today</h6>
                <h3>86</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-acard text-center">
                <h6>Avg Waiting Time</h6>
                <h3>18 min</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-acard text-center">
                <h6>Completion Rate</h6>
                <h3>92%</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="feature-acard text-center">
                <h6>Emergency Ratio</h6>
                <h3 class="text-danger">6%</h3>
            </div>
        </div>

    </div>

    <!-- Clinic Performance -->
    <div class="feature-acard mb-4">
        <h5 class="mb-3">Clinic Performance Summary</h5>

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Clinic</th>
                    <th>Appointments</th>
                    <th>Avg Wait</th>
                    <th>Doctors</th>
                    <th>Efficiency</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Main Clinic</td>
                    <td>52</td>
                    <td>15 min</td>
                    <td>6</td>
                    <td>
                        <span class="badge bg-success">High</span>
                    </td>
                </tr>

                <tr>
                    <td>Branch Clinic</td>
                    <td>34</td>
                    <td>26 min</td>
                    <td>3</td>
                    <td>
                        <span class="badge bg-warning text-dark">Moderate</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Doctor Workload -->
    <div class="feature-acard mb-4">
        <h5 class="mb-3">Doctor Workload Comparison</h5>

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Clinic</th>
                    <th>Patients Seen</th>
                    <th>Avg Consultation</th>
                    <th>Load Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Dr. Raj Patel</td>
                    <td>Main Clinic</td>
                    <td>24</td>
                    <td>12 min</td>
                    <td>
                        <span class="badge bg-success">Balanced</span>
                    </td>
                </tr>

                <tr>
                    <td>Dr. Meena Shah</td>
                    <td>Branch Clinic</td>
                    <td>18</td>
                    <td>17 min</td>
                    <td>
                        <span class="badge bg-warning text-dark">Heavy</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Trend Placeholder
    <div class="feature-acard text-center">
        <h5>Trends & Reports</h5>
        <p class="text-muted mb-0">
            Appointment trends, waiting time graphs, and workload charts
            can be visualized here using Chart.js.
        </p>
    </div> -->

</div>
</main>
<?php
$content = ob_get_clean();
include './admin-layout.php';
?>