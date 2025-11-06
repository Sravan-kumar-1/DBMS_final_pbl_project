<?php
// backend/signup.php
include 'db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(['status'=>'error','message'=>'Missing fields']);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name,email,phone,password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $hash);
if ($stmt->execute()) {
    echo json_encode(['status'=>'success','message'=>'Signup successful']);
} else {
    echo json_encode(['status'=>'error','message'=>$conn->error]);
}
$stmt->close();
?>
