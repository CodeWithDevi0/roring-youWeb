<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $mission_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    $updates = [];
    $types = "";
    $params = [];

    if (isset($_POST['completed'])) {
        $updates[] = "completed = ?";
        $types .= "i";
        $params[] = $_POST['completed'];
    }

    if (isset($_POST['name'])) {
        $updates[] = "name = ?";
        $types .= "s";
        $params[] = $_POST['name'];
    }

    if (isset($_POST['description'])) {
        $updates[] = "description = ?";
        $types .= "s";
        $params[] = $_POST['description'];
    }

    if (empty($updates)) {
        echo json_encode(['success' => false, 'error' => 'No updates provided']);
        exit;
    }

    $sql = "UPDATE missions SET " . implode(", ", $updates) . " WHERE id = ? AND user_id = ?";
    $types .= "ii";
    $params[] = $mission_id;
    $params[] = $user_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
