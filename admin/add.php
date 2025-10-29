<?php
// Standalone admin add page — improved error logging and debug output

// Include shared configuration
require_once __DIR__ . '/../config.php';

// Debug mode from shared config
$debug = (ENVIRONMENT === 'development');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo "Database connection failed.";
    if ($debug) echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}

// Simple input cleaner
function clean($v) {
    return trim(htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

// Log helper
function log_error($msg) {
    @file_put_contents(__DIR__ . '/admin_add_error.log', "[".date('Y-m-d H:i:s')."] ".$msg.PHP_EOL, FILE_APPEND);
}

$error = '';
$success = '';

// try to detect the admin table used by your project
$candidates = ['admins','admin_users','admin','users','users_admin'];
$foundTable = null;
foreach ($candidates as $t) {
    try {
        $stmt = $conn->query("SHOW TABLES LIKE " . $conn->quote($t));
        if ($stmt->fetchColumn()) { $foundTable = $t; break; }
    } catch (Exception $e) { /* ignore */ }
}

if (!$foundTable) {
    $error = "No admin table detected. Check admin/login.php for the correct table name.";
    log_error("No admin table found among candidates. DB tables check failed.");
} else {
    // get columns
    try {
        $colsStmt = $conn->query("SHOW COLUMNS FROM `{$foundTable}`");
        $cols = $colsStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } catch (Exception $e) {
        $error = "Unable to inspect table structure for '{$foundTable}'.";
        log_error("SHOW COLUMNS failed: ".$e->getMessage());
    }

    // determine username/email column and password column
    if (empty($error)) {
        $userCol = null;
        $passCol = null;
        if (in_array('username', $cols)) $userCol = 'username';
        elseif (in_array('email', $cols)) $userCol = 'email';

        if (in_array('password', $cols)) $passCol = 'password';
        elseif (in_array('pass', $cols)) $passCol = 'pass';

        if (!$userCol || !$passCol) {
            $error = "Table '{$foundTable}' does not have expected username/email and password columns.";
            log_error("Unexpected columns in {$foundTable}: " . implode(',', $cols));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error === '') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Both username and password are required.';
    } else {
        try {
            // duplicate check
            $dupSql = "SELECT 1 FROM `{$foundTable}` WHERE `{$userCol}` = ? LIMIT 1";
            $dupStmt = $conn->prepare($dupSql);
            $dupStmt->execute([$username]);
            if ($dupStmt->fetch()) {
                $error = 'Username/email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $insSql = "INSERT INTO `{$foundTable}` (`{$userCol}`, `{$passCol}`) VALUES (?, ?)";
                $ins = $conn->prepare($insSql);
                $ins->execute([$username, $hashed]);
                $success = 'Admin user created successfully.';
            }
        } catch (Exception $e) {
            $msg = "Insert failed: " . $e->getMessage();
            log_error($msg);
            $error = $debug ? $msg : 'Unable to create admin. Try again later.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        .container{max-width:480px;margin:0 auto}
        .form-group{margin-bottom:12px}
        label{display:block;margin-bottom:6px}
        input[type="text"],input[type="password"]{width:100%;padding:8px;box-sizing:border-box}
        .btn{padding:8px 12px;cursor:pointer}
        .alert{padding:10px;margin-bottom:12px;border-radius:4px}
        .alert-danger{background:#f8d7da;color:#721c24}
        .alert-success{background:#d4edda;color:#155724}
        pre{white-space:pre-wrap;background:#f3f3f3;padding:8px;border-radius:4px}
    </style>
</head>
<body>
<div class="container">
    <h2>Add Admin</h2>

    <?php if ($foundTable): ?>
        <p>Using table: <strong><?php echo htmlspecialchars($foundTable); ?></strong> (user column: <?php echo htmlspecialchars($userCol ?? ''); ?>, pass column: <?php echo htmlspecialchars($passCol ?? ''); ?>)</p>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label>Username or Email</label>
            <input name="username" type="text" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" required>
        </div>

        <button class="btn" type="submit">Create Admin</button>
        <a class="btn" href="index.php">Back</a>
    </form>

    <?php if ($debug): ?>
        <hr>
        <h4>Debug</h4>
        <p>Errors are logged to <code>admin_add_error.log</code> in this folder.</p>
    <?php endif; ?>
</div>
</body>
</html>