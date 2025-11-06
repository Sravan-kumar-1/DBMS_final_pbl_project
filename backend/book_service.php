<?php
// backend/book_service.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db.php'; // provides $conn (mysqli)

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST allowed');
    }

    // 1) Collect & validate inputs
    $name       = trim($_POST['name'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $service_id = trim($_POST['service_id'] ?? '');
    $car_model  = trim($_POST['car_model'] ?? '');

    if ($name === '' || $phone === '' || $service_id === '' || $car_model === '') {
        throw new Exception('Missing required fields: name, phone, service_id, car_model');
    }

    // 2) Ensure service exists
    $stmt = $conn->prepare("SELECT service_id FROM services WHERE service_id = ?");
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        throw new Exception('Invalid service_id');
    }
    $stmt->close();

    // 3) Find or create a user by phone (guest account)
    //    users requires: name, email (UNIQUE), password (hash), role
    //    We’ll search by phone first; if not found, create one.
    $user_id = null;

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ? LIMIT 1");
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $stmt->bind_result($user_id_found);
    if ($stmt->fetch()) {
        $user_id = $user_id_found;
    }
    $stmt->close();

    if ($user_id === null) {
        // create a synthetic email to satisfy UNIQUE(email)
        $email = 'guest+' . preg_replace('/\D+/', '', $phone) . '@example.local';
        // ensure uniqueness in case multiple bookings same phone very fast
        // try once; if dup email, append timestamp
        $password_hash = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
        $role = 'user';

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $name, $email, $phone, $password_hash, $role);
        if (!$stmt->execute()) {
            if (strpos($conn->error, 'Duplicate entry') !== false) {
                // retry with timestamped email
                $email = 'guest+' . preg_replace('/\D+/', '', $phone) . '+' . time() . '@example.local';
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss', $name, $email, $phone, $password_hash, $role);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to create guest user: ' . $conn->error);
                }
            } else {
                throw new Exception('Failed to create guest user: ' . $conn->error);
            }
        }
        $user_id = $stmt->insert_id;
        $stmt->close();
    }

    if (!$user_id) {
        throw new Exception('Could not resolve user_id');
    }

    // 4) Insert booking (NOTE: no service_name column here)
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, car_model, service_id, contact_phone)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param('isis', $user_id, $car_model, $service_id, $phone);

    if (!$stmt->execute()) {
        throw new Exception('Failed to insert booking: ' . $stmt->error);
    }

    $response = [
        'status' => 'success',
        'booking_id' => $stmt->insert_id
    ];
    $stmt->close();
} catch (Throwable $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    echo json_encode($response);
}
