<?php
require_once "./db.php";

// Fetch CMS settings
$settings_result = mysqli_query($con, "SELECT section_key, content_value FROM site_settings");
$cms = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $cms[$row['section_key']] = $row['content_value'];
}

// Helper Function
function get_content($key, $default, $cms_array)
{
    return isset($cms_array[$key]) && !empty($cms_array[$key]) ? $cms_array[$key] : $default;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css?v=vibrant">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
</head>

<body>

    <header>
        <?php include './header.php'; ?>
    </header>

    <main class="explore-hero">
        <div class="container">
            <section class="features-header text-center my-5">
                <h2>About <span>MediQueue</span></h2>
                <div class="section-divider"></div>
                <p><?= get_content('about_hero_subtitle', 'Smart technology to simplify clinic operations', $cms) ?></p>
            </section>

            <div class="features-header text-center">
                <h2>Who <span>We Are</span></h2>
                <div class="section-divider"></div>
                <p><?= get_content('about_who_we_are', 'MediQueue is a smart clinic management platform...', $cms) ?></p>
            </div>

            <div class="features-header text-center mt-5">
                <h2>Our <span>Mission</span></h2>
                <div class="section-divider"></div>
                <p><?= get_content('about_mission', 'Our mission is to help clinics...', $cms) ?></p>
            </div>

            <div class="features-header text-center mt-5">
                <h2>Why <span>MediQueue?</span></h2>
                <div class="section-divider"></div>
            </div>

            <div class="row justify-content-center">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card about-card">
                            <h5><?= get_content("about_why_card{$i}_title", "Benefit $i", $cms) ?></h5>
                            <p><?= get_content("about_why_card{$i}_desc", "Benefit description...", $cms) ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </main>

    <?php include './footer.php'; ?>
</body>

</html>