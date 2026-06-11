<?php
require 'db.php';
require_once __DIR__ . '/common_ui.php';

$message = '';
$messageType = '';
$employeeId = $_POST['employee_id'] ?? '';
$branchId = $_POST['branch_id'] ?? '';
$startFrom = $_POST['start_from'] ?? '';
$until = $_POST['until'] ?? '';

// Load Employees and Branches for the dropdowns.
$employees = [];
if ($res = $mysqli->query('SELECT EmployeeID, FullName FROM Employee ORDER BY EmployeeID')) {
    while ($row = $res->fetch_assoc()) { $employees[] = $row; }
}
$branches = [];
if ($res = $mysqli->query('SELECT BranchID, Address FROM Branch ORDER BY BranchID')) {
    while ($row = $res->fetch_assoc()) { $branches[] = $row; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($employeeId === '' || $branchId === '' || $startFrom === '') {
        $message = 'Employee, Branch, and StartFrom are required. Until can be left empty for an open-ended period.';
        $messageType = 'error';
    } elseif (!ctype_digit((string) $employeeId) || !ctype_digit((string) $branchId)) {
        $message = 'Employee and Branch IDs must be positive integers.';
        $messageType = 'error';
    } else {
        // Pre-delete any row with the same primary key so re-submitting the
        // same combination doesn't trip the PK constraint instead of the
        // trigger we are demonstrating.
        $delete = $mysqli->prepare('DELETE FROM WorksIn WHERE EmployeeID = ? AND BranchID = ? AND StartFrom = ?');
        if ($delete !== false) {
            $delete->bind_param('iis', $employeeId, $branchId, $startFrom);
            $delete->execute();
            $delete->close();
        }

        $stmt = $mysqli->prepare('INSERT INTO WorksIn (EmployeeID, BranchID, StartFrom, Until) VALUES (?, ?, ?, ?)');
        if ($stmt === false) {
            $message = 'Failed to prepare query: ' . $mysqli->error;
            $messageType = 'error';
        } else {
            $untilForBind = $until === '' ? null : $until;
            $stmt->bind_param('iiss', $employeeId, $branchId, $startFrom, $untilForBind);
            if ($stmt->execute()) {
                $printedUntil = $until === '' ? 'NULL' : $until;
                $message = "INSERT accepted. Row (EmployeeID={$employeeId}, BranchID={$branchId}, StartFrom={$startFrom}, Until={$printedUntil}) was added to WorksIn.";
                $messageType = 'success';
            } else {
                $message = 'INSERT blocked: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        }
    }
}

app_render_page_start('Trigger 2 - Employee Work Date Validation', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<h1>Trigger 2: Employee Work Date Validation</h1>

<div class="description">
    <div class="meta">Responsible: Bardiya Shavandi</div>
    <code>trg_validate_worksin_dates</code> fires <em>BEFORE INSERT</em> on
    <code>WorksIn</code>. It blocks rows where <code>Until</code> is not later than
    <code>StartFrom</code>, and it also blocks rows whose <code>StartFrom</code> is in the future.
</div>

<div class="card">
    <h2 class="mt-0">Try an Insert</h2>
    <form method="POST">
        <div class="form-row">
            <div class="field">
                <label for="employee_id">Employee</label>
                <select id="employee_id" name="employee_id" required>
                    <option value="">-- choose --</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo (int) $emp['EmployeeID']; ?>"
                            <?php echo ((string) $emp['EmployeeID'] === (string) $employeeId) ? 'selected' : ''; ?>>
                            <?php echo (int) $emp['EmployeeID']; ?> &mdash; <?php echo app_e($emp['FullName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="branch_id">Branch</label>
                <select id="branch_id" name="branch_id" required>
                    <option value="">-- choose --</option>
                    <?php foreach ($branches as $br): ?>
                        <option value="<?php echo (int) $br['BranchID']; ?>"
                            <?php echo ((string) $br['BranchID'] === (string) $branchId) ? 'selected' : ''; ?>>
                            <?php echo (int) $br['BranchID']; ?> &mdash; <?php echo app_e($br['Address']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="field">
                <label for="start_from">Start From</label>
                <input type="date" id="start_from" name="start_from" value="<?php echo app_e($startFrom); ?>" required>
            </div>
            <div class="field">
                <label for="until">Until (optional)</label>
                <input type="date" id="until" name="until" value="<?php echo app_e($until); ?>">
            </div>
        </div>
        <div class="field-hint">Successful inserts persist in the <code>WorksIn</code> table; you can verify them in phpMyAdmin. Re-submitting the same Employee + Branch + StartFrom combination replaces the previous row so the trigger can be tested repeatedly.</div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Try Insert</button>
        </div>
    </form>

    <?php if ($message !== ''): ?>
        <?php app_render_output($messageType, $messageType === 'success' ? 'Trigger allowed the insert' : 'Trigger blocked the insert', $message); ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3 class="mt-0">Suggested cases</h3>
    <ul>
        <li><strong>Allowed:</strong> Employee <code>1</code>, Branch <code>2</code>, StartFrom <code>2024-03-01</code>, Until empty.</li>
        <li><strong>Blocked (Until &le; StartFrom):</strong> Employee <code>2</code>, Branch <code>3</code>, StartFrom <code>2024-01-01</code>, Until <code>2023-06-01</code>.</li>
        <li><strong>Blocked (StartFrom in future):</strong> Employee <code>3</code>, Branch <code>4</code>, StartFrom <code>2099-01-01</code>, Until empty.</li>
    </ul>
</div>

<?php
app_render_footer_link('index.php', 'Back to dashboard');
app_render_page_end();
?>
