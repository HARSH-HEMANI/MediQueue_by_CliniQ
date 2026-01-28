<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="background-color: #fef9f9;">
    <header>
        <nav class="navbar fixed-top navbar-expand-lg" id="nav-home">
            <div class="container-fluid px-4">

                <!-- Logo (LEFT) -->
                <a class="navbar-brand" href="index.php">
                    <img src="./img/mediq.png" alt="MediQ-Logo" style="height:60px;">
                </a>

                <!-- Toggle button (Mobile) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar content -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- CENTER MENU -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Doctor/Hospital</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">FAQs</a>
                        </li>
                    </ul>

                    <!-- RIGHT BUTTONS -->
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
        <div style="background-color: #FF5A5F; color: #FFF; text-align: center; padding: 10px; margin-top: 88px;">
            <h2>BOOK APPOINTMENT</h2>
        </div>
    </header>

    <main>
        <div class="home_bg" style="background-image: url(./img/home-bg1.png);">
            <div class="home_content" style="text-align: center; margin-right: 50%; font-size: 20px;">
                <h1 style="font-size:50px;">Optimize Patient Flow. <br> Reduce Waiting Time. <br> Improve Care.</h1><br>
                <p>MediQueue by CliniQ is a smart clinic management platform <br>that combines appointment scheduling with real-time <br>queue monitoring and workload analysis, helping clinics <br>run smoothly and efficiently every day.</p>
                <div style="text-align: left; margin-bottom: 20px; margin-left: 35%;"><br>
                    <button type="button" class="btn" id="home-btn" style="background-color: #FF5A5F; color: #FFF; padding: 10px; border-radius: 35px;">Explore MediQueue</button>
                </div>
            </div>
        </div>

        <!-- <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" id="carousel_img">
                    <img src="./img/waiting-area.jpg" class="d-block w-100" alt="..." style="height: 500px; width: auto;">
                    <div class="carousel-caption d-none d-md-block" style="background-color: rgba(100,100,100,0.4); width:auto;">
                        <h5>Smart Queue Visibility</h5>
                        <p>No More Long Waiting Lines in Clinics & Hospitals</p>
                    </div>
                </div>
                <div class="carousel-item" id="carousel_img">
                    <img src="./img/appointment.jpg" class="d-block w-100" alt="..." style="height: 500px; width: auto;">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the second slide.</p>
                    </div>
                </div>
                <div class="carousel-item" id="carousel_img">
                    <img src="./img/doctor.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div> -->
        <div class="home_bg" style="background-image: url(./img/home_bg.png);">
            <div class="home_content" style="text-align: center; margin: 30px; ">
                <h2> Smart Patient Flow & Clinic Analytics.</h2>
                <p>A comprehensive clinic management system designed to monitor appointments, analyze patient flow, <br>and optimize daily clinic workload for better efficiency and reduced waiting time.</p>
            </div>

            <div class="container text-center">
                <div class="row row-cols-3">
                    <div class="col">
                        <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                            <img src="./img/appointment.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                            <div class="card-body">
                                <h5 class="card-title">Smart Appointment Management</h5>
                                <p class="card-text">Patients and reception staff can register appointments digitally, enabling structured scheduling and accurate data collection for clinic flow analysis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                            <img src="./img/bar-graph.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                            <div class="card-body">
                                <h5 class="card-title">Clinic Load Monitoring</h5>
                                <p class="card-text">Track daily clinic workload, patient volume, and consultation patterns to understand how busy the clinic is and plan operations better.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                            <img src="./img/time-tracking.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                            <div class="card-body">
                                <h5 class="card-title">Consultation Time Tracking</h5>
                                <p class="card-text">Monitor actual consultation start and end times to analyze delays, time usage, and overall clinic efficiency.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                            <img src="./img/analytic.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                            <div class="card-body">
                                <h5 class="card-title">Doctor Analytics Dashboard</h5>
                                <p class="card-text">Doctors can view daily performance insights including patient flow, workload distribution, and time utilization through a centralized dashboard.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                            <img src="./img/receptionist.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                            <div class="card-body">
                                <h5 class="card-title">Reception Desk Management</h5>
                                <p class="card-text">Reception staff can manage walk-in patients, offline bookings, and real-time queue updates through a dedicated reception interface.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hover">
                        <div class="col">
                            <div class="card" id="hover-underline" style="width: 18rem; text-align: center; align-items: center;">
                                <img src="./img/patient-flow.png" class="card-img-top" alt="..." style="margin-top:5%; height: 50px; width: 50px;">
                                <div class="card-body">
                                    <h5 class="card-title">Patient Flow Analytics</h5>
                                    <p class="card-text">Analyze patient movement, queue behavior and delay patterns to improve clinic planning and reduce overcrowding.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include "./footer.php";
    ?>

</body>

</html>