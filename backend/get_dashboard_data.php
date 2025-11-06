<?php
include "db.php";

// Total Bookings
$total = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];

// Most booked service
$topService = $conn->query("
    SELECT service_name, COUNT(*) as c
    FROM bookings
    GROUP BY service_name
    ORDER BY c DESC LIMIT 1
")->fetch_assoc();

// Most popular car model
$topCar = $conn->query("
    SELECT car_model, COUNT(*) as c
    FROM bookings
    GROUP BY car_model
    ORDER BY c DESC LIMIT 1
")->fetch_assoc();

// Data for charts
$chartData = [];
$result = $conn->query("
    SELECT service_name, COUNT(*) as total
    FROM bookings
    GROUP BY service_name
");
while($row = $result->fetch_assoc()) {
    $chartData[] = $row;
}

echo json_encode([
    "total_bookings" => $total,
    "top_service" => $topService,
    "top_car" => $topCar,
    "chart_data" => $chartData
]);
