<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style_mq.css">
    <link rel="stylesheet" href="./css/book_appointment.css">

    <style>
        /* Prevent content hiding behind fixed navbar */
        body {
            padding-top: 90px;
        }
    </style>
</head>
<body>
<?php include "header.php";?>

<!-- ================= FORM ================= -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-lg">
               <div class="card-header text-center">
                    <h4>Book Appointment</h4>
                </div>

                <div class="card-body">
                    <form action="appointment_process.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Appointment Date</label>
                            <input type="date" name="appointment_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Appointment Time</label>
                            <input type="time" name="appointment_time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purpose of Visit</label>
                            <select name="purpose" class="form-select" required>
                                <option value="">Select Purpose</option>
                                <option>General Consultation</option>
                                <option>Meeting</option>
                                <option>Inquiry</option>
                                <option>Follow-up</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Additional Message</label>
                            <textarea name="message" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success w-50">Book Now</button>
                            <button type="reset" class="btn btn-secondary w-50">Clear</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "./footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
