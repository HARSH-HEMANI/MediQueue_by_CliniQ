<?php
require_once "./db.php";
$settings_result = mysqli_query($con, "SELECT section_key, content_value FROM site_settings");
$cms = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $cms[$row['section_key']] = $row['content_value'];
}

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
    <title>Home | MediQueue</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
</head>

<body>
    <header>
        <?php include "./header.php"; ?>
        <div style="background: linear-gradient(90deg, #fd686d, #ff8a8f);color:#fff;text-align:center;padding:10px;margin-top:88px;">
            <h2><?= get_content('banner_text', 'SMART CLINIC MANAGEMENT SYSTEM', $cms) ?></h2>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1><?= get_content('hero_title', 'Optimize Patient Flow.<br>Reduce Waiting Time.<br><span>Improve Care.</span>', $cms) ?></h1>
                <p><?= get_content('hero_desc', 'MediQueue description...', $cms) ?></p>
                <a href="./explore.php" class="hero-btn">Explore MediQueue</a>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="features-header text-center">
                    <h2><?= get_content('features_main_title', 'Smart Patient Flow & Clinic Analytics', $cms) ?></h2>
                    <div class="section-divider"></div>
                    <p><?= get_content('features_main_desc', 'Description...', $cms) ?></p>
                </div>

                <div class="row justify-content-center">
                    <?php
                    $icons = ['./img/appointment.png', './img/bar-graph.png', './img/time-tracking.png', './img/analytic.png', './img/receptionist.png', './img/patient-flow.png'];
                    for ($i = 1; $i <= 6; $i++):
                    ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="feature-card animated-card">
                                <img src="<?= $icons[$i - 1] ?>" alt="">
                                <h5><?= get_content("card{$i}_title", "Feature $i", $cms) ?></h5>
                                <p><?= get_content("card{$i}_desc", "Description for card $i", $cms) ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include "./footer.php"; ?>
</body>

</html>