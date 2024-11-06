<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_pic'];

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload failed. Error code: ' . $file['error'], 'color' => 'red']);
        exit;
    }

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed types are JPEG, PNG, and GIF.', 'color' => 'red']);
        exit;
    }

    // Generate a unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $upload_dir = 'uploads/';
    $filepath = $upload_dir . $filename;

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Check if directory is writable
    if (!is_writable($upload_dir)) {
        echo json_encode(['success' => false, 'message' => 'Upload directory is not writable.', 'color' => 'red']);
        exit;
    }

    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Update the user's profile picture in the database
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $filepath, $user_id);

        if ($stmt->execute()) {
            $_SESSION['profile_pic'] = $filepath;
            echo json_encode(['success' => true, 'message' => 'Profile picture updated successfully!', 'filepath' => $filepath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed. Error: ' . $stmt->error, 'color' => 'red']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file', 'color' => 'red']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request', 'color' => 'red']);
}
