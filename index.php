<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | MediQueue</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <header>
        <nav class="navbar fixed-top navbar-expand-lg" id="nav-home">
            <div class="container-fluid px-4">

                <!-- Logo -->
                <a class="navbar-brand" href="index.php">
                    <img src="./img/mediq.png" alt="MediQ Logo" style="height:60px;">
                </a>

                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Center Menu -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Doctor/Hospital</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">FAQs</a></li>
                    </ul>

                    <!-- Right Buttons -->
                    <div class="d-flex">
                        <button class="btn btn-outline-danger me-2"
                            onclick="location.href='./login.php'"
                            style="border-radius:38px; border:2px solid #FF5A5F;">
                            Login
                        </button>

                        <button class="btn btn-outline-secondary"
                            onclick="location.href='register.php'"
                            style="border-radius:38px; border-width:2px;">
                            Register
                        </button>
                    </div>

                </div>
            </div>
        </nav>

        <!-- Booking Banner -->
        <div style="background:#FF5A5F;color:#fff;text-align:center;padding:10px;margin-top:88px;">
            <h2>SMART APPOINTMENT BOOKING SYSTEM</h2>
        </div>
    </header>

    <!-- ================= MAIN ================= -->
    <main>

        <!-- HERO SECTION -->
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
                <!-- <form action="book_appointment.php">
                <button>Book Appointment</button>
                </form> -->
                <a href="book_appointment.php" class="hero-btn">Book Appointment</a>

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

    <!-- ================= FOOTER ================= -->
    <?php include "./footer.php"; ?>

</body>

</html>