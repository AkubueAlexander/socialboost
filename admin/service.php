<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    include_once '../inc/database.php';

      $sqlRecent = 'SELECT socialorder.id AS orderId, socialorder.*, service.* FROM socialorder
     INNER JOIN service ON socialorder.serviceId = service.id  LIMIT 4';        
    $stmtRecent = $pdo->prepare($sqlRecent);
    $stmtRecent->execute();
    $rows = $stmtRecent->fetchAll();


    // Fetch orders
    $sql = 'SELECT * FROM service ORDER BY id ASC'; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rowsService = $stmt->fetchAll();


      if (isset($_POST['edit'])) {       

        $id = $_POST['id'];               
        $title = $_POST['title'];
        $advDes = $_POST['advDes'];
        $earnerDes = $_POST['earnerDes'];    
        $advPrice = $_POST['advPrice'];
        $earnerPrice = $_POST['earnerPrice'];
        $per = $_POST['per'];
        $platform = $_POST['platform'];    
        $imgUrl = $_POST['imgUrl'];
        $imgBg = $_POST['imgBg'];               
        $icon = $_POST['icon'];
        $iconColour = $_POST['iconColour'];
        $iconBg = $_POST['iconBg'];
        $rating = $_POST['rating'];    
        $ratingCount = $_POST['ratingCount'];
        $platformBg = $_POST['platformBg'];
        $platformColour = $_POST['platformColour'];
        $highLight = $_POST['highLight'];    
        $highLightColour = $_POST['highLightColour'];
        $highLightBg = $_POST['highLightBg'];               
        $category = $_POST['category'];
     
             
        
    
        $update_sql = 'UPDATE service SET title = :title,advDes = :advDes,earnerDes = :earnerDes,
        advPrice = :advPrice,earnerPrice = :earnerPrice,per = :per,platform = :platform,imgUrl = :imgUrl,
        imgBg = :imgBg,icon = :icon,iconColour = :iconColour,iconBg = :iconBg,rating = :rating,ratingCount = :ratingCount,
        platformBg = :platformBg,platformColour = :platformColour,highLight = :highLight,highLightColour = :highLightColour,highLightBg = :highLightBg,
        category = :category

        Where id = :id ';
        $update = $pdo->prepare($update_sql);        
        $update->execute(['title' => $title,
        'advDes' => $advDes,'earnerDes' => $earnerDes,'advPrice' => $advPrice,'earnerPrice' => $earnerPrice,'per' => $per
    ,
        'platform' => $platform,'imgUrl' => $imgUrl,'imgBg' => $imgBg,'icon' => $icon,'iconColour' => $iconColour,'iconBg' => $iconBg
    ,
        'rating' => $rating,'ratingCount' => $ratingCount,'platformBg' => $platformBg,'platformColour' => $platformColour,'highLight' => $highLight
    ,
        'highLightColour' => $highLightColour,'highLightBg' => $highLightBg,'category' => $category,'id' => $id]);
        
    
        echo '<script>
                    setTimeout(function() {
                    window.location.href = "service?updated=true";
                    }, 200);
                    </script>';
    
}

    

    
    


  

    

   

    
    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostbrands - Service Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

    [x-cloak] {
        display: none !important
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
        if (isset($_GET['updated'])) {
            echo "<script>
                Swal.fire({
                    title: 'Service Updated Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
         <?php
        if (isset($_GET['created'])) {
            echo "<script>
                Swal.fire({
                    title: 'Service Inserted Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
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
                        <h2 class="text-xl font-semibold text-gray-800">Admin Dashboard</h2>
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
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">



                <div class="max-w-7xl mx-auto bg-white p-6 rounded-xl shadow" x-data="serviceTable()" x-init="init()">
                    <div class="flex justify-end mb-4">
                        <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded" onclick="window.location.href='create-service';">
                            Create
                        </button>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <h1 class="text-xl font-semibold text-gray-800">Service</h1>
                        <input type="text" placeholder="Search..."
                            class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                            x-model="search" @input="filterRows()" />
                    </div>

                    <div class="overflow-x-auto ">
                        <table class="min-w-max divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 font-semibold">
                                <tr>
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Advertiser Description</th>
                                    <th class="px-4 py-2 text-left">Earner Description</th>
                                    <th class="px-4 py-2 text-left">Advertiser Price</th>
                                    <th class="px-4 py-2 text-left">Earner Price</th>
                                    <th class="px-4 py-2 text-left">Count</th>
                                    <th class="px-4 py-2 text-left">Platform</th>
                                    <th class="px-4 py-2 text-left">Image Url</th>
                                    <th class="px-4 py-2 text-left">Image Background</th>
                                    <th class="px-4 py-2 text-left">Icon</th>
                                    <th class="px-4 py-2 text-left">Icon Background</th>
                                    <th class="px-4 py-2 text-left">Icon Colour</th>
                                    <th class="px-4 py-2 text-left">Rating</th>
                                    <th class="px-4 py-2 text-left">Rating Count</th>
                                    <th class="px-4 py-2 text-left">Platform Background</th>
                                    <th class="px-4 py-2 text-left">Platform Colour</th>
                                    <th class="px-4 py-2 text-left">High Light</th>
                                    <th class="px-4 py-2 text-left">High Light Colour</th>
                                    <th class="px-4 py-2 text-left">Hight Light Background</th>
                                    <th class="px-4 py-2 text-left">Category</th>
                                    <th class="px-4 py-2 text-left">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in paginatedRows()" :key="row.id">
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2" x-text="row.id"></td>
                                        <td class="px-4 py-2" x-text="row.title"></td>
                                        <td class="px-4 py-2" x-text="row.advDes"></td>
                                        <td class="px-4 py-2" x-text="row.earnerDes"></td>
                                        <td class="px-4 py-2" x-text="row.advPrice"></td>
                                        <td class="px-4 py-2" x-text="row.earnerPrice"></td>
                                        <td class="px-4 py-2" x-text="row.per"></td>
                                        <td class="px-4 py-2" x-text="row.platform"></td>
                                        <td class="px-4 py-2" x-text="row.imgUrl"></td>
                                        <td class="px-4 py-2" x-text="row.imgBg"></td>
                                        <td class="px-4 py-2" x-text="row.icon"></td>
                                        <td class="px-4 py-2" x-text="row.iconBg"></td>
                                        <td class="px-4 py-2" x-text="row.iconColour"></td>
                                        <td class="px-4 py-2" x-text="row.rating"></td>
                                        <td class="px-4 py-2" x-text="row.ratingCount"></td>
                                        <td class="px-4 py-2" x-text="row.platformBg"></td>
                                        <td class="px-4 py-2" x-text="row.platformColour"></td>
                                        <td class="px-4 py-2" x-text="row.highLight"></td>
                                        <td class="px-4 py-2" x-text="row.highLightColour"></td>
                                        <td class="px-4 py-2" x-text="row.highLightBg"></td>
                                        <td class="px-4 py-2" x-text="row.category"></td>



                                        <td class="border border-gray-300 p-2 flex gap-2">
                                            <button @click="showModal(row, false)"
                                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                View
                                            </button>
                                            <button @click="showModal(row, true)"
                                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </button>
                                            <button @click="confirmDelete(row.id)"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                        </td>

                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Modal  -->
                        <div x-show="modalOpen" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition
                            @keydown.escape.window="modalOpen=false" @click.self="modalOpen=false">
                            <div
                                class="bg-white w-[95%] max-w-2xl rounded-xl shadow-lg p-6  max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-semibold"
                                        x-text="isEditing ? 'Edit Order' : 'View Service'">
                                    </h2>
                                    <button @click="modalOpen=false"
                                        class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
                                </div>
                                <form action="" method="post">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- ID (always read-only) -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">ID</label>
                                            <input type="text" x-model="modalData.id" 
                                                class="w-full border rounded-lg p-2 bg-gray-100 cursor-not-allowed"
                                                readonly>
                                            <input type="hidden" name="id" x-model="modalData.id">
                                        </div>

                                        <!-- Title -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Title</label>
                                            <input type="text" x-model="modalData.title" name="title"
                                                placeholder="Title" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Advertiser Description</label>
                                            <textarea class="md:col-span-2" type="text" x-model="modalData.advDes"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''" name="advDes"
                                                placeholder="Advertiser Description"> </textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Earner Description</label>
                                            <textarea class="md:col-span-2" type="text" x-model="modalData.earnerDes"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''" name="earnerDes"
                                                placeholder="Earner Description"> </textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Advertiser Price</label>
                                            <input type="number" step="0.01" x-model.number="modalData.advPrice" name="advPrice"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Earner Price</label>
                                            <input type="number" step="0.01" x-model.number="modalData.earnerPrice" name="earnerPrice"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Per(Count)</label>
                                            <input type="number" x-model.number="modalData.per" name="per"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Platform</label>
                                            <input type="text" x-model="modalData.platform" name="platform"
                                                placeholder="e.g Apple" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>




                                        <div>
                                            <label class="block text-sm font-medium mb-1">Image Url</label>
                                            <input type="phone" x-model="modalData.imgUrl" name="imgUrl"
                                                placeholder="e.g https://image.png" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Image Background</label>
                                            <input type="text" x-model="modalData.imgBg" name="imgBg"
                                                placeholder="e.g bg-red-100" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Icon</label>
                                            <input type="text" x-model="modalData.icon" name="icon"
                                                placeholder="e.g fas fa-apple" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Icon Background</label>
                                            <input type="text" x-model="modalData.iconBg" name="iconBg"
                                                placeholder="e.g bg-red-100" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Icon Colour</label>
                                            <input type="text" x-model="modalData.iconColour" name="iconColour"
                                                placeholder="e.g text-red-500" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Rating</label>
                                            <input type="number" step="0.01" x-model.number="modalData.rating" name="rating"
                                                placeholder="e.g 4.5" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Rating Count</label>
                                            <input type="number" x-model.number="modalData.ratingCount" name="ratingCount"
                                                placeholder="e.g 1500" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Platform Background</label>
                                            <input type="text" x-model="modalData.platformBg" name="platformBg"
                                                placeholder="e.g bg-red-100" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Platform Colour</label>
                                            <input type="text" x-model="modalData.platformColour" name="platformColour"
                                                placeholder="e.g text-red-500" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Highlight</label>
                                            <input type="text" x-model="modalData.highLight" name="highLight"
                                                placeholder="e.g New" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Highlight Colour</label>
                                            <input type="text" x-model="modalData.highLightColour"
                                                name="highLightColour" placeholder="e.g text-red-500"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Highlight Background</label>
                                            <input type="text" x-model="modalData.highLightBg" name="highLightBg"
                                                placeholder="e.g bg-red-100" class="w-full border rounded-lg p-2"
                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Category</label>
                                            <input type="text" x-model="modalData.category" name="category"
                                                placeholder="e.g Followers | Reviews"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>



                                    </div>

                                    <div class="flex justify-end gap-2 mt-6">
                                        <button @click="modalOpen=false"
                                            class="px-4 py-2 rounded-lg border">Close</button>
                                        <button type="submit" x-show="isEditing"   name="edit"
                                            class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col md:flex-row justify-between items-center mt-6 space-y-4 md:space-y-0">
                        <p class="text-sm text-gray-600"
                            x-text="`Showing ${startIndex + 1} to ${Math.min(endIndex(), filtered.length)} of ${filtered.length} orders`">
                        </p>
                        <div class="flex items-center space-x-1">
                            <button class="px-3 py-1 border rounded hover:bg-gray-200" @click="prevPage"
                                :disabled="currentPage === 1">Prev</button>
                            <template x-for="page in totalPages()" :key="page">
                                <button class="px-3 py-1 border rounded"
                                    :class="page === currentPage ? 'bg-blue-600 text-white' : 'hover:bg-gray-200'"
                                    @click="currentPage = page" x-text="page"></button>
                            </template>
                            <button class="px-3 py-1 border rounded hover:bg-gray-200" @click="nextPage"
                                :disabled="currentPage === totalPages()">Next</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                            <button class="text-sm text-purple-600 hover:text-purple-800 font-medium"
                                onclick="event.stopPropagation();window.location.href='order-history'">
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
                                    <div
                                        class="platform-icon <?php echo $row -> iconBg ?> <?php echo $row -> iconColour ?>">
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
                                    <p class="text-sm text-gray-500 mt-2"><?php echo $count ?> out of
                                        <?php echo $row -> quantity ?> delivered</p>
                                    <div class="flex items-center mt-1">

                                        <span
                                            class="ml-2 text-xs text-gray-500"><?php echo number_format($percentage, 0) ?>%</span>
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

    const servicesFromPHP = <?php echo json_encode($rowsService, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function serviceTable() {
        return {
            search: '',
            currentPage: 1,
            perPage: 5,
            startIndex: 0,
            rows: [],
            filtered: [],
            // modal state
            modalOpen: false,
            isEditing: false,
            modalData: {},

            init() {
                this.rows = servicesFromPHP.map(service => {

                    return {
                        id: service.id,
                        title: service.title,
                        advDes: service.advDes,
                        earnerDes: service.earnerDes,
                        advPrice: service.advPrice,
                        earnerPrice: service.earnerPrice,
                        per: service.per,
                        platform: service.platform,
                        imgUrl: service.imgUrl,
                        imgBg: service.imgBg,
                        icon: service.icon,
                        iconBg: service.iconBg,
                        iconColour: service.iconColour,
                        rating: service.rating,
                        ratingCount: service.ratingCount,
                        platformBg: service.platformBg,
                        platformColour: service.platformColour,
                        highLight: service.highLight,
                        highLightColour: service.highLightColour,
                        highLightBg: service.highLightBg,
                        category: service.category
                    };
                });
                this.filtered = this.rows;
            },

            filterRows() {
                const keyword = this.search.toLowerCase();
                this.filtered = this.rows.filter(row =>
                    Object.values(row).some(value => String(value).toLowerCase().includes(keyword))
                );
                this.currentPage = 1;
            },
            paginatedRows() {
                this.startIndex = (this.currentPage - 1) * this.perPage;
                return this.filtered.slice(this.startIndex, this.endIndex());
            },
            endIndex() {
                return this.startIndex + this.perPage;
            },
            totalPages() {
                return Math.ceil(this.filtered.length / this.perPage) || 1;
            },
            nextPage() {
                if (this.currentPage < this.totalPages()) this.currentPage++;
            },
            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },

            // modal actions
            showModal(row, editMode = false) {
                this.modalData = {
                    ...row
                }; // clone row for editing
                this.isEditing = !!editMode;
                this.modalOpen = true;
            },
            saveEdit() {
                // apply modal changes back to table row
                const i = this.rows.findIndex(r => r.id === this.modalData.id);
                if (i !== -1) {
                    // update the row with modal data
                    this.rows[i] = {
                        ...this.rows[i],
                        title: this.modalData.title,
                        advDes: this.modalData.advDes,
                        earnerDes: this.modalData.earnerDes,
                        advPrice: this.modalData.advPrice,
                        per: this.modalData.per,
                        platform: this.modalData.platform,
                        imgUrl: this.modalData.imgUrl,
                        imgBg: this.modalData.imgBg,
                        icon: this.modalData.icon,
                        iconBg: this.modalData.iconBg,
                        iconColour: this.modalData.iconColour,
                        rating: this.modalData.rating,
                        ratingCount: this.modalData.ratingCount,
                        platformBg: this.modalData.platformBg,
                        platformColour: this.modalData.platformColour,
                        highLight: this.modalData.highLight,
                        highLightColour: this.modalData.highLightColour,
                        highLightBg: this.modalData.highLightBg,
                        category: this.modalData.category


                    };
                    // refresh filtered so table reflects changes immediately
                    this.filterRows();
                }
                this.modalOpen = false;

                // TODO: send AJAX to your PHP endpoint to persist changes server-side
                // fetch('update-order.php', { method:'POST', body: JSON.stringify(this.rows[i]) })
            },

            confirmDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.rows = this.rows.filter(r => r.id !== id);
                        this.filterRows();
                        Swal.fire("Deleted!", "The order has been removed.", "success");
                        // TODO: also delete from server via AJAX
                    }
                });
            }


        }
    }
    </script>
</body>

</html>