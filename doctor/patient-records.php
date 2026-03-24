<?php
include "doctor-auth.php";

$patientId = $_GET['patient'] ?? "P1021";

$patients = [
    "P1021" => [
        "name"         => "Rahul Patel",
        "age"          => 32,
        "gender"       => "Male",
        "phone"        => "98XXXXXX21",
        "registered"   => "15 Aug 2025",
        "total_visits" => 5,
        "last_visit"   => "12 Jan 2026",
        "frequency"    => "Once in 2 months",
        "notes"        => "Recurrent fever complaints. Blood test advised.",
        "history"      => [
            [
                "date"  => "12 Jan 2026",
                "type"  => "Follow-up",
                "token" => "21",
                "note"  => "Fever reduced, advised rest."
            ]
        ]
    ],
    "P1044" => [
        "name"         => "Anita Shah",
        "age"          => 45,
        "gender"       => "Female",
        "phone"        => "97XXXXXX44",
        "registered"   => "02 Mar 2024",
        "total_visits" => 8,
        "last_visit"   => "05 Jan 2026",
        "frequency"    => "Once a month",
        "notes"        => "Hypertension monitoring.",
        "history"      => [
            [
                "date"  => "05 Jan 2026",
                "type"  => "Follow-up",
                "token" => "19",
                "note"  => "BP stable. Continue medication."
            ]
        ]
    ],
    "P1102" => [
        "name"         => "Mohit Kumar",
        "age"          => 29,
        "gender"       => "Male",
        "phone"        => "96XXXXXX02",
        "registered"   => "10 Oct 2025",
        "total_visits" => 3,
        "last_visit"   => "10 Jan 2026",
        "frequency"    => "Occasional",
        "notes"        => "Emergency visit for chest pain.",
        "history"      => [
            [
                "date"  => "10 Jan 2026",
                "type"  => "Emergency",
                "token" => "E3",
                "note"  => "Acute chest pain. Referred to hospital."
            ]
        ]
    ]
];

// FIX: fallback to first patient if an invalid ID is passed via URL
if (!isset($patients[$patientId])) {
    $patientId = "P1021";
}

$current = $patients[$patientId];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FIX: corrected page title -->
    <title>MediQueue | Patient Records</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <!-- HEADER -->
        <section class="features-header my-1">
            <h2>Patient <span>Records</span></h2>
            <p class="text-muted text-decoration-underline mx-0 mb-3">Search and review registered patients</p>
        </section>

        <!-- PATIENT TABLE -->
        <section class="row g-4">

            <div class="col-lg-7">
                <div class="dcard">
                    <div class="card-header">Patient List</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Last Visit</th>
                                    <th>Total Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($patients as $id => $data): ?>
                                    <tr class="<?php echo ($id === $patientId) ? 'table-active' : ''; ?>">
                                        <td>
                                            <a href="?patient=<?php echo htmlspecialchars($id); ?>">
                                                <?php echo htmlspecialchars($id); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($data['name']); ?><br>
                                            <small class="text-muted">
                                                <?php echo (int)$data['age']; ?> / <?php echo htmlspecialchars($data['gender']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo htmlspecialchars($data['last_visit']); ?></td>
                                        <td><?php echo (int)$data['total_visits']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PROFILE PANEL -->
            <div class="col-lg-5">

                <div class="dcard mb-3">
                    <div class="card-header">Patient Profile</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($current['name']); ?></p>
                        <p><strong>Age / Gender:</strong> <?php echo (int)$current['age']; ?> / <?php echo htmlspecialchars($current['gender']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($current['phone']); ?></p>
                        <p><strong>Registered On:</strong> <?php echo htmlspecialchars($current['registered']); ?></p>
                        <hr>
                        <p class="mb-1"><strong>Medical Notes</strong></p>
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($current['notes']); ?></p>
                    </div>
                </div>

                <div class="dcard mb-3">
                    <div class="card-header">Visit Summary</div>
                    <div class="card-body">
                        <p><strong>Total Visits:</strong> <?php echo (int)$current['total_visits']; ?></p>
                        <p><strong>Last Visit:</strong> <?php echo htmlspecialchars($current['last_visit']); ?></p>
                        <p class="mb-0"><strong>Avg Visit Frequency:</strong> <?php echo htmlspecialchars($current['frequency']); ?></p>
                    </div>
                </div>

                <div class="dcard">
                    <div class="card-body">
                        <button class="btn btn-outline-secondary w-100" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Patient History
                        </button>
                    </div>
                </div>

            </div>

        </section>

        <!-- VISIT HISTORY -->
        <section class="mt-4">
            <div class="dcard">
                <div class="card-header">Visit History</div>
                <div class="card-body">
                    <ul class="visit-timeline">
                        <?php foreach ($current['history'] as $visit): ?>
                            <li class="<?php echo ($visit['type'] === 'Emergency') ? 'emergency-visit' : ''; ?>">

                                <span class="visit-date"><?php echo htmlspecialchars($visit['date']); ?></span>

                                <?php if ($visit['type'] === 'Emergency'): ?>
                                    <span class="badge type-emergency">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                    </span>
                                <?php else: ?>
                                    <span class="badge type-follow">Follow-up</span>
                                <?php endif; ?>

                                <span class="badge status-completed">Completed</span>

                                <p class="mt-1 mb-0">Token #<?php echo htmlspecialchars($visit['token']); ?></p>
                                <small class="text-muted">Notes: <?php echo htmlspecialchars($visit['note']); ?></small>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <!-- FIX: Bootstrap JS moved to bottom of body -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>