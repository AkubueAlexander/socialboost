<?php
session_start();

if (!isset($_GET['transaction_id'])) {
    die("No transaction ID.");
}
 include_once 'inc/database.php';
 include_once 'method.php';

$transaction_id = $_GET['transaction_id'];

// Step 1: Verify payment
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$transaction_id}/verify",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CAINFO => "C:/wamp64/bin/php/php8.2.18/extras/ssl/cacert.pem",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer FLWSECK_TEST-c239aaf7c99ab1fa37b71a622e528ca4-X",
        "Content-Type: application/json"
    ],
));
$response = curl_exec($curl);
curl_close($curl);

$res = json_decode($response);




if ($res && $res->status === 'success') {
    // Step 2: Retrieve the form data from session
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        $amount = $_SESSION['amount'];
        
 
        $sqlWallet = 'SELECT balance FROM wallet WHERE userId = :userId';        
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $userId]);
        $rowWallet = $stmtWallet->fetch();

        
        $balance = $rowWallet -> balance ;

        $balance += $amount;

        // Step 3: Update database        
        
        $stmt = $pdo->prepare("UPDATE wallet SET balance = :balance WHERE userId = :userId");
        $stmt->execute(['balance' => $balance, 'userId' => $userId]);

        header("Location: wallet.php?success= 1");
      
    } else {
        echo "Session expired or data not found.";
    }

    // Clear session
    unset($_SESSION['userId']);
    unset($_SESSION['amount']);
} else {
    echo "Payment not successful.";
}
