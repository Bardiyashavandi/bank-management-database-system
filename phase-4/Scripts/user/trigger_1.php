<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$message = '';
$messageType = '';
$accountId = $_POST['account_id'] ?? '1';
$newBalance = $_POST['new_balance'] ?? '';

$accounts = [];
if ($res = $mysqli->query('SELECT AccountID, Number, Balance FROM Account ORDER BY AccountID')) {
    while ($row = $res->fetch_assoc()) {
        $accounts[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accountId === '' || $newBalance === '' || !is_numeric($newBalance)) {
        $message = 'Please pick an account and enter a numeric new balance.';
        $messageType = 'error';
    } else {
        $stmt = $mysqli->prepare('UPDATE Account SET Balance = ? WHERE AccountID = ?');
        if ($stmt === false) {
            $message = 'Failed to prepare query: ' . $mysqli->error;
            $messageType = 'error';
        } else {
            $stmt->bind_param('di', $newBalance, $accountId);
            if ($stmt->execute()) {
                $message = "UPDATE accepted. Account {$accountId} balance was set to {$newBalance}.";
                $messageType = 'success';
            } else {
                $message = 'UPDATE blocked by trigger: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        }
    }

    // Reload the account list so the dropdown reflects the new balance after a successful update.
    $accounts = [];
    if ($res = $mysqli->query('SELECT AccountID, Number, Balance FROM Account ORDER BY AccountID')) {
        while ($row = $res->fetch_assoc()) {
            $accounts[] = $row;
        }
    }
}

app_render_page_start('Trigger 1 - Account Balance Cannot Go Negative', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Trigger 1: Account Balance Cannot Go Negative</h1>

<div class="description">
    <div class="meta">Responsible: Borhan Javadian</div>
    <code>trg_prevent_negative_balance</code> fires <em>BEFORE UPDATE</em> on
    <code>Account</code>. If the new <code>Balance</code> is negative, the trigger
    raises a <code>SIGNAL</code> and blocks the update. Successful updates persist
    in the database; the dropdown above is refreshed after each attempt so it
    always shows the current balance.
</div>

<div class="card">
    <h2 class="mt-0">Try an Update</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="account_id">Account</label>
                <select id="account_id" name="account_id">
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?php echo (int) $account['AccountID']; ?>" <?php echo ((string) $account['AccountID'] === (string) $accountId) ? 'selected' : ''; ?>>
                            <?php echo app_e($account['Number']); ?> - current balance: <?php echo app_e($account['Balance']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="new_balance">New balance</label>
                <input
                    type="number"
                    step="0.01"
                    id="new_balance"
                    name="new_balance"
                    placeholder="e.g. 10000 or -500"
                    value="<?php echo app_e($newBalance); ?>"
                    required
                >
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Try Update</button>
    </form>

    <?php if ($message !== ''): ?>
        <?php app_render_output($messageType, $messageType === 'success' ? 'Trigger allowed the update' : 'Trigger blocked the update', $message); ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3 class="mt-0">Suggested cases</h3>
    <ul>
        <li><strong>Allowed:</strong> set the balance to any non-negative value such as <code>10000</code>.</li>
        <li><strong>Blocked:</strong> try a negative value such as <code>-500</code>.</li>
        <li><strong>Allowed:</strong> zero is valid, so <code>0</code> should pass.</li>
    </ul>
</div>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
