<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    include_once 'inc/database.php';

    $sqlUser = 'SELECT * FROM user  WHERE id = :id LIMIT 1';        
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['id' => $_SESSION['id']]);
    $rowUser = $stmtUser->fetch();


    if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $id = trim($_POST['id']);
    $phone = trim($_POST['phone']);
    $fullName = trim($_POST['fullName']);

    $stmt = $pdo->prepare("UPDATE user SET fullName = :fullName, email = :email, phone = :phone WHERE id = :id");
    $stmt->execute(['id' => $id, 'fullName' => $fullName, 'email' => $email, 'phone' => $phone]);
    header("Location: advertiser-settings?success=1");

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
                    title: 'Profile Updated Successfully',
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
                        <h2 class="text-xl font-semibold text-gray-800">Settings</h2>
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
            <main class="flex justify-center items-center p-4 md:p-6 bg-gray-50 min-h-screen">

                <div class="bg-white shadow-lg rounded-2xl w-full max-w-2xl p-6 relative">

                    <!-- Referral Link Section -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Your Referral Link</label>
                        <div class="flex items-center space-x-2">
                            <input id="referralLink" type="text" readonly value="http://localhost/boost/register-advertiser?ref=<?php echo $rowUser -> userName ?>"
                                class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-700 text-sm" />
                            <button onclick="copyReferralLink()"
                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md">
                                📋
                            </button>
                        </div>
                    </div>

                    <!-- Settings Form -->
                    <form class="space-y-4" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm mb-1">Full Name</label>
                                <input type="text" placeholder="full name" name="fullName"
                                    value="<?php echo $rowUser -> fullName ?>"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Email</label>
                                <input type="email" placeholder="Enter email" name="email"
                                    value="<?php echo $rowUser -> email ?>"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm mb-1">Phone</label>
                                <input type="tel" placeholder="Enter phone number" name="phone"
                                    value="<?php echo $rowUser -> phone ?>"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm mb-1">Username</label>
                                <input type="text" value="<?php echo $rowUser -> userName ?>" readonly
                                    class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-600" />
                                    <input type="hidden" name="id" value="<?php echo $rowUser -> id ?>">
                            </div>
                        </div>
                        <button type="submit" name="submit"
                            class="mt-4 w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">Save
                            Changes</button>
                    </form>
                </div>

                <!-- Toast Message -->
                <div id="toast"
                    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-2 rounded shadow-lg text-sm hidden">
                    Copied!
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

    function copyReferralLink() {
        const link = document.getElementById('referralLink');
        link.select();
        link.setSelectionRange(0, 99999); // for mobile
        document.execCommand("copy");

        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000); // 3 seconds
    }
    </script>
</body>

</html>