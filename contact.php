<?php
// Assuming you have a connection file. Adjust the path if necessary.
require_once "db.php";

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sub = mysqli_real_escape_string($con, $_POST['sub']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    $query = "INSERT INTO contact_messages (name, email, subject, message, status) 
              VALUES ('$name', '$email', '$sub', '$message', 'New')";

    if (mysqli_query($con, $query)) {
        $success_msg = "Thank you! Your message has been sent successfully.";
    } else {
        $error_msg = "Oops! Something went wrong. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>
    <header>
        <?php include "./header.php"; ?>
    </header>

    <main class="explore-hero">
        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Contact <span>Us</span></h2>
                <div class="section-divider"></div>
                <p>Have questions or need support? We’re here to help.</p>
            </section>

            <div class="row justify-content-center">

                <div class="col-lg-7 mb-4">
                    <div class="feature-card">
                        <h5 class="mb-4 text-center">Send Us a Message</h5>

                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success"><?= $success_msg ?></div>
                        <?php endif; ?>
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger"><?= $error_msg ?></div>
                        <?php endif; ?>

                        <form action="" method="post" id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required data-validation="required|min|max" data-min="3" data-max="20">
                                    <small id="name_error" class="text-danger"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com" required data-validation="required|email">
                                    <small id="email_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" name="sub" id="sub" class="form-control" placeholder="Subject" required data-validation="required">
                                <small id="sub_error" class="text-danger"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" name="message" id="message" rows="5" placeholder="Write your message..." required data-validation="required"></textarea>
                                <small id="message_error" class="text-danger"></small>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="submit_contact" class="hero-btn me-3">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5 mb-4">
                    <div class="feature-card animated-card">
                        <h5 class="mb-3">Get in Touch</h5>
                        <p>Reach out to MediQueue for product inquiries, onboarding support, or technical assistance.</p>
                        <ul class="list-unstyled mt-4">
                            <li class="mb-3"><strong>Email:</strong><br>support@mediqueue.com</li>
                            <li class="mb-3"><strong>Phone:</strong><br>+91 123456789</li>
                            <li class="mb-3"><strong>Office:</strong><br>India</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include "./footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/validation.js"></script>
</body>

</html>