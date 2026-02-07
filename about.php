<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <header>
        <?php include './header.php'; ?>
    </header>

    <main class="features">

        <div class="container">
            <section class="features-header text-center my-5">
                <h2>About <span>MediQueue</span></h2>
                <div class="section-divider"></div>
                <p>
                    Smart technology to simplify clinic operations
                </p>
            </section>
            <!-- WHO WE ARE -->
            <div class="features-header text-center">
                <h2>Who <span>We Are</span></h2>
                <div class="section-divider"></div>
                <p>
                    MediQueue is a smart clinic management platform designed to reduce
                    patient waiting time and improve operational efficiency. We combine
                    appointment scheduling, real-time queue monitoring, and analytics
                    into one unified system.
                </p>
            </div>

            <!-- OUR MISSION -->
            <div class="features-header text-center mt-5">
                <h2>Our <span>Mission</span></h2>
                <div class="section-divider"></div>
                <p>
                    Our mission is to help clinics, hospitals, and doctors deliver
                    better care by removing administrative chaos and optimizing
                    patient flow using data-driven insights.
                </p>
            </div>

            <!-- WHY MEDIQUEUE -->
            <div class="features-header text-center mt-5">
                <h2>Why <span>MediQueue?</span></h2>
                <div class="section-divider"></div>
            </div>

            <!-- Cards reused from homepage feature design -->
            <div class="row justify-content-center">

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card animated-card">
                        <h5>Reduced Waiting Time</h5>
                        <p>
                            Smart queue handling keeps patients informed
                            and clinics efficient.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card animated-card">
                        <h5>Actionable Analytics</h5>
                        <p>
                            Understand patient flow, peak hours,
                            and doctor workload.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card animated-card">
                        <h5>Doctor-First Design</h5>
                        <p>
                            Built around real clinic workflows
                            and daily challenges.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <?php include './footer.php'; ?>
</body>

</html>