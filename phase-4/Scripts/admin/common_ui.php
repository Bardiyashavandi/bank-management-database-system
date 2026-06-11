<?php

function app_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_theme_palette($theme = 'user')
{
    if ($theme === 'admin') {
        return [
            'bg' => '#fbf7f1',
            'surface' => '#ffffff',
            'border' => '#f1e3cf',
            'border-strong' => '#e6d2b3',
            'text' => '#1a1410',
            'muted' => '#7a6753',
            'primary' => '#9a4a13',
            'primary-hover' => '#7a3909',
            'primary-soft' => '#fdf2e2',
            'primary-glow' => 'rgba(154, 74, 19, 0.18)',
            'accent' => '#d97706',
            'success-bg' => '#ecfdf5',
            'success-border' => '#a7f3d0',
            'success-text' => '#065f46',
            'error-bg' => '#fef2f2',
            'error-border' => '#fecaca',
            'error-text' => '#991b1b',
            'shadow-sm' => '0 1px 2px rgba(26, 20, 16, 0.04), 0 1px 3px rgba(26, 20, 16, 0.06)',
            'shadow-md' => '0 4px 6px -1px rgba(26, 20, 16, 0.05), 0 12px 24px -4px rgba(26, 20, 16, 0.08)',
            'radius' => '12px',
            'radius-sm' => '8px',
        ];
    }

    return [
        'bg' => '#f7f8fc',
        'surface' => '#ffffff',
        'border' => '#e6e8f0',
        'border-strong' => '#cfd4e0',
        'text' => '#0b1220',
        'muted' => '#5b6478',
        'primary' => '#2f56d6',
        'primary-hover' => '#1e40af',
        'primary-soft' => '#eef3ff',
        'primary-glow' => 'rgba(47, 86, 214, 0.18)',
        'accent' => '#0ea5e9',
        'success-bg' => '#ecfdf5',
        'success-border' => '#a7f3d0',
        'success-text' => '#065f46',
        'error-bg' => '#fef2f2',
        'error-border' => '#fecaca',
        'error-text' => '#991b1b',
        'shadow-sm' => '0 1px 2px rgba(11, 18, 32, 0.04), 0 1px 3px rgba(11, 18, 32, 0.06)',
        'shadow-md' => '0 4px 6px -1px rgba(11, 18, 32, 0.05), 0 12px 24px -4px rgba(11, 18, 32, 0.08)',
        'radius' => '12px',
        'radius-sm' => '8px',
    ];
}

function app_stylesheet($theme = 'user')
{
    $palette = app_theme_palette($theme);
    $variables = '';

    foreach ($palette as $name => $value) {
        $variables .= "    --{$name}: {$value};\n";
    }

    return ":root {\n{$variables}}\n" . <<<'CSS'
* { box-sizing: border-box; }
*:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

html, body { margin: 0; padding: 0; }

body {
    font-family: -apple-system, BlinkMacSystemFont, "Inter", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: var(--text);
    background:
        radial-gradient(900px 380px at 6% -12%, var(--primary-soft) 0%, transparent 60%),
        radial-gradient(700px 320px at 100% -8%, var(--primary-soft) 0%, transparent 55%),
        var(--bg);
    background-attachment: fixed;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

a { color: var(--primary); text-decoration: none; transition: color 0.15s; }
a:hover { color: var(--primary-hover); text-decoration: underline; }

code {
    background: var(--primary-soft);
    color: var(--primary);
    padding: 2px 7px;
    border-radius: 5px;
    font-family: "SF Mono", "JetBrains Mono", Menlo, Consolas, monospace;
    font-size: 0.88em;
    font-weight: 500;
}

/* ---------- Top bar ---------- */
.topbar {
    background: rgba(255, 255, 255, 0.78);
    border-bottom: 1px solid var(--border);
    backdrop-filter: saturate(180%) blur(20px);
    -webkit-backdrop-filter: saturate(180%) blur(20px);
    position: sticky;
    top: 0;
    z-index: 50;
}
.topbar-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    gap: 24px;
}
.topbar .brand {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.01em;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}
.topbar .brand:hover { text-decoration: none; opacity: 0.95; }
.brand-mark {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 0.02em;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.2);
}
.topbar nav {
    margin-left: auto;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}
.topbar nav a {
    color: var(--muted);
    font-size: 14px;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 8px;
    transition: background 0.15s, color 0.15s;
}
.topbar nav a:hover {
    color: var(--text);
    background: var(--primary-soft);
    text-decoration: none;
}

/* ---------- Container ---------- */
.container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 36px 24px 64px;
}

h1 {
    font-size: 30px;
    margin: 0 0 8px;
    letter-spacing: -0.025em;
    font-weight: 700;
    color: var(--text);
}
h2 {
    font-size: 19px;
    margin: 32px 0 14px;
    letter-spacing: -0.01em;
    font-weight: 700;
    color: var(--text);
}
h3 {
    font-size: 16px;
    margin: 18px 0 8px;
    font-weight: 700;
    color: var(--text);
}
.lead {
    color: var(--muted);
    margin: 0;
    font-size: 15px;
    max-width: 720px;
}

/* ---------- Hero ---------- */
.hero {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 28px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: "";
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background:
        radial-gradient(420px 220px at 92% 0%, var(--primary-soft) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}
.hero > * { position: relative; z-index: 1; }
.hero .eyebrow {
    color: var(--primary);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 12px;
    display: inline-block;
    padding: 4px 11px;
    background: var(--primary-soft);
    border-radius: 999px;
}
.hero .stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 22px;
}
.hero .stat-chip {
    padding: 8px 14px;
    border-radius: 999px;
    background: var(--bg);
    border: 1px solid var(--border);
    color: var(--text);
    font-weight: 600;
    font-size: 13px;
}

/* ---------- Cards ---------- */
.card,
.ticket,
.comment,
.summary-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
}
.card { padding: 22px; margin-bottom: 16px; }
.card.tight { padding: 14px 18px; }
.card h2:first-child, .card h3:first-child { margin-top: 0; }

.description {
    background: linear-gradient(135deg, var(--primary-soft) 0%, var(--surface) 75%);
    border: 1px solid var(--border);
    border-left: 3px solid var(--primary);
    border-radius: var(--radius);
    padding: 18px 22px;
    margin-bottom: 24px;
}
.description .meta {
    color: var(--primary);
    font-size: 11px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-weight: 800;
}
.description ul { margin: 8px 0 0 20px; padding: 0; }

/* ---------- Grids ---------- */
.grid,
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
}
.grid { margin-bottom: 12px; }
.grid .card,
.summary-card {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 0;
}
.grid .card {
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}
.grid .card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-strong);
}
.grid .label,
.summary-card .label {
    font-size: 11px;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 700;
}
.grid .title {
    font-weight: 700;
    font-size: 17px;
    color: var(--text);
    line-height: 1.35;
}
.grid .author,
.summary-card .value {
    color: var(--muted);
    font-size: 13px;
}
.summary-card { padding: 22px; }
.summary-card .metric {
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.04em;
    color: var(--text);
    line-height: 1.1;
}

/* ---------- Buttons ---------- */
.btn,
button,
input[type=submit] {
    font-family: inherit;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: var(--radius-sm);
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s, color 0.15s, transform 0.05s, box-shadow 0.18s;
    white-space: nowrap;
    line-height: 1.4;
}
.btn:active { transform: translateY(1px); }
.btn-primary {
    background: var(--primary);
    color: #ffffff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.18);
}
.btn-primary:hover {
    background: var(--primary-hover);
    text-decoration: none;
    box-shadow: 0 4px 14px var(--primary-glow), inset 0 1px 0 rgba(255,255,255,0.18);
}
.btn-secondary {
    background: var(--surface);
    color: var(--text);
    border-color: var(--border-strong);
}
.btn-secondary:hover {
    background: var(--primary-soft);
    color: var(--primary);
    border-color: var(--primary);
    text-decoration: none;
}
.btn-danger {
    background: #dc2626;
    color: #ffffff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.18);
}
.btn-danger:hover {
    background: #b91c1c;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
}
.btn.disabled,
.btn[aria-disabled="true"] { opacity: 0.55; pointer-events: none; }

.btn-row,
.inline-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}
.grid .btn { margin-top: auto; align-self: flex-start; }

/* ---------- Forms ---------- */
form { margin: 0; }
.field { margin-bottom: 16px; }
.field label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 7px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.field-hint {
    color: var(--muted);
    font-size: 13px;
    margin-top: 8px;
}

.input,
input[type=text],
input[type=date],
input[type=number],
textarea,
select {
    width: 100%;
    max-width: 480px;
    padding: 11px 13px;
    font-size: 14px;
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text);
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: inherit;
    line-height: 1.4;
}
textarea { min-height: 120px; resize: vertical; }
.input:focus,
input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-glow);
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 14px;
}
.form-row .field {
    margin-bottom: 0;
    flex: 1 1 220px;
}

/* ---------- Tables ---------- */
.table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    margin-top: 12px;
    background: var(--surface);
}
table {
    border-collapse: collapse;
    width: 100%;
    font-size: 14px;
}
thead th {
    background: var(--bg);
    color: var(--muted);
    text-align: left;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.07em;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
}
tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: var(--bg); }
td em { color: var(--muted); font-style: italic; }

/* ---------- Output boxes ---------- */
.output {
    margin: 18px 0;
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--surface);
    font-size: 14px;
}
.output .label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
    margin-bottom: 6px;
    font-weight: 700;
}
.output.success {
    background: var(--success-bg);
    border-color: var(--success-border);
    color: var(--success-text);
}
.output.success .label { color: var(--success-text); opacity: 0.85; }
.output.error {
    background: var(--error-bg);
    border-color: var(--error-border);
    color: var(--error-text);
}
.output.error .label { color: var(--error-text); opacity: 0.85; }

/* ---------- Tickets & comments ---------- */
.ticket {
    padding: 20px;
    margin-bottom: 14px;
    transition: border-color 0.15s, box-shadow 0.15s, transform 0.18s;
}
.ticket:hover {
    border-color: var(--border-strong);
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}
.ticket-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 12px;
    margin-bottom: 10px;
}
.ticket .who { font-weight: 700; color: var(--text); }
.ticket .when { color: var(--muted); font-size: 13px; }
.ticket .body { white-space: pre-wrap; margin: 8px 0 14px; color: var(--text); }

.pill {
    display: inline-block;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 800;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-left: 6px;
}
.pill.active { background: var(--success-bg); color: var(--success-text); }
.pill.resolved { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.pill.admin { background: #fef3c7; color: #92400e; }

.comment {
    padding: 14px 16px;
    margin-bottom: 10px;
}
.comment.admin { border-color: #fcd34d; background: #fffbeb; }
.comment .meta {
    color: var(--muted);
    font-size: 12px;
    margin-bottom: 8px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}
.comment .meta .who { font-weight: 800; color: var(--text); }
.comment .body { white-space: pre-wrap; color: var(--text); }

/* ---------- Helpers ---------- */
.muted-list { margin: 0; padding-left: 20px; color: var(--muted); }
.muted-list li { margin-bottom: 4px; }

.footer-nav {
    margin-top: 32px;
    padding-top: 18px;
    border-top: 1px solid var(--border);
    color: var(--muted);
    font-size: 13px;
}

.text-muted { color: var(--muted); }
.mt-0 { margin-top: 0; }
.mt-4 { margin-top: 24px; }
.mb-0 { margin-bottom: 0; }

@media (max-width: 720px) {
    .container { padding: 24px 16px 44px; }
    .topbar-inner { padding: 12px 16px; }
    .ticket-head { flex-direction: column; align-items: flex-start; }
    .hero, .card, .ticket, .summary-card { padding: 18px; }
    h1 { font-size: 24px; }
}
CSS;
}

function app_render_page_start($title, $options = [])
{
    $theme = $options['theme'] ?? 'user';
    $brandHref = $options['brand_href'] ?? 'index.php';
    $brandLabel = $options['brand_label'] ?? ($theme === 'admin' ? 'Bank Management Admin' : 'Bank Management');
    $navLinks = $options['nav_links'] ?? [];

    // Brand mark: a single letter inside a colored gradient square.
    $brandInitial = $theme === 'admin' ? 'A' : 'B';

    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"en\">\n";
    echo "<head>\n";
    echo "    <meta charset=\"UTF-8\">\n";
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
    echo '    <title>' . app_e($title) . "</title>\n";
    echo "    <style>\n" . app_stylesheet($theme) . "\n    </style>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "    <header class=\"topbar\">\n";
    echo "        <div class=\"topbar-inner\">\n";
    echo '            <a class="brand" href="' . app_e($brandHref) . '">';
    echo '<span class="brand-mark">' . app_e($brandInitial) . '</span>';
    echo app_e($brandLabel);
    echo "</a>\n";
    echo "            <nav>\n";

    foreach ($navLinks as $href => $label) {
        echo '                <a href="' . app_e($href) . '">' . app_e($label) . "</a>\n";
    }

    echo "            </nav>\n";
    echo "        </div>\n";
    echo "    </header>\n";
    echo "    <main class=\"container\">\n";
}

function app_render_page_end()
{
    echo "    </main>\n";
    echo "</body>\n";
    echo "</html>\n";
}

function app_render_output($type, $label, $message)
{
    echo '<div class="output ' . app_e($type) . '">';
    echo '<div class="label">' . app_e($label) . '</div>';
    echo app_e($message);
    echo '</div>';
}

function app_render_footer_link($href, $label)
{
    echo '<div class="footer-nav"><a href="' . app_e($href) . '">&larr; ' . app_e($label) . '</a></div>';
}

function app_status_class($active)
{
    return $active ? 'active' : 'resolved';
}

function app_status_text($active)
{
    return $active ? 'Active' : 'Resolved';
}
