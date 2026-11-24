<?php
/** PDO connection — single shared instance, exceptions on, real prepares. */
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        if (DB_SSL) {
            // Encrypted connection for hosted MySQL. With a CA certificate the server is verified too.
            if (DB_SSL_CA !== '') {
                $caFile = sys_get_temp_dir() . '/medio-db-ca-' . substr(hash('sha256', DB_SSL_CA), 0, 12) . '.pem';
                if (!is_file($caFile)) file_put_contents($caFile, str_replace('\\n', "\n", DB_SSL_CA));
                $opts[PDO::MYSQL_ATTR_SSL_CA] = $caFile;
                $opts[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
            } else {
                foreach (['/etc/ssl/certs/ca-certificates.crt', '/etc/pki/tls/certs/ca-bundle.crt', '/etc/ssl/cert.pem', 'C:/xampp/apache/bin/curl-ca-bundle.crt'] as $bundle) {
                    if (is_file($bundle)) { $opts[PDO::MYSQL_ATTR_SSL_CA] = $bundle; break; }
                }
                $opts[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }
        }
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';port=' . (int)DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            $opts
        );
        $pdo->exec("SET time_zone = '+05:30'"); // Sri Lanka time, whatever the server's clock zone is
    }
    return $pdo;
}

/** Run a prepared query and return the statement. */
function q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}

function q_one(string $sql, array $params = []): ?array
{
    $row = q($sql, $params)->fetch();
    return $row === false ? null : $row;
}

function q_all(string $sql, array $params = []): array
{
    return q($sql, $params)->fetchAll();
}

function q_val(string $sql, array $params = [])
{
    $v = q($sql, $params)->fetchColumn();
    return $v === false ? null : $v;
}
