<?php
require_once "admin-init.php";
$content_page = 'manage-faqs';
ob_start();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM faqs WHERE id = $id");
    $_SESSION['admin_success'] = "FAQ deleted successfully.";
    header("Location: manage-faqs.php");
    exit();
}

$res = mysqli_query($con, "SELECT * FROM faqs ORDER BY sort_order ASC");
$faqs_data = [];
while ($row = mysqli_fetch_assoc($res)) {
    $faqs_data[] = $row;
}
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Manage <span>FAQs</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
            </div>
            <button class="btn btn-brand w-30 mb-3 py-2" data-bs-toggle="modal" data-bs-target="#addFaqModal">+ Add FAQ</button>
        </div>

        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['admin_success'];
                unset($_SESSION['admin_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="feature-acard">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Order</th>
                        <th width="30%">Question</th>
                        <th width="45%">Answer</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($faqs_data)): ?>
                        <?php foreach ($faqs_data as $row): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $row['sort_order'] ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['question']) ?></td>
                                <td><small class="text-muted"><?= mb_strimwidth(htmlspecialchars($row['answer']), 0, 100, "...") ?></small></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFaqModal<?= $row['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this FAQ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No FAQs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="faq-action.php" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="action" value="add">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3"><label class="form-label fw-bold">Question</label><input type="text" name="question" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-bold">Answer</label><textarea name="answer" class="form-control" rows="4" required></textarea></div>
                <div class="mb-3"><label class="form-label fw-bold">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-brand w-30 mb-3 py-2">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($faqs_data as $row): ?>
    <div class="modal fade" id="editFaqModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="faq-action.php" method="POST" class="modal-content border-0 shadow">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="faq_id" value="<?= $row['id'] ?>">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Question</label>
                        <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($row['question']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Answer</label>
                        <textarea name="answer" class="form-control" rows="4" required><?= htmlspecialchars($row['answer']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= $row['sort_order'] ?>">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-brand w-30 mb-3 py-2">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>