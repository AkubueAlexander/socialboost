<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    include_once '../inc/database.php';

     $sqlCategory = 'SELECT * FROM category'; 
    $stmtCategory = $pdo->prepare($sqlCategory);
    $stmtCategory->execute();
    $rowsCategory = $stmtCategory->fetchAll();

 


    if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);    
    $advDes = $_POST['advDes'];      
    $earnerDes = trim($_POST['earnerDes']);       
    $advPrice = $_POST['advPrice'];  
    $earnerPrice = trim($_POST['earnerPrice']);
    $per = trim($_POST['per']);
    $platform = trim($_POST['platform']);
    $platformBg = trim($_POST['platformBg']);
    $platformColour = trim($_POST['platformColour']);
    $imgUrl = trim($_POST['imgUrl']);
    $imgBg = trim($_POST['imgBg']);
    $icon = trim($_POST['icon']);
    $iconBg = trim($_POST['iconBg']);
    $iconColour = trim($_POST['iconColour']);
    $rating = trim($_POST['rating']);
    $ratingCount = trim($_POST['ratingCount']);
    $highLight = trim($_POST['highLight']);
    $highLightColour = trim($_POST['highLightColour']);
    $highLightBg = trim($_POST['highLightBg']);
    $categoryId = trim($_POST['categoryId']);


    $sql = 'INSERT INTO service (title, advDes, earnerDes,advPrice, earnerPrice,per, platform, platformBg, platformColour,
     imgUrl, imgBg, icon, iconBg, iconColour, rating, ratingCount, highLight, highLightColour, highLightBg, categoryId) 
    VALUES (:title,:advDes,:earnerDes,:advPrice,:earnerPrice,:per, :platform, :platformBg, :platformColour,
     :imgUrl, :imgBg, :icon, :iconBg, :iconColour, :rating, :ratingCount, :highLight, :highLightColour, :highLightBg, :categoryId)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title' => $title,'advDes' => $advDes,'earnerDes' => $earnerDes,'advPrice' => $advPrice,
        'earnerPrice' => $earnerPrice,'per' => $per,'platform' => $platform,'platformBg' => $platformBg,
        'platformColour' => $platformColour,'imgUrl' => $imgUrl,'imgBg' => $imgBg,'icon' => $icon,
        'iconBg' => $iconBg,'iconColour' => $iconColour,'rating' => $rating,'ratingCount' => $ratingCount,
        'highLight' => $highLight,'highLightColour' => $highLightColour,'highLightBg' => $highLightBg,
        'categoryId' => $categoryId]);
        

    header("Location: service?created=1");

    }
    


  

    

   

    
    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostbrands - Service Dashboard</title>
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
        <div
            class="sidebar relative bg-gradient-to-b from-purple-600 to-indigo-700 text-white w-64 flex-shrink-0 flex flex-col">

            <div class="p-4 flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg">
                    <i class="fas fa-bolt text-purple-600 text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold">Boostbrands</h1>
            </div>
            <nav class="mt-8">
                <div class="px-4 space-y-1">
                    <a href="index" class="flex items-center px-4 py-3 bg-white bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="order"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-history mr-3"></i>
                        Order
                    </a>
                    <a href="service"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-bookmark mr-3"></i>
                        Service
                    </a>
                    <a href="task"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-tasks mr-3"></i>
                        Task
                    </a>
                    <a href="user"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a>

                    <a href="withdrawal"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-wallet mr-3"></i>
                        Withdrawal
                    </a>
                    <a href="category"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-cubes mr-3"></i>
                        Category
                    </a>
                    <a href="settings"
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
                            <p class="text-sm font-medium">Admin</p>
                            <p class="text-xs opacity-70">Admin User</p>
                        </div>
                    </div>
                    <button class="mt-3 w-full py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-sm "
                        onclick="window.location.href='logout';">
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
                        <h2 class="text-xl font-semibold text-gray-800">Create Service</h2>
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
                            <span class="ml-2 text-sm font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 pb-20">

                <form class="space-y-4" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Title</label>
                            <input type="text" placeholder="e.g Google play Review" name="title" required
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm mb-1">Advertiser Description</label>
                            <textarea placeholder="Description" name="advDes"
                                class="  w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            </textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm mb-1">Earner Description</label>
                            <textarea placeholder="Description" name="earnerDes"
                                class="  w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            </textarea>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Advertiser Price</label>
                            <input type="number" step="0.01" placeholder="e.g 20.45" name="advPrice"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Earner Price</label>
                            <input type="number" step="0.01" placeholder="e.g 2.50" name="earnerPrice"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Per (Count)</label>
                            <input type="number" placeholder="e.g 10" name="per"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Platform</label>
                            <input type="text" placeholder="e.g google" name="platform"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Platform Background</label>
                            <input type="text" placeholder="e.g bg-blue-100" name="platformBg"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Platform Colour</label>
                            <input type="text" placeholder="e.g bg-blue-800" name="platformColour"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Image Url</label>
                            <input type="text" placeholder="e.g https://image-link.png" name="imgUrl"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Image Background</label>
                            <input type="text" placeholder="e.g bg-purple-100" name="imgBg"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Icon</label>
                            <input type="text" placeholder="e.g fas fa-star" name="icon"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Icon Background</label>
                            <input type="text" placeholder="e.g text-red-100" name="iconBg"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Icon Colour</label>
                            <input type="text" placeholder="e.g text-red-500" name="iconColour" required
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Rating</label>
                            <input type="number" step="0.01" placeholder="e.g 4.21" name="rating"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Rating Count</label>
                            <input type="number" placeholder="e.g 1200" name="ratingCount"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Highlight</label>
                            <input type="text" placeholder="e.g BESTSELLER" name="highLight"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Highlight Colour</label>
                            <input type="text" placeholder="e.g text-blue-500" name="highLightColour"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Highlight Background</label>
                            <input type="text" placeholder="e.g text-blue-100" name="highLightBg"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Category</label>
                            <select name="categoryId" required
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                                <option value="">-- Select Category --</option>
                                <?php foreach($rowsCategory as $row):?>
                                <option value="<?php echo $row -> id?>"><?php echo $row -> description ?></option>
                                
                                <?php endforeach?>
                            </select>
                        </div>


                    </div>
                    <button type="submit" name="submit"
                        class="mt-4 w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">Save
                    </button>
                </form>




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