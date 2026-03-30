<?php
require_once "admin-init.php";
$content_page = 'manage-site';
ob_start();

$res = mysqli_query($con, "SELECT * FROM site_settings");
$settings = [];
while ($row = mysqli_fetch_assoc($res)) {
    $settings[$row['section_key']] = $row['content_value'];
}
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">
        <div class="features-header text-start mb-4">
            <h2>Manage <span>Website Content</span></h2>
            <div class="section-divider" style="margin-left:0;"></div>
        </div>

        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['admin_success'];
                                                unset($_SESSION['admin_success']); ?></div>
        <?php endif; ?>

        <form action="update-site-action.php" method="POST">

            <div class="feature-acard mb-4">
                <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi- megaphone me-2"></i>Banner & Hero Section</h5>
                <div class="mb-3">
                    <label class="form-label">Pink Banner Text</label>
                    <input type="text" name="banner_text" class="form-control" value="<?= htmlspecialchars($settings['banner_text'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hero Main Title (HTML allowed)</label>
                    <textarea name="hero_title" class="form-control" rows="2"><?= htmlspecialchars($settings['hero_title'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hero Description</label>
                    <textarea name="hero_desc" class="form-control" rows="3"><?= htmlspecialchars($settings['hero_desc'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="feature-acard mb-4">
                <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi-grid-3x3-gap me-2"></i>Features Header</h5>
                <div class="mb-3">
                    <label class="form-label">Section Title</label>
                    <input type="text" name="features_main_title" class="form-control" value="<?= htmlspecialchars($settings['features_main_title'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Section Description</label>
                    <textarea name="features_main_desc" class="form-control" rows="3"><?= htmlspecialchars($settings['features_main_desc'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="feature-acard mb-4">
                <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi-card-checklist me-2"></i>Individual Feature Cards</h5>
                <div class="row">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <div class="col-md-6 mb-4">
                            <div class="p-3 border rounded bg-light">
                                <h6>Feature Card <?= $i ?></h6>
                                <div class="mb-2">
                                    <label class="small fw-bold">Title</label>
                                    <input type="text" name="card<?= $i ?>_title" class="form-control form-control-sm" value="<?= htmlspecialchars($settings["card{$i}_title"] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="small fw-bold">Description</label>
                                    <textarea name="card<?= $i ?>_desc" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($settings["card{$i}_desc"] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="text-center ">
                <button type="submit" class="btn btn-brand w-30 mb-3 py-2">Save All Changes</button>
            </div>
        </form>
    </div>
</main>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>