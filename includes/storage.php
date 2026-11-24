<?php
/**
 * Where uploaded files live.
 *   STORAGE_DRIVER = 'disk' (default, XAMPP / normal hosting): files in /uploads
 *   STORAGE_DRIVER = 'db'   (Vercel and other read-only hosts): files in the MySQL table stored_files
 * Paths stay the same either way (e.g. "uploads/prescriptions/rx_3_ab12.png").
 */

function storage_uses_db(): bool
{
    return STORAGE_DRIVER === 'db';
}

/** Save a freshly uploaded temp file under $relPath. */
function storage_save_upload(string $tmpFile, string $relPath, string $mime): bool
{
    if (!storage_uses_db()) {
        return move_uploaded_file($tmpFile, ROOT_PATH . '/' . $relPath);
    }
    $data = file_get_contents($tmpFile);
    if ($data === false) return false;
    $st = db()->prepare('REPLACE INTO stored_files (path, mime, size, data) VALUES (?,?,?,?)');
    $st->bindValue(1, $relPath);
    $st->bindValue(2, $mime);
    $st->bindValue(3, strlen($data), PDO::PARAM_INT);
    $st->bindValue(4, $data, PDO::PARAM_LOB);
    return $st->execute();
}

/** Read a stored file. Returns ['mime' => ..., 'data' => bytes] or null. Disk first, then database. */
function storage_read(?string $relPath): ?array
{
    if (!$relPath) return null;
    $disk = ROOT_PATH . '/' . $relPath;
    if (is_file($disk)) {
        return ['mime' => mime_content_type($disk) ?: 'application/octet-stream', 'data' => (string)file_get_contents($disk)];
    }
    if (storage_uses_db()) {
        $row = q_one('SELECT mime, data FROM stored_files WHERE path = ?', [$relPath]);
        if ($row) return ['mime' => $row['mime'], 'data' => $row['data']];
    }
    return null;
}

function storage_exists(?string $relPath): bool
{
    if (!$relPath) return false;
    if (is_file(ROOT_PATH . '/' . $relPath)) return true;
    return storage_uses_db() && (bool)q_val('SELECT 1 FROM stored_files WHERE path = ?', [$relPath]);
}
