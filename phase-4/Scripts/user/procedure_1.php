<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$rows = null;
$error = null;
$minBalance = '';
$maxBalance = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $minBalance = $_POST['min_balance'] ?? '';
    $maxBalance = $_POST['max_balance'] ?? '';

    if ($minBalance === '' || $maxBalance === '') {
        $error = 'Please provide both a minimum and a maximum balance.';
    } elseif (!is_numeric($minBalance) || !is_numeric($maxBalance)) {
        $error = 'Both values must be numeric.';
    } elseif ((float) $minBalance > (float) $maxBalance) {
        $error = 'Minimum balance cannot be greater than maximum balance.';
    } else {
        $stmt = $mysqli->prepare('CALL GetCustomersInBalanceRange(?, ?)');
        if ($stmt === false) {
            $error = 'Failed to prepare call: ' . $mysqli->error;
        } else {
            $stmt->bind_param('dd', $minBalance, $maxBalance);
            if (!$stmt->execute()) {
                $error = 'Procedure failed: ' . $stmt->error;
            } else {
                $result = $stmt->get_result();
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
            }
            $stmt->close();
        }
    }
}

app_render_page_start('Procedure 1 - Customer Balances in Range', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Procedure 1: Customer Balances in Range</h1>

<div class="description">
    <div class="meta">Responsible: Borhan Javadian</div>
    <code>GetCustomersInBalanceRange(p_min, p_max)</code> returns every customer-account
    row whose <code>Balance</code> falls inside the given range, ordered by balance ascending.
</div>

<div class="card">
    <h2 class="mt-0">Parameters</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="min_balance">Minimum balance</label>
                <input type="number" step="0.01" id="min_balance" name="min_balance" value="<?php echo app_e($minBalance); ?>" required>
            </div>
            <div class="field">
                <label for="max_balance">Maximum balance</label>
                <input type="number" step="0.01" id="max_balance" name="max_balance" value="<?php echo app_e($maxBalance); ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Call Procedure</button>
    </form>
</div>

<?php if ($error !== null): ?>
    <?php app_render_output('error', 'Error', $error); ?>
<?php elseif ($rows !== null): ?>
    <div class="card">
        <h2 class="mt-0">Results</h2>
        <?php if (count($rows) === 0): ?>
            <p class="text-muted mb-0">
                No accounts have a balance between <?php echo app_e($minBalance); ?> and <?php echo app_e($maxBalance); ?>.
            </p>
        <?php else: ?>
            <p class="text-muted"><?php echo count($rows); ?> row(s) returned.</p>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <?php foreach (array_keys($rows[0]) as $column): ?>
                                <th><?php echo app_e($column); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo $value === null ? '<em>NULL</em>' : app_e($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
