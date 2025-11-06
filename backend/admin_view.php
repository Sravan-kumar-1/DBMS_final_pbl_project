<?php
// backend/admin_view.php
include 'admin_protect.php';
include 'db.php';

$sql = "SELECT b.booking_id, u.name as user_name, u.email, b.car_model, s.service_name, b.pickup_address, b.contact_phone, b.booking_date, b.status
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.user_id
        LEFT JOIN services s ON b.service_id = s.service_id
        ORDER BY b.booking_date DESC";
$res = $conn->query($sql);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Bookings</title>
  <style>
    body{font-family:Arial; padding:20px}
    table{border-collapse:collapse; width:100%}
    th,td{border:1px solid #ddd; padding:8px}
    th{background:#333; color:white}
  </style>
</head>
<body>
  <h2>Bookings (Admin View)</h2>
  <p>Demo access key: <code>?admin_key=show-me-admin-2025</code></p>
  <table>
    <thead><tr>
      <th>ID</th><th>User</th><th>Email</th><th>Car Model</th><th>Service</th><th>Pickup Address</th><th>Phone</th><th>Date</th><th>Status</th>
    </tr></thead>
    <tbody>
<?php while ($row = $res->fetch_assoc()) { ?>
<tr>
  <td><?=htmlspecialchars($row['booking_id'])?></td>
  <td><?=htmlspecialchars($row['user_name'])?></td>
  <td><?=htmlspecialchars($row['email'])?></td>
  <td><?=htmlspecialchars($row['car_model'])?></td>
  <td><?=htmlspecialchars($row['service_name'])?></td>
  <td><?=htmlspecialchars($row['pickup_address'])?></td>
  <td><?=htmlspecialchars($row['contact_phone'])?></td>
  <td><?=htmlspecialchars($row['booking_date'])?></td>
  <td><?=htmlspecialchars($row['status'])?></td>
</tr>
<?php } ?>
    </tbody>
  </table>
</body>
</html>
