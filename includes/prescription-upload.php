<?php
/**
 * Saves an uploaded prescription file for $user and alerts the pharmacists.
 * Returns [prescriptionId, null] on success or [null, errorMessage].
 */
function save_prescription_upload(array $user, ?array $f, ?string $notes = null): array
{
    if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return [null, 'Please choose a photo or PDF of your prescription (max 5 MB).'];
    }
    if ($f['size'] > 5 * 1024 * 1024) {
        return [null, 'That file is too large — the maximum size is 5 MB.'];
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];
    $mime = mime_content_type($f['tmp_name']);
    if (!isset($allowed[$mime])) {
        return [null, 'Only photos (JPG, PNG, WEBP) or PDF files are accepted.'];
    }
    $name = 'rx_' . $user['id'] . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($f['tmp_name'], ROOT_PATH . '/uploads/prescriptions/' . $name)) {
        return [null, 'Upload failed — please try again.'];
    }
    q('INSERT INTO prescriptions (user_id, file_path, original_name, notes, verify_token) VALUES (?,?,?,?,?)', [
        $user['id'], 'uploads/prescriptions/' . $name, substr($f['name'], 0, 255), $notes ?: null, new_token(),
    ]);
    $id = (int)db()->lastInsertId();
    foreach (q_all("SELECT id FROM users WHERE role IN ('pharmacist','admin')") as $staff) {
        notify((int)$staff['id'], 'prescription', 'New prescription from ' . $user['name'], 'Waiting for review.', url('staff/prescriptions.php'));
    }
    return [$id, null];
}
