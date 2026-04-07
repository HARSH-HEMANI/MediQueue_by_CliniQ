<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

$content_page = 'Live Queue | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;">
            Real-time clinic updates
        </small>
        <h3 class="fw-bold mt-1">Live Queue Monitor</h3>
    </div>

    <div class="row g-4" id="mainContent">

        <!-- LEFT -->
        <div class="col-xl-8 col-lg-7">

            <div class="now-serving-display mb-4">
                <span class="section-label">Now Serving</span>
                <div class="token-number" id="currentToken">--</div>
                <div class="token-label" id="doctorInfo">Loading...</div>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue List</h6>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Token</th>
                                <th>Patient</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="queueTable">
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Loading queue...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-xl-4 col-lg-5">

            <div class="p-card mb-4 text-center">
                <span class="section-label">Your Position</span>
                <div class="display-4 fw-bold text-brand" id="positionNumber">--</div>

                <p class="text-muted">Patients Ahead</p>

                <div class="progress mb-3" style="height:8px;">
                    <div id="queueProgress" class="progress-bar progress-bar-brand" style="width:0%"></div>
                </div>

                <p>
                    Estimated Wait: <strong id="waitTime">--</strong> min
                </p>

                <div id="alertArea" class="alert mt-3">
                    Please wait...
                </div>
            </div>

            <div class="p-card">
                <h6 class="fw-bold mb-3">Queue Metrics</h6>

                <div class="d-flex justify-content-between">
                    <span>Total Patients</span>
                    <strong id="totalPatients">--</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Your Token</span>
                    <strong id="myToken">--</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Status</span>
                    <strong class="text-success">Available</strong>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- SOUND -->
<audio id="alertSound" src="notify.mp3" preload="auto"></audio>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let lastData = "";
let alerted = false;

function updateUI(res) {

    $("#currentToken").text("#" + res.current_token);
    $("#doctorInfo").text("Dr. " + res.doctor_name + " · " + res.specialization);

    $("#positionNumber").text(res.position);
    $("#waitTime").text(res.wait_time);
    $("#queueProgress").css("width", res.progress + "%");
    $("#myToken").text("#" + res.my_token);
    $("#totalPatients").text(res.queue.length);

    let html = "";

    res.queue.forEach(function(item) {

        let isMe = (item.token_no == res.my_token);
        let isServing = (item.token_no == res.current_token);

        let rowClass = isServing ? "queue-row-active" :
                       (isMe ? "queue-row-you" : "");

        let name = isMe ? "You" :
            item.patient_name.split(" ")[0];

        let status = isServing ?
            '<span class="badge-soft-success">Consulting</span>' :
            '<span class="badge-soft-warning">Waiting</span>';

        html += `
            <tr class="${rowClass}">
                <td><strong>#${item.token_no}</strong></td>
                <td>${name}</td>
                <td>${status}</td>
            </tr>
        `;
    });

    $("#queueTable").html(html);

    // ALERT + SOUND
    if (res.position == 0) {

        $("#alertArea").html(`
            <strong class="text-success">
                It's your turn! Please go to doctor.
            </strong>
        `);

        if (!alerted) {
            document.getElementById("alertSound").play();
            alerted = true;
        }

    } else {

        $("#alertArea").html(`
            Please wait. You will be notified.
        `);

        alerted = false;
    }
}

function loadLiveQueue() {
    $.ajax({
        url: "fetch_live_queue.php",
        method: "GET",
        dataType: "json",
        success: function(res) {

            if (res.no_queue) {
                $("#mainContent").html(`
                    <div class="col-12 text-center py-5">
                        <h5>No Active Queue</h5>
                    </div>
                `);
                return;
            }

            let newData = JSON.stringify(res);

            if (newData !== lastData) {
                lastData = newData;
                updateUI(res);
            }
        }
    });
}

// Poll every 5 sec
setInterval(loadLiveQueue, 5000);

// Initial load
loadLiveQueue();
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>