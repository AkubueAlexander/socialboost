<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    
    include_once 'inc/database.php';
    include_once 'method.php';

        $title = htmlspecialchars($_GET['service']);
        $sql = 'SELECT * FROM service WHERE title = :title';        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title' => $title]);
        $row = $stmt->fetch();

        $per = $row->per;

  
  if (isset($_POST['submit'])) {
    $serviceId = $_POST['serviceId'];
    $advId = $_POST['advId'];
    $amountSpent = $_POST['amountSpent'];
    $quantity = ($_POST['quantity'] * $per);
    $orderCountTrack = $quantity;
    $socialUrl = $_POST['socialUrl'];
    $paymentMethod = $_POST['paymentMethod'];
    
   

    if ($paymentMethod == 'wallet') {

        $sqlWallet = 'SELECT balance FROM wallet WHERE userId = :userId';        
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $_POST['advId']]);
        $rowWallet = $stmtWallet->fetch();

        $balance = $rowWallet -> balance ;

        // Check if wallet balance is sufficient
        if ($balance < $amountSpent) {
            echo "<script>alert('Insufficient wallet balance. Please top up your wallet.');</script>";
          
        }
        
       else {
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

          echo "<script>alert('Order placed successfuly.');</script>";
          
          header('location: order-history?status=success');
        
      }
        
    }
    else {
      
        $_SESSION['formData'] = [
            'serviceId' => $_POST['serviceId'],
            'advId' => $_POST['advId'],
            'amountSpent' => $_POST['amountSpent'],
            'quantity' => $quantity,
            'orderCountTrack' => $quantity,
            'socialUrl' => $_POST['socialUrl'],
            'title' => $title,
            'per' => $per,
            
        ];

        $sqlCheck = 'SELECT * FROM user  WHERE id = :id LIMIT 1';        
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $advId]);
        $rowCheck = $stmtCheck->fetch();

        $email = $rowCheck -> email; // Replace with user email
        $name = $rowCheck -> firstName. ' '. $rowCheck -> lastName;
        
        $txref = 'TX-' . time(); // Unique transaction reference
        $callback_url = "http://localhost/boost/flutter-verify.php";
        $secret_key = 'FLWSECK_TEST-c239aaf7c99ab1fa37b71a622e528ca4-X'; // Replace with your Flutterwave secret key

        $amountSpent = $_POST['amountSpent']; 

        $data = [
            'tx_ref' => $txref,
            'amount' => $amountSpent,
            'currency' => 'USD',
            'payment_options' => 'card,banktransfer',
            'redirect_url' => $callback_url,
            'customer' => [
                'email' => $email,
                'name' => $name,
            ],
            'customizations' => [
                'title' => 'Social Media Order',
                'description' => 'Payment for social media order',
            ]
        ];

      $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secret_key",
            "Content-Type: application/json"
        ],
        //  Add this line - Update the path as per your PHP setup
        CURLOPT_CAINFO => "C:/wamp64/bin/php/php8.2.18/extras/ssl/cacert.pem"
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response);
            if ($result->status === 'success') {
                header('Location: ' . $result->data->link); // Redirect to Flutterwave checkout page
                exit();
            } else {
                echo "Payment failed to initiate.";
          }
        }


    }


  }

       

    


    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostbrands - Order Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#6366f1',
                    secondary: '#8b5cf6',
                    dark: '#1e293b',
                    light: '#f8fafc',
                }
            }
        }
    }
    </script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');



    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
    }









    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            position: fixed;
            z-index: 50;
            height: 100vh;
            transition: transform 0.3s ease;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }

        .overlay.active {
            display: block;
        }
    }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-gradient-to-b from-purple-600 to-indigo-700 text-white w-64 flex-shrink-0">
            <div class="p-4 flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg">
                    <i class="fas fa-bolt text-purple-600 text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold">Boostbrands</h1>
            </div>
            <nav class="mt-8">
                <div class="px-4 space-y-1">
                    <a href="advertiser"
                        class="flex items-center px-4 py-3 bg-white bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="new-order"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        New Order
                    </a>
                    <a href="order-history"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-history mr-3"></i>
                        Order History
                    </a>
                    <a href="wallet"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-wallet mr-3"></i>
                        Wallet
                    </a>
                    <a href="advertiser-settings"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-cog mr-3"></i>
                        Settings
                    </a>
                </div>
            </nav>
            <div class="absolute bottom-0 w-full p-4">
                <div class="bg-white bg-opacity-10 p-3 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">John Doe</p>
                            <p class="text-xs opacity-70">Premium User</p>
                        </div>
                    </div>
                    <button class="mt-3 w-full py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-sm ">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>


                </div>
            </div>
        </div>

        <!-- Mobile overlay -->
        <div class="overlay"></div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="md:hidden mr-4 text-gray-500">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">New Order</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell text-xl"></i>
                            </button>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </div>
                        <div class="hidden md:flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-user text-purple-600"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium">John Doe</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">

                <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-xl rounded-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-center">Checkout</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Summary -->
                        <div class="bg-gray-50 p-5 rounded-xl border">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>

                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span>Service:</span>
                                    <span class="font-medium"><?php echo $row -> title ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Price per <?php echo $row -> per ?>:</span>
                                    <span id="pricePerUnit">$<?php echo $row -> advPrice ?></span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span>Quantity (×<?php echo $row -> per ?> ):</span>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="decreaseBtn"
                                            class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">-</button>
                                        <span id="quantity" class="px-3 font-semibold">1</span>
                                        <button type="button" id="increaseBtn"
                                            class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">+</button>
                                    </div>
                                </div>

                                <div class="border-t pt-3 flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span id="totalPrice">$<?php echo $row -> advPrice ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- User Input & Payment -->
                        <div class="bg-gray-50 p-5 rounded-xl border">
                            <h3 class="text-lg font-semibold mb-4">Your Details</h3>

                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="quantity" id="inputQuantity" value="1">
                                <input type="hidden" name="amountSpent" id="inputTotalPrice"
                                    value="<?php echo $row -> advPrice ?>">
                                <input type="hidden" name="serviceId" value="<?php echo $row -> id ?>">
                                <input type="hidden" name="advId" value="<?php echo $_SESSION['id'] ?>">

                                <div>
                                    <label class="block text-sm mb-1">Page URL</label>
                                    <input type="text" name="socialUrl" placeholder="social media link"
                                        class="w-full px-4 py-2 border rounded-lg" required>
                                </div>



                                <div>
                                    <label class="block text-sm mb-2">Payment Method</label>
                                    <select name="paymentMethod" class="w-full px-4 py-2 border rounded-lg" required>
                                        <option value="">Select a payment option</option>
                                        <option value="flutterwave">Flutterwave</option>
                                        <option value="wallet">Wallet Balance</option>
                                    </select>
                                </div>

                                <button type="submit" name="submit" id="payButton"
                                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                                    Confirm & Pay $<?php echo $row -> advPrice ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-sm text-gray-500">
                        Having issues? <a href="/support" class="text-blue-600 underline">Contact support</a>
                    </div>
                </div>





            </main>
        </div>
    </div>


    <script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    //  JavaScript to update quantity and price

    document.addEventListener("DOMContentLoaded", function() {
        const pricePerUnit = <?php echo $row -> advPrice ?>;
        let quantity = 1;

        const quantityEl = document.getElementById('quantity');
        const totalPriceEl = document.getElementById('totalPrice');
        const payButton = document.getElementById('payButton');
        const inputQuantity = document.getElementById('inputQuantity');
        const inputTotalPrice = document.getElementById('inputTotalPrice');

        document.getElementById('increaseBtn').addEventListener('click', function() {
            quantity++;
            updateUI();
        });

        document.getElementById('decreaseBtn').addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                updateUI();
            }
        });

        function updateUI() {
            const total = (pricePerUnit * quantity).toFixed(2);
            quantityEl.innerText = quantity;
            totalPriceEl.innerText = `$${total}`;
            payButton.innerText = `Confirm & Pay $${total}`;
            inputQuantity.value = quantity;
            inputTotalPrice.value = total;
        }
    });
    </script>
</body>

</html>