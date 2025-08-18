<?php
if (true) {
    $amount = $_GET['amount'];
    $userId = $_SESSION['id'];

    $sqlCheck = 'SELECT * FROM earnerwallet  WHERE id = :id LIMIT 1';        
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['id' => $id ]);
    $rowCheck = $stmtCheck->fetch();

    $balance = $rowCheck -> balance;

    $balance -= $amount;

    $stmt = $pdo->prepare("UPDATE earnerwallet SET balance = :balance WHERE userId = :userId");
    $stmt->execute(['balance' => $balance, 'userId' => $userId]);


    

    

}


?>