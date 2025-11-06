<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "db.php";

$query = "
SELECT 
    b.booking_id,
    u.name AS customer_name,
    s.service_id,
    s.service_name,
    b.car_model,
    b.booking_date
FROM bookings b
LEFT JOIN users u ON b.user_id = u.user_id
LEFT JOIN services s ON b.service_id = s.service_id
ORDER BY b.booking_id DESC
";

$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
