<?php
require_once "admin-init.php";
require_once "../mailer.php"; // Include your mailer setup

$content_page = 'admin-messages';
ob_start();

// ==========================================
// HANDLE EMAIL REPLY SUBMISSION
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_reply'])) {
    $msg_id = (int)$_POST['message_id'];
    $to_email = $_POST['to_email'];
    $user_name = $_POST['user_name'];
    $original_subject = $_POST['original_subject'];

    // The admin's custom message
    $reply_text = $_POST['reply_message'];

    // Prepare the email details
    $subject = "Re: " . $original_subject . " (MediQueue Support)";

    // Build a nice HTML body for the email
    $body = "
        <div style='font-family: Arial, sans-serif; color: #333;'>
            <h3>MediQueue Support</h3>
            <p>Hi " . htmlspecialchars($user_name) . ",</p>
            <p>Thank you for reaching out to us regarding <strong>" . htmlspecialchars($original_subject) . "</strong>.</p>
            <div style='background-color: #f9f9f9; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;'>
                " . nl2br(htmlspecialchars($reply_text)) . "
            </div>
            <p>Best Regards,<br><strong>MediQueue Admin Team</strong></p>
        </div>
    ";

    // Call the sendEmail function from your mailer.php
    $mail_status = sendEmail($to_email, $subject, $body);

    if ($mail_status === true) {
        // If email sent successfully, update database status
        mysqli_query($con, "UPDATE contact_messages SET status = 'Replied' WHERE message_id = $msg_id");
        $_SESSION['admin_success'] = "Reply sent successfully to " . htmlspecialchars($to_email) . "!";
    } else {
        // If email failed, show the error from PHPMailer
        $_SESSION['admin_error'] = "Failed to send email. Error: " . $mail_status;
    }

    header("Location: admin-messages.php");
    exit();
}

// Fetch all messages
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$messages_result = mysqli_query($con, $query);
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_success'];
                unset($_SESSION['admin_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_error'];
                unset($_SESSION['admin_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="features-header text-center mb-4">
            <h2>Support <span>Messages</span></h2>
            <div class="section-divider"></div>
            <p>View and respond to user inquiries</p>
        </div>

        <div class="feature-acard">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sender Info</th>
                            <th>Subject & Message</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($messages_result && mysqli_num_rows($messages_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($messages_result)): ?>
                                <tr>
                                    <td style="min-width: 100px;">
                                        <?= date('d M Y', strtotime($row['created_at'])) ?><br>
                                        <small class="text-muted"><?= date('h:i A', strtotime($row['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                                        <span class="text-muted small"><?= htmlspecialchars($row['email']) ?></span>
                                    </td>
                                    <td style="max-width: 300px;">
                                        <strong><?= htmlspecialchars($row['subject']) ?></strong><br>
                                        <span class="text-muted small d-inline-block text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($row['message']) ?>">
                                            <?= htmlspecialchars($row['message']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'New'): ?>
                                            <span class="badge bg-danger">New</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Replied</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm <?= ($row['status'] == 'New') ? 'btn-primary' : 'btn-outline-secondary' ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#replyModal_<?= $row['message_id'] ?>">
                                            <?= ($row['status'] == 'New') ? 'Reply Now' : 'View / Send Again' ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No messages found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php
if ($messages_result && mysqli_num_rows($messages_result) > 0) {
    mysqli_data_seek($messages_result, 0); // Reset pointer
    while ($row = mysqli_fetch_assoc($messages_result)):
        $modal_id = $row['message_id'];
?>
        <div class="modal fade" id="replyModal_<?= $modal_id ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="" method="POST">
                        <input type="hidden" name="message_id" value="<?= $modal_id ?>">
                        <input type="hidden" name="to_email" value="<?= htmlspecialchars($row['email']) ?>">
                        <input type="hidden" name="user_name" value="<?= htmlspecialchars($row['name']) ?>">
                        <input type="hidden" name="original_subject" value="<?= htmlspecialchars($row['subject']) ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Reply to <?= htmlspecialchars($row['name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-start">
                            <div class="mb-4 p-3 bg-light border rounded">
                                <h6 class="mb-1 text-muted">Original Message:</h6>
                                <p class="mb-0 small"><strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?></p>
                                <p class="mb-0 small mt-2"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Reply:</label>
                                <textarea name="reply_message" class="form-control" rows="5" placeholder="Type your response here... It will be sent via email." required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <small class="text-muted">Sending from: indextrader82@gmail.com</small>
                            <div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="send_reply" class="btn btn-primary">Send Email</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    endwhile;
}
?>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>