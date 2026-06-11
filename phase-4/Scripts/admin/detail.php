<?php
require_once __DIR__ . '/common_ui.php';
require_once __DIR__ . '/ticket_store.php';

$id = $_GET['id'] ?? '';
$notice = $_GET['updated'] ?? '';
$ticket = ticket_find($id, $loadError);
$actionError = null;
$commentBody = trim($_POST['body'] ?? '');

if ($ticket !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'comment') {
        if (!ticket_is_active($ticket)) {
            $actionError = 'Resolved tickets cannot receive new admin comments.';
        } elseif ($commentBody === '') {
            $actionError = 'Comment body is required.';
        } else {
            $commentError = null;
            if (ticket_add_comment($id, 'admin', $commentBody, $commentError)) {
                header('Location: detail.php?' . http_build_query([
                    'id' => $id,
                    'updated' => 'commented',
                ]));
                exit;
            }

            $actionError = $commentError ?? 'Could not add comment.';
        }
    } elseif ($action === 'resolve') {
        if (!ticket_is_active($ticket)) {
            $actionError = 'This ticket is already resolved.';
        } else {
            $resolveError = null;
            if (ticket_resolve($id, $resolveError)) {
                header('Location: index.php?resolved=1');
                exit;
            }

            $actionError = $resolveError ?? 'Could not resolve ticket.';
        }
    }
}

app_render_page_start('Admin - Ticket Details', [
    'theme' => 'admin',
    'brand_href' => 'index.php',
    'brand_label' => 'Bank Management Admin',
    'nav_links' => [
        'index.php' => 'Active Tickets',
        '../user/index.php' => 'User Site',
    ],
]);
?>
<h1>Ticket Details</h1>

<?php if ($notice === 'commented'): ?>
    <?php app_render_output('success', 'Admin comment posted', 'Your admin reply was added to the ticket.'); ?>
<?php endif; ?>

<?php if ($loadError !== null): ?>
    <?php app_render_output('error', 'Error', $loadError); ?>
<?php else: ?>
    <div class="ticket">
        <div class="ticket-head">
            <div>
                <span class="who"><?php echo app_e(ticket_field($ticket, 'username')); ?></span>
                <span class="pill <?php echo app_status_class(ticket_is_active($ticket)); ?>">
                    <?php echo app_e(app_status_text(ticket_is_active($ticket))); ?>
                </span>
            </div>
            <div class="when"><?php echo app_e(ticket_field($ticket, 'created_at')); ?></div>
        </div>
        <div class="body"><?php echo nl2br(app_e(ticket_field($ticket, 'body'))); ?></div>
    </div>

    <div class="card">
        <h2 class="mt-0">Admin controls</h2>
        <ul class="muted-list">
            <li>Comments from this page are automatically stored with username <strong>admin</strong>.</li>
            <li>Resolving a ticket flips its <code>status</code> to <code>false</code>.</li>
            <li>Resolved tickets no longer appear on either homepage because both lists filter to active entries.</li>
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

    <?php if ($actionError !== null): ?>
        <?php app_render_output('error', 'Action failed', $actionError); ?>
    <?php endif; ?>

    <?php if (ticket_is_active($ticket)): ?>
        <div class="card">
            <h2 class="mt-0">Comment as admin</h2>
            <form method="POST">
                <input type="hidden" name="action" value="comment">
                <div class="field">
                    <label for="body">Admin comment</label>
                    <textarea id="body" name="body" required><?php echo app_e($commentBody); ?></textarea>
                </div>
                <div class="inline-actions">
                    <button type="submit" class="btn btn-primary">Post Admin Comment</button>
                    <a class="btn btn-secondary" href="index.php">Back to active tickets</a>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 class="mt-0">Resolve ticket</h2>
            <p class="text-muted">Use this when the issue is complete. The ticket will disappear from active user and admin views.</p>
            <form method="POST">
                <input type="hidden" name="action" value="resolve">
                <div class="inline-actions">
                    <button type="submit" class="btn btn-danger">Mark Resolved</button>
                    <a class="btn btn-secondary" href="index.php">Cancel</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="card">
            <h2 class="mt-0">Resolved ticket</h2>
            <p class="mb-0 text-muted">This ticket is already resolved, so the admin action forms are disabled.</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
app_render_footer_link('index.php', 'Back to active tickets');
app_render_page_end();
?>
