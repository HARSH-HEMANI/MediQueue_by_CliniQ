<?php
require_once "admin-init.php";
$content_page = 'manage-testimonials';
ob_start();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM testimonials WHERE id = $id");
    header("Location: manage-testimonials.php");
}

$res = mysqli_query($con, "SELECT * FROM testimonials ORDER BY id DESC");
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Manage <span>Testimonials</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
            </div>
            <button class="btn btn-brand w-30 mb-3 py-2" data-bs-toggle="modal" data-bs-target="#addTestModal">+ Add New</button>
        </div>

        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Feedback</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><strong><?= $row['patient_name'] ?></strong><br><small><?= $row['location'] ?></small></td>
                            <td><small><?= $row['feedback'] ?></small></td>
                            <td><?= $row['rating'] ?>/5</td>
                            <td>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="addTestModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="testimonial-action.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5>Add New Testimonial</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Patient Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label>Location</label><input type="text" name="location" class="form-control" required></div>
                <div class="mb-3"><label>Feedback</label><textarea name="feedback" class="form-control" rows="3" required></textarea></div>
                <div class="mb-3"><label>Rating (1-5)</label><input type="number" name="rating" class="form-control" min="1" max="5" value="5"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-brand w-30 mb-3 py-2">Save Testimonial</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>