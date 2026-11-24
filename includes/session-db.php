<?php
/**
 * Keeps log-in sessions in MySQL (table `sessions`) instead of files.
 * Needed on hosts like Vercel where each request may hit a different server
 * with no shared disk. Enabled with SESSION_DRIVER = 'db'.
 */
final class MedioDbSessionHandler implements SessionHandlerInterface
{
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }

    public function read(string $id): string|false
    {
        $data = q_val('SELECT data FROM sessions WHERE id = ? AND updated_at > DATE_SUB(NOW(), INTERVAL 14 DAY)', [$id]);
        return $data === false || $data === null ? '' : (string)$data;
    }

    public function write(string $id, string $data): bool
    {
        q('INSERT INTO sessions (id, data, updated_at) VALUES (?,?,NOW())
           ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW()', [$id, $data]);
        return true;
    }

    public function destroy(string $id): bool
    {
        q('DELETE FROM sessions WHERE id = ?', [$id]);
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        return q('DELETE FROM sessions WHERE updated_at < DATE_SUB(NOW(), INTERVAL 14 DAY)')->rowCount();
    }
}
