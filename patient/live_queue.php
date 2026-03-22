<?php
$content_page = 'Live Queue | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Real-time clinic updates</small>
        <h3 class="fw-bold mb-0 mt-1">Live Queue Monitor</h3>
    </div>

    <div class="row g-4">
        <div class="col-xl-8 col-lg-7">

            <div class="now-serving-display mb-4">
                <span class="section-label">Now Serving</span>
                <div class="token-number" id="currentToken">#101</div>
                <div class="token-label">Dr. Sarah Wilson &nbsp;·&nbsp; Cardiology</div>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue List</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Token</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Patient</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size:0.75rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="queueTable">
                            <tr class="queue-row-active">
                                <td><strong>#101</strong></td>
                                <td>Ravi Patel</td>
                                <td><span class="badge-soft-success">Consulting</span></td>
                            </tr>
                            <tr>
                                <td><strong>#102</strong></td>
                                <td>Meera Shah</td>
                                <td><span class="badge-soft-warning">Waiting</span></td>
                            </tr>
                            <tr class="queue-row-you">
                                <td><strong>#103</strong></td>
                                <td>You <i class="bi bi-person-fill text-brand ms-1"></i></td>
                                <td><span class="badge bg-primary">Waiting</span></td>
                            </tr>
                            <tr>
                                <td><strong>#104</strong></td>
                                <td>Ankit Verma</td>
                                <td><span class="badge-soft-warning">Waiting</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-5">

            <div class="p-card mb-4 text-center">
                <span class="section-label">Your Position</span>
                <div class="display-4 fw-bold text-brand" id="positionNumber">2</div>
                <p class="text-muted mb-3" style="font-size:0.85rem;">Patients Ahead</p>

                <div class="progress mb-3" style="height:8px;border-radius:20px;">
                    <div id="queueProgress" class="progress-bar progress-bar-brand" style="width:40%"></div>
                </div>

                <p class="text-muted" style="font-size:0.9rem;">
                    <i class="bi bi-clock me-1"></i>Estimated Wait: <strong id="waitTime">15</strong> min
                </p>

                <div id="alertArea" class="alert alert-light border-0 mt-3 text-muted" style="font-size:0.84rem;background:var(--brand-soft)!important;">
                    <i class="bi bi-info-circle me-1 text-brand"></i>Please wait. You will be notified when it's your turn.
                </div>

                <button id="arriveBtn" class="btn btn-brand w-100 mt-2 d-none">
                    <i class="bi bi-check2-circle me-1"></i>Mark as Arrived
                </button>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue Metrics</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Total Patients Today</span><strong>28</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Avg Consultation</span><strong>7 min</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:0.88rem;">
                        <span class="text-muted">Doctor Status</span>
                        <strong class="text-success"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Available</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let position = 2,
        waitTime = 15,
        currentToken = 101;
    const queueTable = document.getElementById("queueTable");

    function updateQueue() {
        if (position > 0) {
            const first = queueTable.querySelector("tr");
            first.classList.remove("queue-row-active");
            first.querySelector("td:last-child").innerHTML = "<span class='badge bg-secondary'>Done</span>";
            queueTable.removeChild(first);
            currentToken++;
            document.getElementById("currentToken").innerText = "#" + currentToken;
            position--;
            waitTime = Math.max(0, waitTime - 7);
            document.getElementById("positionNumber").innerText = position;
            document.getElementById("waitTime").innerText = waitTime;
            document.getElementById("queueProgress").style.width = Math.min(100, 100 - position * 35) + "%";
            const newFirst = queueTable.querySelector("tr");
            if (newFirst) {
                newFirst.classList.add("queue-row-active");
                newFirst.querySelector("td:last-child").innerHTML = "<span class='badge-soft-success'>Consulting</span>";
            }
        }
        if (position === 0) {
            document.getElementById("alertArea").innerHTML = "<strong class='text-success'><i class='bi bi-check-circle-fill me-1'></i>It's your turn! Proceed to Cabin 2.</strong>";
            document.getElementById("arriveBtn").classList.remove("d-none");
        }
    }
    setInterval(updateQueue, 30000);
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>