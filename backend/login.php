<?php
// backend/login.php
include 'db.php';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['status'=>'error','message'=>'Missing credentials']);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo json_encode(['status'=>'success','user_id'=>$user['user_id'],'name'=>$user['name'],'role'=>$user['role']]);
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid password']);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'User not found']);
}
$stmt->close();
?>
