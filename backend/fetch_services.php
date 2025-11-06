<?php
// backend/fetch_services.php
include 'db.php';
$res = $conn->query("SELECT service_id, service_name, description, category, price FROM services ORDER BY category, service_name");
$data = [];
while ($r = $res->fetch_assoc()) $data[] = $r;
header('Content-Type: application/json');
echo json_encode($data);
?>
