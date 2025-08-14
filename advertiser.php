<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    include_once 'inc/database.php';
    $id = $_SESSION['id'];

    $sql = "SELECT COUNT(*) AS total_count FROM socialorder WHERE advId = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $totalCount = $stmt->fetchColumn();

    $pending = 'pending';
    $sqlPending = "SELECT COUNT(*) AS total_count FROM socialorder WHERE advId = :id AND status = :pending";
    $stmtPending = $pdo->prepare($sqlPending);
    $stmtPending->execute(['id' => $id,'pending' => $pending]);
    $pendingCount = $stmtPending->fetchColumn();

    $inProgress = 'In Progress';
    $sqlInProgress = "SELECT COUNT(*) AS total_count FROM socialorder WHERE advId = :id AND status = :inProgress";
    $stmtInProgress = $pdo->prepare($sqlInProgress);
    $stmtInProgress->execute(['id' => $id,'inProgress' => $inProgress]);
    $inProgressCount = $stmtInProgress->fetchColumn();

    $completed = 'completed';
    $sqlCompleted = "SELECT COUNT(*) AS total_count FROM socialorder WHERE status = :completed AND advId = :id";
    $stmtCompleted = $pdo->prepare($sqlCompleted);
    $stmtCompleted->execute(['id' => $id, 'completed' => $completed]);    
    $completedCount = $stmtCompleted->fetchColumn();


    $sqlRecent = 'SELECT socialorder.id AS orderId, socialorder.*, service.* FROM socialorder
     INNER JOIN service ON socialorder.serviceId = service.id WHERE socialorder.advId = :id LIMIT 4';        
    $stmtRecent = $pdo->prepare($sqlRecent);
    $stmtRecent->execute(['id' => $id]);
    $rows = $stmtRecent->fetchAll();

     $sqlUser = 'SELECT fullName FROM user  WHERE id = :id LIMIT 1';        
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['id' => $_SESSION['id']]);
    $rowUser = $stmtUser->fetch();

    

    
    

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

    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background-color: #e0e0e0;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .platform-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .chart-container {
        height: 300px;
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
                    <a href="advertiser" class="flex items-center px-4 py-3 bg-white bg-opacity-10 rounded-lg text-white">
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
                        <h2 class="text-xl font-semibold text-gray-800">Order Dashboard</h2>
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
                            <span class="ml-2 text-sm font-medium"><?php echo $rowUser -> fullName ?>e</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">
                <!-- Stats cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-100">Total Orders</p>
                                <h3 class="text-2xl font-bold"><?php echo $totalCount ?></h3>
                            </div>
                            <div class="p-3 rounded-lg bg-white bg-opacity-20">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-purple-100 font-medium"><i class="fas fa-arrow-up mr-1"></i> 12%
                                from last month</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-100">Completed</p>
                                <h3 class="text-2xl font-bold"><?php echo $completedCount ?></h3>
                            </div>
                            <div class="p-3 rounded-lg bg-white bg-opacity-20">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-green-100 font-medium"><i class="fas fa-arrow-up mr-1"></i> 8%
                                from last month</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-sm p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-yellow-100">In Progress</p>
                                <h3 class="text-2xl font-bold"><?php echo $inProgressCount ?></h3>
                            </div>
                            <div class="p-3 rounded-lg bg-white bg-opacity-20">
                                <i class="fas fa-spinner text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-yellow-100 font-medium"><i class="fas fa-arrow-down mr-1"></i> 2%
                                from last month</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100">Pending</p>
                                <h3 class="text-2xl font-bold"><?php echo $pendingCount ?></h3>
                            </div>
                            <div class="p-3 rounded-lg bg-white bg-opacity-20">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-xs text-blue-100 font-medium"><i class="fas fa-arrow-up mr-1"></i> 1% from
                                last month</span>
                        </div>
                    </div>
                </div>

                <!-- Charts and recent orders -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Order distribution chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Order Distribution</h3>
                            <select
                                class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option selected>Last 90 days</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="orderChart"></canvas>
                        </div>
                    </div>

                    <!-- Platform distribution -->
                    <div class="bg-white rounded-xl shadow-sm p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Platform Distribution</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="platform-icon bg-blue-100 text-blue-600">
                                            <i class="fab fa-google"></i>
                                        </div>
                                        <span class="ml-2 text-sm font-medium">Google</span>
                                    </div>
                                    <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='new-order?platform=Google'">
                                      <i class="fas fa-shopping-cart mr-2"></i> Buy
                                    </button>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-blue-500" style="width: 42%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="platform-icon bg-purple-100 text-purple-600">
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                        <span class="ml-2 text-sm font-medium">Instagram</span>
                                    </div>
                                    <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='new-order?platform=Instagram'">
                                      <i class="fas fa-shopping-cart mr-2"></i> Buy
                                    </button>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-purple-500" style="width: 28%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="platform-icon bg-red-100 text-red-600">
                                            <i class="fab fa-youtube"></i>
                                        </div>
                                        <span class="ml-2 text-sm font-medium">YouTube</span>
                                    </div>
                                    <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='new-order?platform=YouTube'">
                                      <i class="fas fa-shopping-cart mr-2"></i> Buy
                                    </button>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-red-500" style="width: 15%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="platform-icon bg-blue-100 text-blue-600">
                                            <i class="fab fa-facebook"></i>
                                        </div>
                                        <span class="ml-2 text-sm font-medium">Facebook</span>
                                    </div>
                                    <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='new-order?platform=Facebook'">
                                      <i class="fas fa-shopping-cart mr-2"></i> Buy
                                    </button>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-blue-400" style="width: 10%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="platform-icon bg-pink-100 text-pink-600">
                                            <i class="fab fa-tiktok"></i>
                                        </div>
                                        <span class="ml-2 text-sm font-medium">TikTok</span>
                                    </div>
                                    <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='new-order?platform=TikTok'">
                                      <i class="fas fa-shopping-cart mr-2"></i> Buy
                                    </button>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-pink-500" style="width: 5%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent orders -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                            <button class="text-sm text-purple-600 hover:text-purple-800 font-medium" onclick="event.stopPropagation();window.location.href='order-history'">
                                View All <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php foreach($rows as $row): ?>
                        <!-- Order  -->
                        <div class="order-card p-5 hover:bg-gray-50 transition duration-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="platform-icon <?php echo $row -> iconBg ?> <?php echo $row -> iconColour ?>">
                                        <i class="<?php echo $row -> icon ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800"><?php echo $row -> title ?></h4>
                                        <p class="text-sm text-gray-500">Order #<?php echo $row -> orderId ?></p>
                                        <p class="text-xs text-gray-400 mt-1">Placed on <?php 

                                        $timestamp = $row -> orderDate;
                                            $date = new DateTime($timestamp);
                                            $formatted = $date->format('d, M Y');

                                            echo $formatted;

                                        
                                        
                                        ?></p>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0 flex flex-col md:items-end">

                                    <?php
                                    $status = $row -> status;
                                    $bg = '';
                                    $colour = '';
                                    $count = ($row -> quantity) - ($row -> orderCountTrack);
                                    $percentage = ($count / $row -> quantity) * 100;

                                    if ($status == 'Completed') {
                                        $bg = 'bg-green-100';
                                        $colour = 'text-green-800';
                                        
                                    }
                                    elseif ($status == 'In Progress') {
                                        $bg = 'bg-yellow-100';
                                        $colour = 'text-yellow-800';
                                    } else {
                                        $bg = 'bg-red-100';
                                        $colour = 'text-red-800';
                                    }   

                                    
                                    ?>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium <?php echo $bg ?> <?php echo $colour ?>"><?php echo $status ?></span>
                                    <p class="text-sm text-gray-500 mt-2"><?php echo $count ?> out of <?php echo $row -> quantity ?> delivered</p>
                                    <div class="flex items-center mt-1">
                                        
                                        <span class="ml-2 text-xs text-gray-500"><?php echo number_format($percentage, 0) ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Order chart
    const ctx = document.getElementById('orderChart').getContext('2d');
    const orderChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                    label: 'Completed',
                    data: [12, 19, 15, 20, 18, 22],
                    backgroundColor: '#10B981',
                    borderRadius: 6
                },
                {
                    label: 'In Progress',
                    data: [3, 2, 4, 5, 3, 4],
                    backgroundColor: '#F59E0B',
                    borderRadius: 6
                },
                {
                    label: 'Pending',
                    data: [1, 2, 1, 0, 2, 2],
                    backgroundColor: '#3B82F6',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5],
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    

    
    </script>
</body>

</html>