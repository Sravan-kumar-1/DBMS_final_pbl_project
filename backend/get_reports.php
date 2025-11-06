<?php
include "db.php";

$query = "SELECT service_name, COUNT(*) as count FROM bookings GROUP BY service_name";
$result = $conn->query($query);

$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
