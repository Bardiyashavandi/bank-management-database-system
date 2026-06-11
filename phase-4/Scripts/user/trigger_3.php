<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$message = '';
$messageType = '';
$accountId = $_POST['account_id'] ?? '1';
$amount = $_POST['amount'] ?? '';
$transDate = date('Y-m-d'); // Use current date for simplicity

$accounts = [];
if ($res = $mysqli->query('SELECT AccountID, Number, Balance FROM Account ORDER BY AccountID')) {
    while ($row = $res->fetch_assoc()) {
        $accounts[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accountId === '' || $amount === '' || !is_numeric($amount)) {
        $message = 'Please pick an account and enter a numeric amount.';
        $messageType = 'error';
    } else {
        // Find max TransactionID to safely insert
        $nextId = 1;
        $maxRes = $mysqli->query('SELECT MAX(TransactionID) AS max_id FROM `Transaction`');
        if ($maxRes && $maxRow = $maxRes->fetch_assoc()) {
            $nextId = (int)$maxRow['max_id'] + 1;
        }
        
        $stmt = $mysqli->prepare('INSERT INTO `Transaction` (TransactionID, Amount, TransDate, AccountID) VALUES (?, ?, ?, ?)');
        if ($stmt === false) {
            $message = 'Failed to prepare query: ' . $mysqli->error;
            $messageType = 'error';
        } else {
            $stmt->bind_param('idsi', $nextId, $amount, $transDate, $accountId);
            if ($stmt->execute()) {
                $message = "INSERT accepted. Transaction {$nextId} (AccountID={$accountId}, Amount={$amount}, Date={$transDate}) was recorded and AccountID={$accountId} balance decreased by {$amount}.";
                $messageType = 'success';
            } else {
                $message = 'INSERT blocked by trigger: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        }
    }
}

app_render_page_start('Trigger 3 - Transaction Amount Validation', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Trigger 3: Transaction Amount Validation</h1>

<div class="description">
    <div class="meta">Responsible: Faraz Sahabi</div>
    <code>trg_validate_transaction_amount</code> fires <em>BEFORE INSERT</em> on
    <code>Transaction</code>. If the new <code>Amount</code> is zero or negative, the trigger
    raises a <code>SIGNAL</code> and blocks the insert. Successful inserts persist in the
    <code>Transaction</code> table and the linked <code>Account.Balance</code> is
    decremented by the transaction amount automatically (handled in SQL by an internal
    AFTER INSERT trigger). If the resulting balance would go negative, Trigger 1's
    BEFORE UPDATE check fires and the whole insert is rolled back, so neither table
    changes &mdash; you can verify both behaviors in phpMyAdmin.
</div>

<div class="card">
    <h2 class="mt-0">Try an Insert</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="account_id">Account</label>
                <select id="account_id" name="account_id">
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?php echo (int) $account['AccountID']; ?>" <?php echo ((string) $account['AccountID'] === (string) $accountId) ? 'selected' : ''; ?>>
                            <?php echo app_e($account['Number']); ?> - balance: <?php echo app_e($account['Balance']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="amount">Amount</label>
                <input
                    type="number"
                    step="0.01"
                    id="amount"
                    name="amount"
                    placeholder="e.g. 500 or -10"
                    value="<?php echo app_e($amount); ?>"
                    required
                >
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Try Insert</button>
    </form>

    <?php if ($message !== ''): ?>
        <?php app_render_output($messageType, $messageType === 'success' ? 'Trigger allowed the insert' : 'Trigger blocked the insert', $message); ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3 class="mt-0">Suggested cases</h3>
    <ul>
        <li><strong>Allowed:</strong> a positive amount that is less than or equal to the account's current balance, such as <code>50</code> on an account with <code>100</code>.</li>
        <li><strong>Blocked by Trigger 3:</strong> a negative or zero amount such as <code>-10</code> or <code>0</code>.</li>
        <li><strong>Blocked by Trigger 1 (cascade):</strong> a positive amount larger than the account's balance, such as <code>9999</code> on an account with <code>100</code> &mdash; the linked balance update would go negative, so Trigger 1 raises a SIGNAL and the entire INSERT is rolled back.</li>
    </ul>
</div>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
