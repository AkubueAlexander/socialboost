<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    $rows = '';
    include_once 'inc/database.php';

     $sqlUser = 'SELECT fullName FROM user  WHERE id = :id LIMIT 1';        
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['id' => $_SESSION['id']]);
    $rowUser = $stmtUser->fetch();

    if (isset($_GET['platform'])) {

        $platform = htmlspecialchars($_GET['platform']);
        $sql = 'SELECT * FROM service WHERE platform = :platform';        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['platform' => $platform]);
        $rows = $stmt->fetchAll();
    } else {
        $sql = 'SELECT * FROM service';        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
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

    .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
                            <span class="ml-2 text-sm font-medium"><?php echo $rowUser -> fullName ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Service Card -->
                 <?php foreach ($rows as $row):;?>
                  
                <div class="service-card bg-white rounded-xl shadow-md overflow-hidden transition duration-300 cursor-pointer" onclick="window.location.href='check-out?service=<?php echo $row -> title; ?>'" data-category="<?php echo $row -> category; ?>">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="<?php echo $row -> highLightBg; ?> <?php echo $row -> highLightColour; ?> px-3 py-1 rounded-full text-xs font-semibold"><?php echo $row -> highLight; ?></div>
                            <div class="text-yellow-500">
                                <i class="fas fa-star"></i>
                                <span class="text-gray-700 ml-1"><?php echo $row -> rating; ?> (<?php echo $row -> ratingCount; ?>)</span>
                            </div>
                        </div>
                        <div class="flex items-center mb-4">
                            <div class="<?php echo $row -> imgBg; ?> p-3 rounded-full mr-4 overflow-hidden">
                                <img src="<?php echo $row -> imgUrl; ?>" 
                                     alt="Google Play" 
                                     class="w-8 h-8 object-contain">
                            </div>
                            <h3 class="text-xl font-bold text-dark"><?php echo $row -> title; ?></h3>
                        </div>
                        <p class="text-gray-600 mb-6"><?php echo $row -> advDes; ?>.</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-dark">$<?php echo $row -> advPrice; ?></span>
                                <span class="text-gray-500 text-sm">/ <?php echo $row -> per; ?> reviews</span>
                            </div>
                            <button class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="event.stopPropagation();window.location.href='check-out?service=<?php echo $row -> title; ?>'">
                                <i class="fas fa-shopping-cart mr-2"></i> Buy Now
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach?>
                
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

    

    
    </script>
</body>

</html>