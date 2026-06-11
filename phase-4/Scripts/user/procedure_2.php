<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$rows = null;
$error = null;
$startDate = '';
$endDate = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($startDate === '' || $endDate === '') {
        $error = 'Please provide both a start date and an end date.';
    } else {
        $stmt = $mysqli->prepare('CALL GetEmployeesByDateRange(?, ?)');
        if ($stmt === false) {
            $error = 'Failed to prepare call: ' . $mysqli->error;
        } else {
            $stmt->bind_param('ss', $startDate, $endDate);
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

app_render_page_start('Procedure 2 - Employees by Date Range', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Procedure 2: Employees by Date Range</h1>

<div class="description">
    <div class="meta">Responsible: Bardiya Shavandi</div>
    <code>GetEmployeesByDateRange(p_start, p_end)</code> returns every employee whose
    <code>WorksIn</code> record <em>started</em> inside the supplied date range, together with
    their branch information. Open-ended assignments whose <code>StartFrom</code> falls outside
    the range are not reported.
</div>

<div class="card">
    <h2 class="mt-0">Parameters</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="start_date">Start date</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo app_e($startDate); ?>" required>
            </div>
            <div class="field">
                <label for="end_date">End date</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo app_e($endDate); ?>" required>
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
                No employees were active between <?php echo app_e($startDate); ?> and <?php echo app_e($endDate); ?>.
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
