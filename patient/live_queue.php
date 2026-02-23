<?php
$content_page = 'Live Queue | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Real-time clinic updates</small>
        <h3>Live Queue Monitor</h3>
    </div>

    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-xl-8 col-lg-7">

            <!-- Now Serving -->
            <div class="card-glass mb-4 text-center">

                <h6 class="section-title">Now Serving</h6>

                <div class="now-serving">
                    <h1 id="currentToken" class="display-4 fw-bold">#101</h1>
                    <p class="text-muted">Dr. Sarah Wilson – Cardiology</p>
                </div>

            </div>

            <!-- Queue List -->
            <div class="card-glass">

                <h6 class="section-title">Queue List</h6>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Token</th>
                                <th>Patient</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="queueTable">

                            <tr class="consulting">
                                <td>#101</td>
                                <td>Ravi Patel</td>
                                <td><span class="badge-confirmed">Consulting</span></td>
                            </tr>

                            <tr>
                                <td>#102</td>
                                <td>Meera Shah</td>
                                <td><span class="badge-pending">Waiting</span></td>
                            </tr>

                            <tr class="your-row">
                                <td>#103</td>
                                <td><strong>You</strong></td>
                                <td><span class="badge bg-primary">Waiting</span></td>
                            </tr>

                            <tr>
                                <td>#104</td>
                                <td>Ankit Verma</td>
                                <td><span class="badge-pending">Waiting</span></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-xl-4 col-lg-5">

            <!-- Your Status -->
            <div class="card-glass mb-4 text-center">

                <h6 class="section-title">Your Status</h6>

                <h2 id="positionNumber" class="fw-bold">2</h2>
                <p class="text-muted">Patients Ahead</p>

                <div class="progress mb-3">
                    <div id="queueProgress"
                        class="progress-bar bg-success"
                        style="width:40%">
                    </div>
                </div>

                <p>
                    <strong>Estimated Wait:</strong>
                    <span id="waitTime">15</span> min
                </p>

                <div id="alertArea" class="mt-3 text-muted">
                    Please wait. You will be notified.
                </div>

                <button id="arriveBtn"
                    class="btn btn-brand mt-3 d-none">
                    Mark as Arrived
                </button>

            </div>

            <!-- Queue Metrics -->
            <div class="card-glass">

                <h6 class="section-title">Queue Metrics</h6>

                <p><strong>Total Patients Today:</strong> 28</p>
                <p><strong>Average Consultation:</strong> 7 min</p>
                <p>
                    <strong>Doctor Status:</strong>
                    <span class="text-success">Available</span>
                </p>

            </div>

        </div>

    </div>
</div>

<script>
    let position = 2;
    let waitTime = 15;
    let currentToken = 101;

    const queueTable = document.getElementById("queueTable");
    const positionNumber = document.getElementById("positionNumber");
    const waitTimeText = document.getElementById("waitTime");
    const progressBar = document.getElementById("queueProgress");
    const currentTokenDisplay = document.getElementById("currentToken");
    const alertArea = document.getElementById("alertArea");
    const arriveBtn = document.getElementById("arriveBtn");

    function updateQueue() {

        if (position > 0) {

            const firstRow = queueTable.querySelector("tr");

            firstRow.classList.remove("consulting");
            firstRow.querySelector("td:last-child").innerHTML =
                "<span class='badge bg-secondary'>Completed</span>";

            queueTable.removeChild(firstRow);

            currentToken++;
            currentTokenDisplay.innerText = "#" + currentToken;

            position--;
            waitTime -= 5;

            positionNumber.innerText = position;
            waitTimeText.innerText = waitTime;

            let progress = 100 - (position * 40);
            progressBar.style.width = progress + "%";

            const newFirstRow = queueTable.querySelector("tr");

            if (newFirstRow) {
                newFirstRow.classList.add("consulting");
                newFirstRow.querySelector("td:last-child").innerHTML =
                    "<span class='badge-confirmed'>Consulting</span>";
            }

            document.querySelectorAll(".your-row").forEach(row => {
                row.classList.remove("your-row");
            });

            const rows = queueTable.querySelectorAll("tr");

            if (rows[position]) {
                rows[position].classList.add("your-row");
            }
        }

        if (position === 0) {

            alertArea.innerHTML =
                "<strong class='text-success'>It's your turn! Please proceed to Cabin 2.</strong>";

            arriveBtn.classList.remove("d-none");

            const yourRow = queueTable.querySelector("tr");

            if (yourRow) {
                yourRow.classList.add("consulting");
                yourRow.querySelector("td:last-child").innerHTML =
                    "<span class='badge-confirmed'>Consulting</span>";
            }
        }
    }

    setInterval(updateQueue, 30000);
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>