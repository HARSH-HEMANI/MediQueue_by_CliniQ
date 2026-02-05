<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | FAQs</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css">

</head>


<body>

    <header>
        <?php include "./header.php"; ?>
    </header>

    <main class="features faq-page">

        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Frequently Asked <span>Questions</span></h2>
                <div class="section-divider"></div>
                <p>
                    Find quick answers to common questions about MediQueue.
                </p>
            </section>
            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="accordion" id="faqAccordion">

                        <!-- FAQ 1 -->
                        <div class="accordion-item mb-3 feature-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                    What is MediQueue?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    MediQueue is a smart clinic management platform that helps
                                    manage appointments, patient queues, and clinic analytics
                                    to reduce waiting time and improve efficiency.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item mb-3 feature-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Who can use MediQueue?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Clinics, hospitals, individual doctors, and reception staff
                                    can all use MediQueue to manage daily operations. And, Patients can also use MediQueue to book appointment and see live Queue.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item mb-3 feature-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Does MediQueue support walk-in patients?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. Reception staff can register walk-in patients
                                    and manage real-time queues efficiently.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item mb-3 feature-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Is MediQueue suitable for small clinics?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely. MediQueue is designed to scale from
                                    small clinics to multi-doctor hospitals.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="accordion-item mb-3 feature-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Is patient data secure?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. MediQueue follows secure authentication,
                                    role-based access control, and session-based security
                                    to protect patient data.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <?php include "./footer.php"; ?>

</body>


</html>