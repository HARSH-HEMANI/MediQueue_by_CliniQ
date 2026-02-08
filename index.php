<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | MediQueue</title>

    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <!-- ------------- NAVBAR ---------------- -->
    <header>
        <?php include "./header.php"; ?>

        <div style="background:#FF5A5F;color:#fff;text-align:center;padding:10px;margin-top:88px;">
            <h2>SMART CLINIC MANAGEMENT SYSTEM</h2>
        </div>
    </header>

    <!-- ------------------ MAIN ----------------- -->
    <main>

        <section class="hero">
            <div class="hero-content">
                <h1>
                    Optimize Patient Flow.<br>
                    Reduce Waiting Time.<br>
                    <span>Improve Care.</span>
                </h1>

                <p>
                    MediQueue by CliniQ is a smart clinic management platform
                    that combines appointment scheduling with real-time
                    queue monitoring and workload analysis, helping clinics
                    run smoothly and efficiently every day.
                </p>

                <a href="#" class="hero-btn">Explore MediQueue</a>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="features">
            <div class="container">

                <!-- Section Heading -->
                <div class="features-header text-center">
                    <h2>
                        Smart Patient Flow <span>&</span> Clinic Analytics
                    </h2>
                    <div class="section-divider"></div>
                    <p>
                        A comprehensive clinic management system designed to monitor appointments,
                        analyze patient flow, and optimize daily clinic workload for better efficiency
                        and reduced waiting time.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="row justify-content-center">

                    <!-- Feature Card -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/appointment.png" alt="">
                            <h5>Smart Appointment Management</h5>
                            <p>
                                Patients and reception staff can register appointments digitally,
                                enabling structured scheduling and accurate data collection.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/bar-graph.png" alt="">
                            <h5>Clinic Load Monitoring</h5>
                            <p>
                                Track daily clinic workload, patient volume, and consultation
                                patterns to plan operations more efficiently.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/time-tracking.png" alt="">
                            <h5>Consultation Time Tracking</h5>
                            <p>
                                Monitor actual consultation start and end times to analyze delays
                                and overall clinic efficiency.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/analytic.png" alt="">
                            <h5>Doctor Analytics Dashboard</h5>
                            <p>
                                Doctors can view performance insights including patient flow,
                                workload distribution, and time utilization.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/receptionist.png" alt="">
                            <h5>Reception Desk Management</h5>
                            <p>
                                Reception staff can manage walk-ins, offline bookings, and
                                real-time queue updates efficiently.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card animated-card">
                            <img src="./img/patient-flow.png" alt="">
                            <h5>Patient Flow Analytics</h5>
                            <p>
                                Analyze patient movement, queue behavior, and delay patterns
                                to reduce overcrowding and improve planning.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

<section class="py-5 bg-white features">
    <?php include './testimonial.php';?>
</section>

    <?php include "./footer.php"; ?>

</body>

</html>