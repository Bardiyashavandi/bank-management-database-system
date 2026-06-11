<?php
require_once __DIR__ . '/common_ui.php';

app_render_page_start('Bank Management - User Dashboard', [
    'brand_href' => 'index.php',
    'nav_links' => [
        'index.php' => 'Home',
        'tickets.php' => 'Tickets',
    ],
]);
?>
<section class="hero">
    <div class="eyebrow">Phase 4 User Interface</div>
    <h1>User Dashboard</h1>
    <p class="lead">
        Test the implemented triggers and stored procedures from the bank database,
        then move to the support section to create or track MongoDB tickets.
    </p>
    <div class="stats-row">
        <div class="stat-chip">3 triggers implemented</div>
        <div class="stat-chip">3 procedures implemented</div>
        <div class="stat-chip">Ticket system available</div>
    </div>
</section>

<h2>Triggers</h2>
<div class="grid">
    <div class="card">
        <div class="label">Trigger 1</div>
        <div class="title">Account Balance Cannot Go Negative</div>
        <div class="author">Borhan Javadian</div>
        <a class="btn btn-primary" href="trigger_1.php">Open Trigger Page</a>
    </div>
    <div class="card">
        <div class="label">Trigger 2</div>
        <div class="title">Employee Work Date Validation</div>
        <div class="author">Bardiya Shavandi</div>
        <a class="btn btn-primary" href="trigger_2.php">Open Trigger Page</a>
    </div>
    <div class="card">
        <div class="label">Trigger 3</div>
        <div class="title">Transaction Amount Validation</div>
        <div class="author">Faraz Sahabi</div>
        <a class="btn btn-primary" href="trigger_3.php">Open Trigger Page</a>
    </div>
</div>

<h2>Stored Procedures</h2>
<div class="grid">
    <div class="card">
        <div class="label">Procedure 1</div>
        <div class="title">Customer Balances in Range</div>
        <div class="author">Borhan Javadian</div>
        <a class="btn btn-primary" href="procedure_1.php">Open Procedure Page</a>
    </div>
    <div class="card">
        <div class="label">Procedure 2</div>
        <div class="title">Employees by Date Range</div>
        <div class="author">Bardiya Shavandi</div>
        <a class="btn btn-primary" href="procedure_2.php">Open Procedure Page</a>
    </div>
    <div class="card">
        <div class="label">Procedure 3</div>
        <div class="title">Transaction Count by Customer</div>
        <div class="author">Faraz Sahabi</div>
        <a class="btn btn-primary" href="procedure_3.php">Open Procedure Page</a>
    </div>
</div>

<h2>Support</h2>
<div class="grid">
    <div class="card">
        <div class="label">MongoDB Support</div>
        <div class="title">Create, review, and comment on active tickets</div>
        <div class="author">Flat PHP structure for submission</div>
        <a class="btn btn-primary" href="tickets.php">Open Tickets</a>
    </div>
</div>

<?php app_render_page_end(); ?>
