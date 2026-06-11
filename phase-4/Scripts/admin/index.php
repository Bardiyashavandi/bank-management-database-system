<?php
require_once __DIR__ . '/common_ui.php';
require_once __DIR__ . '/ticket_store.php';

$tickets = ticket_fetch_active_tickets(null, $loadError);
$notice = $_GET['resolved'] ?? '';
$activeUsers = [];
$commentCount = 0;

foreach ($tickets as $ticket) {
    $activeUsers[(string) ticket_field($ticket, 'username')] = true;
    $commentCount += count(ticket_comments($ticket));
}

app_render_page_start('Admin - Active Tickets', [
    'theme' => 'admin',
    'brand_href' => 'index.php',
    'brand_label' => 'Bank Management Admin',
    'nav_links' => [
        'index.php' => 'Active Tickets',
        '../user/index.php' => 'User Site',
    ],
]);
?>
<section class="hero">
    <div class="eyebrow">Admin Interface</div>
    <h1>Active Support Tickets</h1>
    <p class="lead">
        This dashboard lists every active support ticket across all users. Open any ticket
        to comment as <code>admin</code> or mark it resolved so it disappears from both the user and admin lists.
    </p>
    <div class="stats-row">
        <div class="stat-chip"><?php echo count($tickets); ?> active ticket(s)</div>
        <div class="stat-chip"><?php echo count($activeUsers); ?> user(s) with open issues</div>
        <div class="stat-chip"><?php echo $commentCount; ?> total comment(s)</div>
    </div>
</section>

<?php if ($notice === '1'): ?>
    <?php app_render_output('success', 'Ticket resolved', 'The ticket was marked resolved and removed from the active lists.'); ?>
<?php endif; ?>

<?php if ($loadError !== null): ?>
    <?php app_render_output('error', 'MongoDB Error', $loadError); ?>
<?php else: ?>
    <div class="summary-grid">
        <div class="summary-card">
            <div class="label">Active Tickets</div>
            <div class="metric"><?php echo count($tickets); ?></div>
            <div class="value">Visible on the admin homepage right now</div>
        </div>
        <div class="summary-card">
            <div class="label">Users Affected</div>
            <div class="metric"><?php echo count($activeUsers); ?></div>
            <div class="value">Distinct usernames with active tickets</div>
        </div>
        <div class="summary-card">
            <div class="label">Comment Volume</div>
            <div class="metric"><?php echo $commentCount; ?></div>
            <div class="value">Existing comments on the active tickets</div>
        </div>
    </div>

    <h2>Ticket queue</h2>
    <?php if (count($tickets) === 0): ?>
        <div class="card">
            <p class="mb-0 text-muted">There are no active tickets to manage.</p>
        </div>
    <?php else: ?>
        <?php foreach ($tickets as $ticket): ?>
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
                <div class="body"><?php echo app_e(ticket_preview(ticket_field($ticket, 'body'))); ?></div>
                <div class="inline-actions">
                    <span class="text-muted"><?php echo count(ticket_comments($ticket)); ?> comment(s)</span>
                    <a class="btn btn-primary" href="detail.php?id=<?php echo urlencode(ticket_id_string($ticket)); ?>">View and Manage</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>

<?php
app_render_footer_link('../user/index.php', 'Back to user interface');
app_render_page_end();
?>
