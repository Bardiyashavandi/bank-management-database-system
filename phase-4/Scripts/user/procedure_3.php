<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$customerId = $_POST['customer_id'] ?? '';
$results = null;
$error = '';

$customers = [];
if ($res = $mysqli->query('SELECT CustomerID, FullName FROM Customer ORDER BY FullName')) {
    while ($row = $res->fetch_assoc()) {
        $customers[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($customerId === '') {
        $error = 'Please select a customer.';
    } else {
        $stmt = $mysqli->prepare('CALL GetTransactionCountByCustomer(?)');
        if ($stmt === false) {
            $error = 'Failed to prepare call: ' . $mysqli->error;
        } else {
            $stmt->bind_param('i', $customerId);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $results = [];
                while ($row = $res->fetch_assoc()) {
                    $results[] = $row;
                }
                $res->free();
            } else {
                $error = 'Procedure execution failed: ' . $stmt->error;
            }
            $stmt->close();
            // Free any subsequent result sets from the stored procedure
            while ($mysqli->more_results() && $mysqli->next_result()) {
                if ($res = $mysqli->store_result()) {
                    $res->free();
                }
            }
        }
    }
}

// Pre-compute summary values used by the result card.
$count = ($results !== null && isset($results[0]['TransactionCount'])) ? (int) $results[0]['TransactionCount'] : 0;
$selectedName = '';
if ($results !== null) {
    foreach ($customers as $customer) {
        if ((string) $customer['CustomerID'] === (string) $customerId) {
            $selectedName = $customer['FullName'];
            break;
        }
    }
}

app_render_page_start('Procedure 3 - Transaction Count by Customer', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Procedure 3: Transaction Count by Customer</h1>

<div class="description">
    <div class="meta">Responsible: Faraz Sahabi</div>
    <code>GetTransactionCountByCustomer(p_customer_id)</code> calculates and returns the 
    total number of transactions for the specified customer across all their accounts.
</div>

<div class="card">
    <h2 class="mt-0">Run Procedure</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">-- Select a Customer --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo (int) $customer['CustomerID']; ?>" <?php echo ((string) $customer['CustomerID'] === (string) $customerId) ? 'selected' : ''; ?>>
                            <?php echo app_e($customer['FullName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Execute</button>
    </form>

    <?php if ($error !== ''): ?>
        <?php app_render_output('error', 'Execution Error', $error); ?>
    <?php endif; ?>
</div>

<?php if ($results !== null): ?>
    <h2>Result</h2>
    <div class="summary-grid">
        <div class="summary-card">
            <div class="label">Customer</div>
            <div class="metric" style="font-size:22px"><?php echo app_e($selectedName); ?></div>
            <div class="value">CustomerID <?php echo app_e($customerId); ?></div>
        </div>
        <div class="summary-card">
            <div class="label">Total Transactions</div>
            <div class="metric"><?php echo $count; ?></div>
            <div class="value">Across all of the customer's accounts</div>
        </div>
    </div>
<?php endif; ?>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
