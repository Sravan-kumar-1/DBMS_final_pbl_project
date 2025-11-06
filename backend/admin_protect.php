<?php
// backend/admin_protect.php
session_start();
// For simple demo: we accept a query param admin_key=demo_admin_key
$ok = false;
if (!empty($_GET['admin_key']) && $_GET['admin_key'] === 'show-me-admin-2025') $ok = true;
if (!$ok) {
    http_response_code(403);
    echo "Forbidden. Use admin_view.php?admin_key=show-me-admin-2025";
    exit;
}
?>
