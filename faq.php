<?php
require_once "db.php";

// Fetch active FAQs ordered by sort_order
$faq_query = "SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC";
$faq_result = mysqli_query($con, $faq_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | FAQs</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
</head>

<body>

    <header>
        <?php include "./header.php"; ?>
    </header>

    <main class="explore-hero">
        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Frequently Asked <span>Questions</span></h2>
                <div class="section-divider"></div>
                <p>Find quick answers to common questions about MediQueue.</p>
            </section>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">

                        <?php
                        $count = 0;
                        if (mysqli_num_rows($faq_result) > 0):
                            while ($faq = mysqli_fetch_assoc($faq_result)):
                                $count++;
                                // Show the first item by default, collapse others
                                $showClass = ($count === 1) ? "show" : "";
                                $buttonClass = ($count === 1) ? "" : "collapsed";
                        ?>
                                <div class="accordion-item mb-3 feature-card border-0 shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $buttonClass ?>" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq<?= $faq['id'] ?>">
                                            <?= htmlspecialchars($faq['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="faq<?= $faq['id'] ?>" class="accordion-collapse collapse <?= $showClass ?>"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted">
                                            <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <div class="text-center py-5">
                                <p class="text-muted">No questions found at the moment.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "./footer.php"; ?>

</body>

</html>