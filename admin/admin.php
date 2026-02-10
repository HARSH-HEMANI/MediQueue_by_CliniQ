<?php
$content_page = 'admin-home';
ob_start();
?>
    <main class="admin-dashboard" style="margin-top:20px;">
        <div class="container">

            <div class="features-header text-center">
                <h2>System <span>Overview</span></h2>
                <div class="section-divider"></div>
                <p>Bird's-eye view of MediQueue system health</p>
            </div>

            <div class="row">

                <div class="col-md-3 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Total Doctors</h6>
                        <h3>12</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Total Patients</h6>
                        <h3>1,240</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Today's Appointments</h6>
                        <h3>86</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Active Clinics</h6>
                        <h3>5</h3>
                    </div>
                </div>

            </div>

            <div class="row mt-4">

                <div class="col-md-4 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Avg Waiting Time (Today)</h6>
                        <h3>18 min</h3>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Doctors On Duty</h6>
                        <h3>8 / 12</h3>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-acard text-center">
                        <h6>Queue Status</h6>
                        <h3 class="text-brand">Normal</h3>
                    </div>
                </div>

            </div>
            <div class="feature-acard mt-4">
                <h5 class="mb-3">Clinic Overview</h5>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Clinic</th>
                            <th>Doctors</th>
                            <th>Patients Today</th>
                            <th>Queue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Main Clinic</td>
                            <td>6</td>
                            <td>48</td>
                            <td><span class="badge bg-success">Smooth</span></td>
                        </tr>
                        <tr>
                            <td>Branch Clinic</td>
                            <td>3</td>
                            <td>32</td>
                            <td><span class="badge bg-warning">Busy</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="feature-acard mt-4">
                <h5 class="mb-3">Recent System Activity</h5>

                <ul class="list-unstyled mb-0">
                    <li>✔ New doctor registered: Dr. Mehta</li>
                    <li>✔ Clinic hours updated: Main Clinic</li>
                    <li>✔ Receptionist activated: Anita Shah</li>
                    <li>✔ Emergency case logged at Branch Clinic</li>
                </ul>
            </div>


        </div>
    </main>
<?php
$content = ob_get_clean();
include './admin-layout.php';
?>