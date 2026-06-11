<?php
require_once __DIR__ . '/common_ui.php';
require_once __DIR__ . '/ticket_store.php';

$status = null;
$message = '';
$submittedUsername = '';
$submittedBody = '';
$lastCreatedUsername = '';
$ticketListUrl = 'tickets.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedUsername = trim($_POST['username'] ?? '');
    $submittedBody = trim($_POST['body'] ?? '');

    if ($submittedUsername === '' || $submittedBody === '') {
        $status = 'error';
        $message = 'Both a username and a message body are required.';
    } else {
        $ticketError = null;
        if (ticket_create($submittedUsername, $submittedBody, $ticketError)) {
            $status = 'success';
            $message = 'Ticket created successfully.';
            $lastCreatedUsername = $submittedUsername;
            $ticketListUrl = 'tickets.php?username=' . urlencode($lastCreatedUsername);
            $submittedUsername = '';
            $submittedBody = '';
        } else {
            $status = 'error';
            $message = $ticketError ?? 'Could not create ticket.';
        }
    }
}

app_render_page_start('Create a Ticket', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Create a Ticket</h1>
<p class="lead">Open a new support ticket. The ticket will be stored in MongoDB with an active status and an empty comment list.</p>

<?php if ($status === 'success'): ?>
    <?php app_render_output('success', 'Ticket created', $message); ?>
    <div class="card">
        <div class="inline-actions">
            <a class="btn btn-primary" href="ticket_create.php">Create another ticket</a>
            <a class="btn btn-secondary" href="<?php echo app_e($ticketListUrl); ?>">Back to ticket list</a>
        </div>
    </div>
<?php else: ?>
    <?php if ($status === 'error'): ?>
        <?php app_render_output('error', 'Error', $message); ?>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Your username" value="<?php echo app_e($submittedUsername); ?>" required>
            </div>
            <div class="field">
                <label for="body">Message</label>
                <textarea id="body" name="body" placeholder="Describe the issue you need help with" required><?php echo app_e($submittedBody); ?></textarea>
            </div>
            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Create Ticket</button>
                <a class="btn btn-secondary" href="tickets.php">Back to ticket list</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
