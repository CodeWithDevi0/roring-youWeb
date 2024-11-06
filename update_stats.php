<?php
session_start();
require_once 'db_connection.php';

if (isset($_SESSION['user_id']) && isset($_POST['level']) && isset($_POST['exp']) && isset($_POST['title'])) {
    $user_id = $_SESSION['user_id'];
    $level = intval($_POST['level']);
    $exp = intval($_POST['exp']);
    $title = $_POST['title'];

    $stmt = $conn->prepare("UPDATE users SET level = ?, exp = ?, title = ? WHERE id = ?");
    $stmt->bind_param("iisi", $level, $exp, $title, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user_level'] = $level;
        $_SESSION['user_exp'] = $exp;
        $_SESSION['user_title'] = $title;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}
?>
