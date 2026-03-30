<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">What clinics say about <span class="text-brand">MediQueue</span></h2>
            <div class="mx-auto my-3 brand-divider"></div>
            <p class="text-muted mx-auto" style="max-width: 750px;font-size: 1.15rem;">
                Trusted by doctors, clinics, and healthcare teams to reduce waiting time and improve patient experience.
            </p>
        </div>

        <div class="row g-4">
            <?php
            // Fetch testimonials from your existing 'testimonials' table
            $test_query = "SELECT * FROM testimonials WHERE is_visible = 1 ORDER BY id DESC LIMIT 6";
            $test_result = mysqli_query($con, $test_query);

            if (mysqli_num_rows($test_result) > 0):
                while ($t = mysqli_fetch_assoc($test_result)):
            ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 tcard">
                                <img src="./img/quote.png" class="quote-icon" alt="quote" style="width: 30px; margin-bottom: 15px; opacity: 0.2;">

                                <p class="text-muted mb-4">
                                    "<?= htmlspecialchars($t['feedback']) ?>"
                                </p>

                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-brand text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold;">
                                        <?= strtoupper(substr($t['patient_name'], 0, 1)) ?>
                                    </div>

                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($t['patient_name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($t['location']) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center text-muted">
                    <p>No reviews available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>