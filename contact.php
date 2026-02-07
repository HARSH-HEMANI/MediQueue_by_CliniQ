<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>

</head>


<body>

    <header>
        <?php include "./header.php"; ?>

        <!-- Page Heading -->

    </header>

    <main class="features contact-page">

        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Contact <span>Us</span></h2>
                <div class="section-divider"></div>
                <p>
                    Have questions or need support? We’re here to help.
                </p>
            </section>
            <div class="row justify-content-center">

                <!-- Contact Form -->
                <div class="col-lg-7 mb-4">
                    <div class="feature-card">
                        <h5 class="mb-4 text-center">Send Us a Message</h5>

                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" placeholder="Your Name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" placeholder="your@email.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" placeholder="Write your message..."></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="hero-btn px-5">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-5 mb-4">
                    <div class="feature-card animated-card">
                        <h5 class="mb-3">Get in Touch</h5>

                        <p>
                            Reach out to MediQueue for product inquiries,
                            onboarding support, or technical assistance.
                        </p>

                        <ul class="list-unstyled mt-4">
                            <li class="mb-3">
                                <strong>Email:</strong><br>
                                support@mediqueue.com
                            </li>

                            <li class="mb-3">
                                <strong>Phone:</strong><br>
                                +91 9XXXXXXXXX
                            </li>

                            <li class="mb-3">
                                <strong>Office:</strong><br>
                                India
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <?php include "./footer.php"; ?>

</body>

</html>