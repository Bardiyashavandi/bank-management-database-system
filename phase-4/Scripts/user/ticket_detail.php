<?php
require_once __DIR__ . '/common_ui.php';
require_once __DIR__ . '/ticket_store.php';

$id = $_GET['id'] ?? '';
$returnUsername = trim($_GET['username'] ?? '');
$notice = $_GET['commented'] ?? '';
$ticket = ticket_find($id, $loadError);
$commentStatus = null;
$commentMessage = '';
$commentUsername = $_POST['username'] ?? '';
$commentBody = $_POST['body'] ?? '';

if ($ticket !== null && $commentUsername === '') {
    $commentUsername = (string) ticket_field($ticket, 'username', $returnUsername);
}

if ($ticket !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentUsername = trim($_POST['username'] ?? '');
    $commentBody = trim($_POST['body'] ?? '');

    if ($commentUsername === '' || $commentBody === '') {
        $commentStatus = 'error';
        $commentMessage = 'Both a username and a comment body are required.';
    } else {
        $commentError = null;
        if (ticket_add_comment($id, $commentUsername, $commentBody, $commentError)) {
            $query = [
                'id' => $id,
                'commented' => '1',
            ];

            if ($returnUsername !== '') {
                $query['username'] = $returnUsername;
            }

            header('Location: ticket_detail.php?' . http_build_query($query));
            exit;
        }

        $commentStatus = 'error';
        $commentMessage = $commentError ?? 'Could not add comment.';
    }
}

$backUrl = 'tickets.php' . ($returnUsername !== '' ? '?username=' . urlencode($returnUsername) : '');

app_render_page_start('Ticket Details', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Ticket Details</h1>

<?php if ($notice === '1'): ?>
    <?php app_render_output('success', 'Comment added', 'Your comment was added successfully.'); ?>
<?php endif; ?>

<?php if ($loadError !== null): ?>
    <?php app_render_output('error', 'Error', $loadError); ?>
<?php else: ?>
    <div class="ticket">
        <div class="ticket-head">
            <div>
                <span class="who"><?php echo app_e(ticket_field($ticket, 'username')); ?></span>
                <span class="pill <?php echo app_status_class(ticket_is_active($ticket)); ?>">
                    <?php echo app_status_text(ticket_is_active($ticket)); ?>
                </span>
            </div>
            <div class="when"><?php echo app_e(ticket_field($ticket, 'created_at')); ?></div>
        </div>
        <div class="body"><?php echo nl2br(app_e(ticket_field($ticket, 'body'))); ?></div>
    </div>

    <div class="card">
        <h2 class="mt-0">Ticket metadata</h2>
        <ul class="muted-list">
            <li>Username: <strong><?php echo app_e(ticket_field($ticket, 'username')); ?></strong></li>
            <li>Created at: <strong><?php echo app_e(ticket_field($ticket, 'created_at')); ?></strong></li>
            <li>Status: <strong><?php echo app_e(app_status_text(ticket_is_active($ticket))); ?></strong></li>
            <li>Comment count: <strong><?php echo count(ticket_comments($ticket)); ?></strong></li>
        </ul>
    </div>

    <h2>Comments</h2>
    <?php $comments = ticket_comments($ticket); ?>
    <?php if (count($comments) === 0): ?>
        <div class="card">
            <p class="mb-0 text-muted">No comments yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <?php
            $commentUser = ticket_field($comment, 'username');
            $isAdminComment = strtolower((string) $commentUser) === 'admin';
            ?>
            <div class="comment <?php echo $isAdminComment ? 'admin' : ''; ?>">
                <div class="meta">
                    <span class="who"><?php echo app_e($commentUser); ?></span>
                    <?php if ($isAdminComment): ?>
                        <span class="pill admin">Admin</span>
                    <?php endif; ?>
                    <span><?php echo app_e(ticket_field($comment, 'created_at')); ?></span>
                </div>
                <div class="body"><?php echo nl2br(app_e(ticket_field($comment, 'comment'))); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Add a Comment</h2>
    <?php if ($commentStatus === 'error'): ?>
        <?php app_render_output('error', 'Error', $commentMessage); ?>
    <?php endif; ?>
    <div class="card">
        <form method="POST">
            <div class="field">
                <label for="comment_username">Your username</label>
                <input type="text" id="comment_username" name="username" value="<?php echo app_e($commentUsername); ?>" required>
            </div>
            <div class="field">
                <label for="comment_body">Comment</label>
                <textarea id="comment_body" name="body" required><?php echo app_e($commentBody); ?></textarea>
            </div>
            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Post Comment</button>
                <a class="btn btn-secondary" href="<?php echo app_e($backUrl); ?>">Back to tickets</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php
app_render_footer_link($backUrl, 'Back to tickets');
app_render_page_end();
?>
