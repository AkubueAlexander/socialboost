<?php

require_once '../inc/database.php';


$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && is_numeric($data['id'])) { // Validate the ID
    $user_id = (int)$data['id'];

    try {
        // Prepare the SQL query
        $sql_delete = 'DELETE FROM user WHERE id = :user_id';
        $stmt_delete = $pdo->prepare($sql_delete);

        // Execute the query
        $stmt_delete->execute(['user_id' => $user_id]);

        // Check if rows were affected
        if ($stmt_delete->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No user found with the given ID']);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}




?>