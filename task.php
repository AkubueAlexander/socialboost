<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 

    include_once 'inc/database.php';
    include_once 'method.php';

    $sql = "SELECT socialorder.id AS orderId,
    socialorder.*, service.*
    FROM socialorder
    INNER JOIN service ON socialorder.serviceId = service.id
    WHERE socialorder.status != 'Completed'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();

   

    $sqlUser = 'SELECT * FROM user  WHERE id = :id LIMIT 1';        
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['id' => $_SESSION['id']]);
    $rowUser = $stmtUser->fetch();

    $sqlRecentTask = "SELECT task.status AS taskStatus, socialorder.*, service.*, task.* FROM task
    INNER JOIN socialorder ON task.orderId = socialorder.id
    INNER JOIN service ON socialorder.serviceId = service.id ORDER BY task.taskDate DESC LIMIT 5";

    $stmtRecentTask = $pdo->prepare($sqlRecentTask);
    $stmtRecentTask->execute();
    $rowsRecentTask = $stmtRecentTask->fetchAll();


    if (isset($_POST['submit'])) {

    $title = $_POST['title'];
    $id = generateUniqueCodeTask($pdo, $title);
    $earnerId = $_SESSION['id'];
    $orderId = $_POST['orderId'];
    $status = 'In Progress';

    $sqlTrack = 'SELECT orderCountTrack FROM socialorder WHERE id = :id';
    $stmtTrack = $pdo->prepare($sqlTrack);
    $stmtTrack->execute(['id' => $orderId]);
    $rowTrack = $stmtTrack->fetch();

    $orderCountTrack = $rowTrack -> orderCountTrack;
    $orderCountTrack -= 1; 
    

    $sqlTask = 'INSERT INTO task (id, earnerId, orderId) VALUES (:id, :earnerId, :orderId)';
    $stmtTask = $pdo->prepare($sqlTask);
    $stmtTask->execute(['id' => $id,'earnerId' => $earnerId, 'orderId' => $orderId]);

   


    
    $sqlUpdate = 'UPDATE socialorder SET status =  :status, orderCountTrack = :orderCountTrack WHERE id = :id';
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute(['status' => $status, 'id' => $orderId, 'orderCountTrack' => $orderCountTrack]);

    header('location: perform-task?orderId='.$orderId.'&title='.$title.'&taskId='.$id);
    
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
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
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
                background-color: rgba(0,0,0,0.5);
                z-index: 40;
            }
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gradient-to-b from-indigo-50 to-violet-50 shadow-md">
            <div class="p-6"><div style="float:right;">
                <div class="flex items-center justify-between ml-auto">
                    <h1 class="text-xl font-bold text-dark">Boost Social</h1>
                    <button id="sidebarClose" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-10">
                    <div class="flex items-center space-x-4 p-3 bg-primary/10 rounded-lg ml-auto">
                        <div class="w-12 h-12 rounded-full gradient-bg flex items-center justify-center text-white">
                            <span class="text-xl font-semibold"><?php echo getInitials($rowUser -> fullName) ?></span>
                        </div>
                        <div>
                            <p class="font-medium text-dark"><?php echo $rowUser -> fullName ?></p>
                            <p class="text-sm text-gray-500">Premium Member</p>
                        </div>
                    </div>
                </div></div>
                <nav class="mt-10">
                    <div class="space-y-1">
                        <a href="earner" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="task" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-tasks"></i>
                            <span>Tasks</span>
                        </a>
                        <a href="earnings" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-wallet"></i>
                            <span>Earnings</span>
                        </a>
                        <a href="analytics" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                        <a href="earner-settings" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <a href="help" class="flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
                            <i class="fas fa-question-circle"></i>
                            <span>Help</span>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <button class="w-full flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary" onclick="window.location.href='logout';">
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
                                <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-red-500 text-white text-xs rounded-full">0</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-600 hover:text-primary">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-primary text-white text-xs rounded-full">0</span>
                            </button>
                        </div>
                        <button class="w-10 h-10 rounded-full bg-gray-100 text-primary flex items-center justify-center">
                           <?php echo getInitials($rowUser -> fullName) ?>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                

                <!-- Available Tasks -->
                <div class="mt-8">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                        <!-- Task Card -->
                         <?php foreach ($rows as $row):
                        
                         $orderId = $row-> orderId;

                         $sqlOrder = 'SELECT orderId FROM task  WHERE orderId = :orderId LIMIT 1';        
                        $stmtOrder = $pdo->prepare($sqlOrder);
                        $stmtOrder->execute(['orderId' => $orderId]);
                        $rowOrder = $stmtOrder->fetch();

                         if (!$rowOrder): ?>             
                          
                        <div class="task-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full <?php echo $row -> iconBg ?> flex items-center justify-center <?php echo $row -> iconColour ?>">
                                        <i class="<?php echo $row -> icon ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-dark"><?php echo $row -> title ?></h4>
                                        <div class="flex space-x-2 mt-1">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                <?php 

                                                $reward = '';
                                                $earnerPrice = $row -> earnerPrice;

                                                if ($earnerPrice > 1) {
                                                   $reward  = 'High Reward';
                                                } elseif ($earnerPrice > 0.5 && $earnerPrice < 1 ) {
                                                    $reward = 'Medium Reward';
                                                } else {
                                                    $reward  =  'Low Reward';
                                                }

                                                echo $reward;
                                                ?>
                                                
                                            </span>
                                            <span class="px-2 py-1 <?php echo $row -> iconBg ?> <?php echo $row -> iconColour ?> text-xs rounded-full"><?php echo $row -> platform ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-gray-600 text-sm"><?php echo $row -> earnerDes ?>.</p>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="text-primary font-bold">$<?php echo $row -> earnerPrice ?></div>
                                    <form  method="post">
                                        <input type="hidden" name="orderId" value="<?php echo $row -> orderId ?>">    
                                        <input type="hidden" name="title" value="<?php echo $row -> title ?>">                                    
                                            <button name="submit" class="px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary hover:text-white transition-colors duration-200">
                                            Start Task
                                        </button>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach?>
                        
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
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-indigo-200 transition-colors"><i class="fab fa-linkedin-in"></i></a>
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

        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Completed Tasks',
                        data: [20, 35, 28, 45, 33, 38, 25],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Assigned Tasks',
                        data: [25, 40, 30, 50, 40, 45, 30],
                        borderColor: '#a78bfa',
                        backgroundColor: 'rgba(167, 139, 250, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
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
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Task Distribution Chart
        const taskDistCtx = document.getElementById('taskDistributionChart').getContext('2d');
        const taskDistChart = new Chart(taskDistCtx, {
            type: 'doughnut',
            data: {
                labels: ['Social Media', 'Reviews', 'Other'],
                datasets: [{
                    data: [62, 23, 15],
                    backgroundColor: [
                        '#6366f1',
                        '#a78bfa',
                        '#f59e0b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>