<?php
require_once __DIR__ . '/common_ui.php';
require_once __DIR__ . '/ticket_store.php';

$selected = trim($_GET['username'] ?? '');
$loadError = null;
$ticketError = null;
$usernames = ticket_active_usernames($loadError);
$tickets = null;

if ($selected !== '') {
    $tickets = ticket_fetch_active_tickets($selected, $ticketError);
}

$visibleCount = is_array($tickets) ? count($tickets) : 0;

app_render_page_start('Support Tickets', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<section class="hero">
    <div class="eyebrow">MongoDB Support Tickets</div>
    <h1>Support Tickets</h1>
    <p class="lead">
        Pick a username with active tickets, inspect the active issues for that user,
        or create a new ticket from the same flat PHP structure required by the phase submission.
    </p>
    <div class="stats-row">
        <div class="stat-chip"><?php echo count($usernames); ?> active user(s)</div>
        <div class="stat-chip"><?php echo $visibleCount; ?> visible ticket(s)</div>
        <div class="stat-chip">Only active tickets appear here</div>
    </div>
</section>

<?php if ($loadError !== null): ?>
    <?php app_render_output('error', 'MongoDB Error', $loadError); ?>
<?php endif; ?>

<?php if ($ticketError !== null): ?>
    <?php app_render_output('error', 'Ticket Error', $ticketError); ?>
<?php endif; ?>

<div class="card">
    <form method="GET">
        <div class="form-row">
            <div class="field">
                <label for="username">Select user</label>
                <select id="username" name="username">
                    <option value="">-- select a user --</option>
                    <?php foreach ($usernames as $username): ?>
                        <option value="<?php echo app_e($username); ?>" <?php echo $username === $selected ? 'selected' : ''; ?>>
                            <?php echo app_e($username); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="field-hint">
                    Only usernames with at least one active ticket appear in this dropdown.
                </div>
            </div>
        </div>
        <div class="inline-actions">
            <button type="submit" class="btn btn-primary">View Tickets</button>
            <a class="btn btn-secondary" href="ticket_create.php">Create a Ticket</a>
        </div>
    </form>
</div>

<h2>Active tickets</h2>
<?php if (empty($usernames)): ?>
    <div class="card">
        <p class="mb-0 text-muted">There are no active tickets in the system yet. Use the create page to open the first one.</p>
    </div>
<?php elseif ($selected === ''): ?>
    <div class="card">
        <p class="mb-0 text-muted">Choose a username above to view that user's active tickets.</p>
    </div>
<?php elseif (count($tickets) === 0): ?>
    <div class="card">
        <p class="mb-0 text-muted">No active tickets are currently listed for <strong><?php echo app_e($selected); ?></strong>.</p>
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
                <a class="btn btn-secondary" href="ticket_detail.php?id=<?php echo urlencode(ticket_id_string($ticket)); ?>&username=<?php echo urlencode($selected); ?>">View Details</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
