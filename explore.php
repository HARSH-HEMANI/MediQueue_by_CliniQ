<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css?v=vibrant">
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <?php include "./header.php"; ?>

    <!-- ══════════════ HERO ══════════════ -->
    <section class="explore-hero">
        <div class="container">
            <div class="row align-items-center g-5">

                <!-- Left -->
                <div class="col-lg-6">
                    <!-- badge reuses existing brand badge style -->
                    <div class="d-inline-flex align-items-center gap-2 mb-4 px-3 py-2 rounded-pill"
                        style="background:rgba(255,90,95,0.1);border:1px solid rgba(255,90,95,0.25);">
                        <i class="bi bi-lightning-charge-fill text-brand" style="font-size:0.8rem;"></i>
                        <span class="text-brand fw-bold text-uppercase" style="font-size:0.75rem;letter-spacing:1.2px;">Smart Clinic OS</span>
                    </div>

                    <!-- reuses features-header heading style -->
                    <h1 class="fw-bold lh-1 mb-0" style="font-size:clamp(2.5rem,5vw,4.2rem);letter-spacing:-1px;color:#1a1a2e;">
                        Everything About<br><span class="text-brand">MediQueue</span>
                    </h1>

                    <p class="mt-4 mb-4 text-muted" style="font-size:1.05rem;line-height:1.8;max-width:500px;">
                        A structured, role-based clinic management system that
                        streamlines appointments, queue management, and analytics
                        — all in one unified platform.
                    </p>

                    <!-- stats row — reuses explore-stat from style.css -->
                    <div class="d-flex gap-4 flex-wrap mb-4">
                        <div class="explore-stat">
                            <h3>4</h3>
                            <p>User Roles</p>
                        </div>
                        <div class="explore-stat">
                            <h3>5</h3>
                            <p>Workflow Steps</p>
                        </div>
                        <div class="explore-stat">
                            <h3>∞</h3>
                            <p>Scalable</p>
                        </div>
                    </div>

                    <a href="./login.php" class="hero-btn me-3">
                        <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                    </a>
                    <a href="./contact.php" class="btn btn-outline-secondary rounded-pill px-4">
                        Contact Us
                    </a>
                </div>

                <!-- Right — hero-float-card from style.css -->
                <div class="col-lg-6">
                    <div class="hero-float-card">
                        <p class="text-uppercase fw-bold text-muted mb-3" style="font-size:0.7rem;letter-spacing:1.5px;">Active Roles</p>

                        <div class="mb-3">
                            <span class="role-pill doctor"><i class="bi bi-heart-pulse-fill"></i>Doctor</span>
                            <span class="role-pill reception"><i class="bi bi-person-badge-fill"></i>Reception</span>
                            <span class="role-pill patient"><i class="bi bi-person-fill"></i>Patient</span>
                            <span class="role-pill admin"><i class="bi bi-shield-fill"></i>Admin</span>
                        </div>

                        <hr class="my-3" style="border-color:rgba(0,0,0,0.07);">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-muted mb-1" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700;">System Status</p>
                                <p class="fw-bold mb-0" style="color:#111827;">All Modules Active</p>
                            </div>
                            <span class="badge bg-success py-2 px-3">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Live
                            </span>
                        </div>

                        <!-- metric rows — reuse card-bg from style.css -->
                        <div class="d-flex flex-column gap-2">
                            <?php
                            $metrics = [
                                ['Queue Efficiency', '94%',    '#3b82f6'],
                                ['Avg Wait Time',    '8 min',  '#22c55e'],
                                ['Patient Flow',     'Optimal', '#FF5A5F'],
                            ];
                            foreach ($metrics as $m):
                            ?>
                                <div class="d-flex justify-content-between align-items-center p-2 rounded-3"
                                    style="background:rgba(0,0,0,0.03);">
                                    <span class="text-muted" style="font-size:0.85rem;"><?= $m[0] ?></span>
                                    <strong style="color:<?= $m[2] ?>;font-size:0.88rem;"><?= $m[1] ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ══════════════ WORKFLOW ══════════════ -->
    <section class="explore-section">
        <div class="container">
            <div class="row align-items-start g-5">

                <!-- Left — reuses features-header -->
                <div class="col-lg-4 fade-up">
                    <span class="section-label">How It Works</span>
                    <h2 class="features-header fw-bold mb-3" style="font-size:2.4rem;letter-spacing:-0.5px;">
                        System <span>Workflow</span>
                    </h2>
                    <div class="section-divider mb-4" style="margin:0 0 20px;"></div>
                    <p class="text-muted" style="font-size:1rem;line-height:1.8;">
                        Five seamless steps from patient arrival to consultation
                        completion — fully tracked and optimised.
                    </p>
                    <a href="./patient/book_appointment.php" class="hero-btn mt-4 d-inline-block">
                        <i class="bi bi-calendar-plus me-2"></i>Book Now
                    </a>
                </div>

                <!-- Right — timeline from style.css -->
                <div class="col-lg-8">
                    <div class="timeline-wrap">
                        <?php
                        $steps = [
                            ['Patient Registration',  'Patient registers an appointment online or as a walk-in at the reception desk.'],
                            ['Token Assignment',      'Receptionist assigns a token number and adds the patient to the live queue.'],
                            ['Doctor Consultation',   'Doctor consults the patient and updates consultation status in real time.'],
                            ['Data Recording',        'System records consultation time, visit details, and diagnosis automatically.'],
                            ['Analytics & Review',    'Admin monitors performance metrics, analytics, and overall system health.'],
                        ];
                        foreach ($steps as $i => $step):
                        ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"><?= $i + 1 ?></div>
                                <div class="timeline-card">
                                    <span class="timeline-card-label"><?= $step[0] ?></span>
                                    <p class="mb-0 fw-500" style="color:#374151;font-size:0.97rem;"><?= $step[1] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ══════════════ ROLE MODULES ══════════════ -->
    <section class="explore-section" style="padding:80px 0;">
        <div class="container">

            <!-- reuses features-header from style.css -->
            <div class="features-header text-center fade-up">
                <h2>Role-Based <span>Modules</span></h2>
                <div class="section-divider"></div>
                <p>MediQueue divides responsibilities across four secure user roles,
                    each with a tailored dashboard and strict access control.</p>
            </div>

            <div class="row g-4 mt-2">

                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="role-card-new doctor">
                        <div class="role-icon-box"><i class="bi bi-heart-pulse-fill"></i></div>
                        <h5 class="fw-bold mb-1" style="color:#1a1a2e;">Doctor</h5>
                        <p class="text-muted mb-3" style="font-size:0.82rem;">Clinical Operations</p>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Live queue management</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Consultation tracking</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Patient record access</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Performance insights</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="role-card-new recep">
                        <div class="role-icon-box"><i class="bi bi-person-badge-fill"></i></div>
                        <h5 class="fw-bold mb-1" style="color:#1a1a2e;">Receptionist</h5>
                        <p class="text-muted mb-3" style="font-size:0.82rem;">Front Desk Operations</p>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Walk-in registration</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Appointment booking</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Token generation</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Queue management</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="role-card-new patient">
                        <div class="role-icon-box"><i class="bi bi-person-fill"></i></div>
                        <h5 class="fw-bold mb-1" style="color:#1a1a2e;">Patient</h5>
                        <p class="text-muted mb-3" style="font-size:0.82rem;">Patient Portal</p>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Online booking</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Live queue status</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Visit history</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Prescriptions</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="role-card-new admin">
                        <div class="role-icon-box"><i class="bi bi-shield-fill-check"></i></div>
                        <h5 class="fw-bold mb-1" style="color:#1a1a2e;">Admin</h5>
                        <p class="text-muted mb-3" style="font-size:0.82rem;">System Control</p>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Doctor & clinic mgmt</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>System analytics</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Audit logs</div>
                        <div class="role-feature-item"><i class="bi bi-check-circle-fill"></i>Configuration</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ══════════════ ARCHITECTURE ══════════════ -->
    <section class="arch-dark">
        <div class="container position-relative" style="z-index:1;">

            <div class="row align-items-center g-5">

                <div class="col-lg-4 fade-up">
                    <span class="section-label" style="color:#ff8a8f;">Under The Hood</span>
                    <h2 class="fw-bold mb-3 text-white" style="font-size:2.4rem;letter-spacing:-0.5px;">
                        System <span style="color:#ff8a8f;">Architecture</span>
                    </h2>
                    <div class="brand-divider mb-4"></div>
                    <p class="mb-3" style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:0.97rem;">
                        MediQueue follows a modular architecture with session-based
                        authentication and strict role-based access control. Each user
                        interacts only with authorised modules.
                    </p>
                    <p style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:0.97rem;">
                        The centralised system maintains consistent workflow across
                        clinics while enabling scalable management.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="row g-3">
                        <?php
                        $arch = [
                            ['🔐', 'Session Auth',     'Secure PHP session-based authentication with role enforcement on every request.'],
                            ['🧩', 'Modular Design',   'Each role has its own isolated module — doctor, reception, patient, admin.'],
                            ['⚡', 'Real-time Queue',  'Live queue updates with token management and instant status changes.'],
                            ['📊', 'Analytics Engine', 'Consultation time tracking, peak-hour analysis, and doctor performance insights.'],
                            ['📱', 'Responsive UI',    'Fully mobile-responsive layout with a glassmorphism design system.'],
                            ['🗄️', 'MySQL Backend',    'Relational database storing all patient, appointment, and clinic data.'],
                        ];
                        foreach ($arch as $i => $a):
                        ?>
                            <div class="col-md-4 fade-up">
                                <div class="arch-card-dark">
                                    <span style="font-size:1.8rem;display:block;margin-bottom:14px;"><?= $a[0] ?></span>
                                    <h6><?= $a[1] ?></h6>
                                    <p><?= $a[2] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ══════════════ CTA ══════════════ -->
    <!-- reuses cta-section from style.css -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="features-header text-center fade-up mb-4">
                <h2>Ready to Experience <span>MediQueue?</span></h2>
                <div class="section-divider"></div>
                <p>Join clinics already using MediQueue to reduce wait times,
                    streamline operations, and deliver better patient care.</p>
            </div>
            <div class="d-flex gap-3 justify-content-center flex-wrap fade-up">
                <a href="./login.php" class="hero-btn">
                    <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                </a>
                <a href="./index.php" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </section>

    <?php include "./footer.php"; ?>

    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'),
                        parseInt(entry.target.dataset.delay) || 0);
                }
            });
        }, {
            threshold: 0.12
        });

        document.querySelectorAll('.fade-up').forEach((el, i) => {
            el.dataset.delay = i * 70;
            observer.observe(el);
        });

        document.querySelectorAll('.timeline-item').forEach((el, i) => {
            el.dataset.delay = i * 100;
            observer.observe(el);
        });
    </script>

</body>

</html>