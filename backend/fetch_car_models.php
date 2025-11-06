<?php
// backend/fetch_car_models.php
include 'db.php';
$res = $conn->query("SELECT car_model_id, make, model FROM car_models ORDER BY make, model");
$data = [];
while ($r = $res->fetch_assoc()) $data[] = $r;
header('Content-Type: application/json');
echo json_encode($data);
?>
