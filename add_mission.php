<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    $stmt = $conn->prepare("INSERT INTO missions (user_id, name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $name, $description);

    if ($stmt->execute()) {
        $mission_id = $stmt->insert_id;
        echo json_encode(['success' => true, 'id' => $mission_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
