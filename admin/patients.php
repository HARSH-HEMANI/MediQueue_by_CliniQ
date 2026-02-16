<?php
$content_page = 'patient-management';
ob_start();
?>
<main class="admin-dashboard" style="margin-top:20px;">
        <div class="container">

            <!-- Header -->
            <div class="features-header text-center mb-2">
                <h2>Patient <span>Management</span></h2>
                <div class="section-divider"></div>
                <p>overview of registered patients</p>
            </div>

            <!-- Search Bar -->
            <div class="feature-acard mb-2">
                <form class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control"
                            placeholder="Search by name or phone">
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
                        <button class="hero-btn w-100" type="button">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Patient Table -->
            <div class="feature-acard">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Clinic</th>
                            <th>Doctor</th>
                            <th>Visits</th>
                            <th>Last Visit</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>PT-1023</td>
                            <td>Ramesh Patel</td>
                            <td>+91 98XXXXXXX</td>
                            <td>Main Clinic</td>
                            <td>Dr. Raj Patel</td>
                            <td>4</td>
                            <td>12 Feb 2026</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                        </tr>

                        <tr>
                            <td>PT-1089</td>
                            <td>Sneha Mehta</td>
                            <td>+91 97XXXXXXX</td>
                            <td>Branch Clinic</td>
                            <td>Dr. Meena Shah</td>
                            <td>1</td>
                            <td>02 Feb 2026</td>
                            <td>
                                <span class="badge bg-secondary">Inactive</span>
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