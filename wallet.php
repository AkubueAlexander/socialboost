<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    $rows = '';
    include_once 'inc/database.php';

        $userId = $_SESSION['id'];

        $sqlWallet = 'SELECT balance FROM wallet WHERE userId = :userId';        
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $userId]);
        $rowWallet = $stmtWallet->fetch();

        $sqlUser = 'SELECT fullName FROM user  WHERE id = :id LIMIT 1';        
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute(['id' => $_SESSION['id']]);
        $rowUser = $stmtUser->fetch();

    if (isset($_POST['submit'])) {

        $amount = $_POST['amount'];
        

        $sqlCheck = 'SELECT * FROM user  WHERE id = :id LIMIT 1';        
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $userId ]);
        $rowCheck = $stmtCheck->fetch();

        $email = $rowCheck -> email; 
        $name = $rowCheck -> firstName. ' '. $rowCheck -> lastName;

        $_SESSION['userId'] = $userId;
        $_SESSION['amount'] = $amount;

        $txref = 'TX-' . time(); // Unique transaction reference
        $callback_url = "http://localhost/boost/wallet-verify.php";
        $secret_key = 'FLWSECK_TEST-c239aaf7c99ab1fa37b71a622e528ca4-X'; 

        

        $data = [
            'tx_ref' => $txref,
            'amount' => $amount,
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

    


    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostbrands - Order Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
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

    <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<script>
                Swal.fire({
                    title: 'Wallet Funded Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar relative bg-gradient-to-b from-purple-600 to-indigo-700 text-white w-64 flex-shrink-0 flex flex-col">
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
                            <p class="text-sm font-medium"><?php echo $rowUser -> fullName ?></p>
                            <p class="text-xs opacity-70">Premium User</p>
                        </div>
                    </div>
                    <button class="mt-3 w-full py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-sm " onclick="window.location.href='logout';">
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
                        <h2 class="text-xl font-semibold text-gray-800">Wallet</h2>
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
                            <span class="ml-2 text-sm font-medium"><?php echo $rowUser -> fullName ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">

                <div class=" bg-gray-100 flex items-center justify-center p-4">
                    <div class="bg-white shadow-lg rounded-2xl p-6 max-w-md w-full">
                        <!-- Header -->
                        <div class=" mb-4">
                            <h2 class="text-2xl font-semibold text-gray-800 text-center">My Wallet</h2>
                            <span class="text-sm text-gray-500"></span>
                        </div>

                        <!-- Balance -->
                        <div class="mb-6 text-center">
                            <p class="text-sm text-gray-500">Available Balance</p>
                            <h1 class="text-4xl font-bold text-secondary mt-1">$<?php echo number_format($rowWallet -> balance, 2) ?></h1>
                        </div>
                        <div class="flex items-center justify-center space-x-4">
                            <button id="openFundModal"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md">
                                Fund
                            </button>
                        </div>

                        <!-- Modal Overlay -->
                        <div id="fundModal"
                            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative animate-fadeIn">
                                <!-- Close button -->
                                <button id="closeFundModal"
                                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-xl"></i>
                                </button>

                                <!-- Modal Title -->
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Fund Wallet</h2>

                                <!-- Form -->
                                <form action="" method="POST" class="space-y-4">
                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount
                                            ($)</label>
                                        <input type="number" name="amount" id="amount" required min="100"
                                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-3">
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button" id="cancelFundModal"
                                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium">
                                            Cancel
                                        </button>
                                        <button type="submit" name="submit"
                                            class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
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

    const fundModal = document.getElementById('fundModal');
    const openFundModal = document.getElementById('openFundModal');
    const closeFundModal = document.getElementById('closeFundModal');
    const cancelFundModal = document.getElementById('cancelFundModal');

    openFundModal.addEventListener('click', () => {
        fundModal.classList.remove('hidden');
        fundModal.classList.add('flex');
    });

    [closeFundModal, cancelFundModal].forEach(btn => {
        btn.addEventListener('click', () => {
            fundModal.classList.add('hidden');
            fundModal.classList.remove('flex');
        });
    });

    // Close modal when clicking outside the card
    fundModal.addEventListener('click', (e) => {
        if (e.target === fundModal) {
            fundModal.classList.add('hidden');
            fundModal.classList.remove('flex');
        }
    });
    </script>
</body>

</html>