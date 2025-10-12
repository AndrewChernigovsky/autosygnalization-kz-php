<?php
// run_migrations.php
// CLI helper to run all .sql files from migrations/ in alphabetical order.
// Usage:
// php run_migrations.php --host=127.0.0.1 --port=3306 --db=autosygnals --user=root --pass=secret
// or rely on env vars DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from CLI.\n";
    exit(1);
}

$opts = getopt('', ['host::', 'port::', 'db::', 'user::', 'pass::', 'dir::', 'dry-run', 'help']);
if (isset($opts['help'])) {
    echo "Usage: php run_migrations.php [--host=] [--port=] [--db=] [--user=] [--pass=] [--dir=] [--dry-run]\n";
    exit(0);
}

$host = $opts['host'] ?? getenv('DB_HOST') ?: '127.0.0.1';
$port = $opts['port'] ?? getenv('DB_PORT') ?: '3306';
$db   = $opts['db']   ?? getenv('DB_NAME') ?: 'autosygnals';
$user = $opts['user'] ?? getenv('DB_USER') ?: 'root';
$pass = $opts['pass'] ?? getenv('DB_PASS') ?: '';
$dir  = $opts['dir']  ?? __DIR__ . '/migrations';
$dry  = isset($opts['dry-run']);

echo "Run migrations from: $dir\n";
echo "DB: $user@{$host}:{$port}/{$db}" . ($dry ? " (dry-run)\n" : "\n");

if (!is_dir($dir)) {
    fwrite(STDERR, "Migrations directory not found: $dir\n");
    exit(2);
}

$files = glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.sql');
sort($files, SORT_STRING);
if (!$files) {
    echo "No .sql files found in migrations directory.\n";
    exit(0);
}

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (Exception $e) {
    fwrite(STDERR, "PDO connection failed: " . $e->getMessage() . "\n");
    exit(3);
}

$summary = ['applied' => 0, 'skipped' => 0, 'failed' => 0];
foreach ($files as $file) {
    $name = basename($file);
    echo "\n=== Running: $name ===\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        fwrite(STDERR, "Failed to read file: $file\n");
        $summary['failed']++;
        continue;
    }

    if ($dry) {
        echo $sql . "\n";
        $summary['skipped']++;
        continue;
    }

    try {
        // Try running as a transaction
        $pdo->beginTransaction();
        // PDO::exec can execute multiple statements when using the native driver
        $pdo->exec($sql);
        $pdo->commit();
        echo "OK: $name applied.\n";
        $summary['applied']++;
        continue;
    } catch (Exception $e) {
        // Rollback and try fallback using mysqli multi_query
        try { $pdo->rollBack(); } catch (Exception $_) {}
        fwrite(STDERR, "PDO exec failed for $name: " . $e->getMessage() . "\n");
        echo "Attempting fallback with mysqli->multi_query...\n";
        $mysqli = new mysqli($host, $user, $pass, $db, (int)$port);
        if ($mysqli->connect_errno) {
            fwrite(STDERR, "mysqli connect failed: " . $mysqli->connect_error . "\n");
            $summary['failed']++;
            continue;
        }

        if ($mysqli->multi_query($sql)) {
            do {
                if ($res = $mysqli->store_result()) {
                    $res->free();
                }
            } while ($mysqli->more_results() && $mysqli->next_result());
            echo "OK (mysqli): $name applied.\n";
            $summary['applied']++;
        } else {
            fwrite(STDERR, "mysqli multi_query failed for $name: " . $mysqli->error . "\n");
            $summary['failed']++;
        }
        $mysqli->close();
    }
}

echo "\nSummary: applied={$summary['applied']}, skipped={$summary['skipped']}, failed={$summary['failed']}\n";
if ($summary['failed'] > 0) exit(5);
exit(0);


