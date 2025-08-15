<?php


include_once '../inc/database.php';

if (isset($_POST['id']) ) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database delete failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
