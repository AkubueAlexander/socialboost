<?php
    session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    
        include_once 'inc/database.php';
        include_once 'method.php';

        $userId = $_SESSION['id'];

        $sqlWallet = 'SELECT * FROM earnerwallet WHERE userId = :userId LIMIT 1';        
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $userId]);
        $rowWallet = $stmtWallet->fetch();

        $sqlRecentTask = "SELECT task.status AS taskStatus, socialorder.*, service.*, task.* FROM task
        INNER JOIN socialorder ON task.orderId = socialorder.id
        INNER JOIN service ON socialorder.serviceId = service.id ORDER BY task.taskDate DESC LIMIT 5";

        $stmtRecentTask = $pdo->prepare($sqlRecentTask);
        $stmtRecentTask->execute();
        $rowsRecentTask = $stmtRecentTask->fetchAll();

        $sqlUser = 'SELECT * FROM user  WHERE id = :id LIMIT 1';        
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute(['id' => $_SESSION['id']]);
        $rowUser = $stmtUser->fetch();

        


   if (isset($_POST['submit'])) {
      $balance = $rowWallet -> balance;
      $bankCode = $rowWallet -> bankCode; // Example test bank code (Get from GET /v3/banks/US)
      $accountNumber = $rowWallet -> accountNumber; 
      $amount = $_POST['amount'];
       
      if ($amount <= $balance) {
        
          $secretKey = "FLWSECK_TEST-c239aaf7c99ab1fa37b71a622e528ca4-X"; // <-- Replace with your TEST secret key

        // ==== 2. Beneficiary Details ====
        
        $currency = "USD";
        $narration = "Test USD withdrawal from my platform"; 
        $reference = "txn_" . time(); // Unique transaction reference for tracking

        // ==== 3. API URL ====
        $url = "https://api.flutterwave.com/v3/transfers";

        // ==== 4. Prepare Request Data ====
        $data = [
            "account_bank"  => $bankCode,         // Bank code for the US bank (from Flutterwave bank list)
            "account_number"=> $accountNumber,    // US account number (sandbox)
            "amount"        => $amount,           // Transfer amount
            "currency"      => $currency,         // Always "USD" here
            "narration"     => $narration,         // Reason for transfer
            "reference"     => $reference,         // Unique reference for this transfer
            "callback_url"  => "http://localhost/boost/earning-verify"
        ];

        // ==== 5. Send cURL Request ====
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,                       // API endpoint
            CURLOPT_RETURNTRANSFER => true,            // Return the response as a string
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,                     // Request timeout
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",           // HTTP method
            CURLOPT_POSTFIELDS => json_encode($data),  // JSON request body
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $secretKey",    // API authentication
                "Content-Type: application/json"       // JSON format
            ],
            CURLOPT_SSL_VERIFYPEER => true,            // Enable SSL certificate verification
            CURLOPT_CAINFO => "C:/wamp64/bin/php/php8.2.18/extras/ssl/cacert.pem" // Path to SSL cert bundle
        ]);

        $response = curl_exec($ch);  // Execute request
        $err = curl_error($ch);      // Capture any cURL errors
        curl_close($ch);   

        // ==== 6. Handle Response ====
        if ($err) {
            // If cURL itself failed (connection, SSL, etc.)
            echo "cURL Error #: " . $err;
        } else {
            // Decode JSON response from Flutterwave
            $result = json_decode($response, true);

            // Check if API returned success
            if (isset($result['status']) && $result['status'] === "success") {
                echo " Transfer successful! Transaction ID: " . $result['data']['id'];
            } else {
                echo " Transfer failed: " . $result['message'];
            }
        }
        
        
        
        // Close cURL session
      }
       else {
        header('Location: earnings?error?insufficient Fund');
      }
        

        

      

        


    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boost Social - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#6366f1',
                    secondary: '#4f46e5',
                    accent: '#a78bfa',
                    dark: '#1e1b4b',
                }
            }
        }
    }
    </script>
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #6366f1 0%, #a78bfa 100%);
    }

    .chart-container {
        position: relative;
        height: 250px;
    }

    .task-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .task-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -10px rgba(99, 102, 241, 0.4);
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-5px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .hover-float:hover {
        animation: float 2s ease-in-out infinite;
    }

    .glow {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
    }

    .glow:hover {
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.5);
    }

    .text-gradient {
        background: linear-gradient(90deg, #6366f1, #a78bfa);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .sidebar {
        transition: all 0.3s ease;
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
            position: fixed;
            left: -100%;
            top: 0;
            z-index: 50;
            height: 100vh;
        }

        .sidebar.active {
            left: 0;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .overlay.active {
            display: block;
        }
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php
        if (isset($_GET['success'])) {
            echo "<script>
                Swal.fire({
                    title: 'Task Submitted Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gradient-to-b from-indigo-50 to-violet-50 shadow-md">
            <div class="p-6">
                <div style="float:right;">
                    <div class="flex items-center justify-between ml-auto">
                        <h1 class="text-xl font-bold text-dark">Boost Social</h1>
                        <button id="sidebarClose" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mt-10">
                        <div class="flex items-center space-x-4 p-3 bg-primary/10 rounded-lg ml-auto">
                            <div class="w-12 h-12 rounded-full gradient-bg flex items-center justify-center text-white">
                                <span
                                    class="text-xl font-semibold"><?php echo getInitials($rowUser -> fullName) ?></span>
                            </div>
                            <div>
                                <p class="font-medium text-dark"><?php echo $rowUser -> fullName ?></p>
                                <p class="text-sm text-gray-500">Premium Member</p>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="mt-10">
                    <div class="space-y-1">
                        <a href="earner" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="task"
                            class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-tasks"></i>
                            <span>Tasks</span>
                        </a>
                        <a href="earnings"
                            class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-wallet"></i>
                            <span>Earnings</span>
                        </a>

                        <a href="earner-settings"
                            class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <a href="help"
                            class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-question-circle"></i>
                            <span>Help</span>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <button
                    class="w-full flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary"
                    onclick="window.location.href='logout';">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="overlay"></div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-500 shadow-sm">
                <div class="px-6 py-4 flex items-center justify-between">
                    <button id="sidebarToggle" class="lg:hidden text-white hover:text-indigo-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-4 ml-auto">
                        <div class="relative">
                            <button class="text-gray-600 hover:text-primary">
                                <i class="fas fa-bell text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-red-500 text-white text-xs rounded-full">0</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-600 hover:text-primary">
                                <i class="fas fa-envelope text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-primary text-white text-xs rounded-full">0</span>
                            </button>
                        </div>
                        <button
                            class="w-10 h-10 rounded-full bg-gray-100 text-primary flex items-center justify-center">
                            <?php echo getInitials($rowUser -> fullName) ?>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
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
                            <h1 class="text-4xl font-bold text-secondary mt-1">
                                $<?php echo number_format($rowWallet -> balance, 2)  ?></h1>
                        </div>

                        <div class="flex items-center justify-center space-x-4">
                            <button type="submit" id="openFundModal"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md">
                                Withdraw
                            </button>
                        </div>





                    </div>
                </div>

                <!-- Modal Overlay -->
                <div id="fundModal"
                    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative animate-fadeIn">
                        <!-- Close button -->
                        <button id="closeFundModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>

                        <!-- Modal Title -->
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Withdraw</h2>

                        <!-- Form -->
                        <form action="" method="POST" class="space-y-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount
                                    ($)</label>
                                <input type="number" name="amount" id="amount" required
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

                <!-- Recent Tasks -->
                <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-dark">Recent Tasks</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($rowsRecentTask as $recentTask): ?>
                        <!-- Task Item -->
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-10 h-10 rounded-full <?php echo $recentTask -> iconBg  ?> flex items-center justify-center <?php echo $recentTask -> iconColour  ?>">
                                        <i class="<?php echo $recentTask -> icon ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-dark"><?php echo $recentTask -> title ?></h4>
                                        <p class="text-sm text-gray-500">
                                            <?php echo $recentTask -> platform;
                                            
                                            $reward = '';
                                            $bg = '';
                                            $colour = '';
                                                $earnerPrice = $recentTask -> earnerPrice;

                                                if ($earnerPrice > 1) {
                                                   $reward  = 'High Reward';
                                                } elseif ($earnerPrice > 0.5 && $earnerPrice < 1 ) {
                                                    $reward = 'Medium Reward';
                                                } else {
                                                    $reward  =  'Low Reward';
                                                }


                                                if ($recentTask -> taskStatus == 'In Progress') {
                                                    $bg = 'bg-yellow-100';
                                                    $colour = 'text-yellow-800';
                                                } 
                                                elseif($recentTask -> taskStatus == 'Pending Approval') {                                                    
                                                    $bg = 'bg-blue-100';
                                                    $colour = 'text-blue-800';

                                                }
                                                else {
                                                    $bg = 'bg-red-100';
                                                    $colour = 'text-red-800';
                                                }
                                                

                                                
                                            
                                            ?>
                                            •

                                            <?php echo $reward ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-dark">$<?php echo $recentTask -> earnerPrice ?></p>
                                    <div
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo $bg ?> <?php echo $colour ?>">
                                        <?php echo $recentTask -> taskStatus ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>


                    </div>

                </div>


            </main>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-500 text-white py-8 mt-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Boost Social</h3>
                    <p class="text-white/80">Earn money by completing social tasks and helping businesses grow.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/80 hover:text-white">Home</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Tasks</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Earnings</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Analytics</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/80 hover:text-white">Terms</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Privacy</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Cookies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/80 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-8 text-center text-white/80">
                <p>&copy; 2023 Boost Social. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Mobile sidebar toggle
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const overlay = document.getElementById('overlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    });

    sidebarClose.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
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