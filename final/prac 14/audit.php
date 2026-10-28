<?php
// Record meaningful administrative actions using a prepared statement.
function writeAuditLog(mysqli $conn, string $action, string $details = ''): void {
    if (($_SESSION['role'] ?? '') !== 'Admin' || empty($_SESSION['student_id'])) return;
    try {
        $stmt = $conn->prepare('INSERT INTO audit_logs (user_id, user_name, user_role, action, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)');
        $id = (int)$_SESSION['student_id'];
        $name = (string)($_SESSION['name'] ?? 'Admin');
        $role = 'Admin';
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
        $stmt->bind_param('isssss', $id, $name, $role, $action, $details, $ip);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $error) {
        error_log('Audit write failed: ' . $error->getMessage());
    }
}
