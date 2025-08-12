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
    if (isset($_SESSION['formData'])) {
        $data = $_SESSION['formData'];
        $serviceId = $data['serviceId'];
        $advId = $data['advId'];
        $amountSpent = $data['amountSpent'];
        $quantity = $data['quantity'];
        $orderCountTrack = $quantity;
        $socialUrl = $data['socialUrl'];
        $title = $data['title'];
       

        



        $sqlWallet = 'SELECT balance FROM wallet WHERE userId = :userId';        
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $advId]);
        $rowWallet = $stmtWallet->fetch();

        
        $balance = $rowWallet -> balance ;

        $balance += $amountSpent;

        // Step 3: Update database        
        
        $stmt = $pdo->prepare("UPDATE wallet SET balance = :balance WHERE userId = :userId");
        $stmt->execute(['balance' => $balance, 'userId' => $advId]);


        // Check if wallet balance is sufficient

        if ($balance < $amountSpent) {
            echo "<script>alert('Insufficient wallet balance. Please top up your wallet.');</script>";
          
        } else {
          $id = generateUniqueCode($pdo, $title);
          $sqlInsert = 'INSERT INTO socialorder (id, serviceId, advId,amountSpent, quantity, orderCountTrack,socialUrl) VALUES (:id,:serviceId,
          :advId,:amountSpent,:quantity,:orderCountTrack,:socialUrl)';
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute(['id' => $id,'serviceId' => $serviceId,'advId' => $advId,'amountSpent' => $amountSpent,
        'quantity' => $quantity,'orderCountTrack' => $orderCountTrack,'socialUrl' => $socialUrl]);

         
          // Update wallet balance
          $balance -= $amountSpent;
          
          // Update wallet balance in the database

          $sqlUpdate = 'UPDATE wallet SET balance =  :balance WHERE userId = :userId';
          $stmtUpdate = $pdo->prepare($sqlUpdate);
          $stmtUpdate->execute(['balance' => $balance, 'userId' => $advId]);

          echo "<script>alert('Order placed successfully.');</script>";
          
          header('location: order-history?status=success');
        
        }
       
    } else {
        echo "Session expired or data not found.";
    }

    // Clear session
    unset($_SESSION['formData']);
} else {
    echo "Payment not successful.";
}
