<?php
$content_page = 'Live Queue | Reception | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Real-time management</small>
            <h1 class="dashboard-title mt-1">Live <span>Queue</span></h1>
            <p class="dashboard-subtitle">Track and manage patient queue in real time</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
            <button class="btn btn-brand rounded-pill">
                <i class="bi bi-pause-circle me-1"></i>Pause Queue
            </button>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stats-row mb-4">
        <div class="rstat-card">
            <h6>Total Tokens</h6>
            <h2>25</h2>
        </div>
        <div class="rstat-card">
            <h6>Waiting</h6>
            <h2>12</h2>
        </div>
        <div class="rstat-card">
            <h6>In Progress</h6>
            <h2>2</h2>
        </div>
        <div class="rstat-card">
            <h6>Completed</h6>
            <h2>11</h2>
        </div>
    </div>

    <!-- Current Token -->
    <div class="dcard current-token-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-success mb-2">Now Consulting</span>
                <div class="current-token-number mt-1">Token <span>#21</span></div>
                <div class="r-patient-name mt-1">
                    Rahul Patel
                    <span class="text-muted fw-normal" style="font-size:0.9rem;">(32 / Male)</span>
                </div>
                <small class="text-muted mt-1 d-block">
                    <i class="bi bi-person-badge me-1"></i>Dr. Mehta &nbsp;·&nbsp; Room 2
                </small>
            </div>
            <div class="d-flex flex-column gap-2">
                <button class="btn btn-brand rounded-pill"><i class="bi bi-arrow-right-circle me-1"></i>Call Next</button>
                <button class="btn btn-outline-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Complete</button>
                <button class="btn btn-outline-warning rounded-pill"><i class="bi bi-skip-forward-circle me-1"></i>Skip</button>
            </div>
        </div>
    </div>

    <!-- Queue Table -->
    <div class="rcard mb-4">
        <div class="rcard-body">
            <h5 class="mb-3">Queue List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="r-thead">
                            <th>Token</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#13</strong></td>
                            <td><strong>Rahul Sharma</strong>
                                <div class="text-muted small">9876543210</div>
                            </td>
                            <td>Dr. Mehta</td>
                            <td class="text-muted" style="font-size:0.88rem;">10:30 AM</td>
                            <td><span class="badge-soft-primary">Follow-up</span></td>
                            <td><span class="badge-soft-warning">Waiting</span></td>
                            <td><button class="btn btn-brand btn-sm rounded-pill px-3">Call</button></td>
                        </tr>
                        <tr>
                            <td><strong>#14</strong></td>
                            <td><strong>Anita Patel</strong>
                                <div class="text-muted small">9123456780</div>
                            </td>
                            <td>Dr. Shah</td>
                            <td class="text-muted" style="font-size:0.88rem;">11:00 AM</td>
                            <td><span class="badge-soft-danger">Emergency</span></td>
                            <td><span class="badge-soft-warning">Waiting</span></td>
                            <td><button class="btn btn-brand btn-sm rounded-pill px-3">Call</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="d-flex gap-3 flex-wrap">
        <button class="btn btn-brand rounded-pill px-4">
            <i class="bi bi-arrow-right me-1"></i>Call Next Patient
        </button>
        <button class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-trash me-1"></i>Clear Completed
        </button>
    </div>

</div>

?>clude './reception-layout.php';
<?php
$content = ob_get_clean();
include './reception-layout.php';
?>clude './reception-layout.php';
?>