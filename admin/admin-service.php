<?php

     session_start();

    if (!isset($_SESSION['id'])) {

         header('location: login');

    } 

    include_once '../inc/database.php';



 





    // Fetch Service

    $sql = 'SELECT * FROM adminservice ORDER BY id ASC'; 

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $rowsService = $stmt->fetchAll();





      if (isset($_POST['edit'])) {       



        $id = $_POST['id'];               

        $title = $_POST['title'];

        $icon = $_POST['icon'];

        $iconColour = $_POST['iconColour'];    

        $iconBg = $_POST['iconBg'];

        $highLight = $_POST['highLight'];

        $highLightBg = $_POST['highLightBg'];

        $highLightColour = $_POST['highLightColour'];    

        $des = $_POST['des'];

        $platform = $_POST['platform'];               

        $platformColour = $_POST['platformColour'];

        $platformBg = $_POST['platformBg'];

        $socialUrl = $_POST['socialUrl'];   

        $postUrl = $_POST['postUrl']; 

   

        $update_sql = 'UPDATE adminservice SET title = :title,icon = :icon,iconColour = :iconColour,

        iconBg = :iconBg,highLight = :highLight,highLightBg = :highLightBg,des = :des,platform = :platform,

        platformColour = :platformColour,platformBg = :platformBg,socialUrl = :socialUrl,postUrl = :postUrl Where id = :id ';

        $update = $pdo->prepare($update_sql);        

        $update->execute(['title' => $title,

        'icon' => $icon,'iconColour' => $iconColour,'iconBg' => $iconBg,'highLight' => $highLight,'highLightBg' => $highLightBg

    ,

        'des' => $des,'platform' => $platform,'platformColour' => $platformColour,'platformBg' => $platformBg,
        'socialUrl' => $socialUrl,'postUrl' => $postUrl, 'id' => $id]);


        echo '<script>
                    setTimeout(function() {
                    window.location.href = "admin-service?updated=true";
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

        <div class="sidebar relative bg-gradient-to-b from-purple-600 to-indigo-700 text-white w-64 flex-shrink-0 flex flex-col">

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
                    <a href="admin-service"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-tools mr-3"></i>
                        Admin Service
                    </a>
                    <a href="task"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-tasks mr-3"></i>
                        Task
                    </a>
                    <a href="admin-task"
                        class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-10 rounded-lg text-white">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Admin Task
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

                        <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded" onclick="window.location.href='create-admin-service';">

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
                                    <th class="px-4 py-2 text-left">Icon</th>
                                    <th class="px-4 py-2 text-left">Icon Colour</th>
                                    <th class="px-4 py-2 text-left">Icon Background</th>
                                    <th class="px-4 py-2 text-left">Highlight</th>
                                    <th class="px-4 py-2 text-left">Highlight Background</th>
                                    <th class="px-4 py-2 text-left">Highlight Colour</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Platform</th>
                                    <th class="px-4 py-2 text-left">Platform Colour</th>
                                    <th class="px-4 py-2 text-left">Platform Background</th>                                    
                                    <th class="px-4 py-2 text-left">Social Url</th>      
                                    <th class="px-4 py-2 text-left">Post Url</th>                               
                                    <th class="px-4 py-2 text-left">Actions</th>



                                </tr>

                            </thead>

                            <tbody>

                                <template x-for="row in paginatedRows()" :key="row.id">

                                    <tr class="hover:bg-gray-50 border-b">

                                      
                                        <td class="px-4 py-2" x-text="row.id"></td>
                                        <td class="px-4 py-2" x-text="row.title"></td>
                                        <td class="px-4 py-2" x-text="row.icon"></td>
                                        <td class="px-4 py-2" x-text="row.iconColour"></td>
                                        <td class="px-4 py-2" x-text="row.iconBg"></td>
                                        <td class="px-4 py-2" x-text="row.highLight"></td>
                                        <td class="px-4 py-2" x-text="row.highLightBg"></td>
                                        <td class="px-4 py-2" x-text="row.highLightColour"></td>
                                        <td class="px-4 py-2" x-text="row.des"></td>
                                        <td class="px-4 py-2" x-text="row.platform"></td>
                                        <td class="px-4 py-2" x-text="row.platformColour"></td>
                                        <td class="px-4 py-2" x-text="row.platformBg"></td>
                                        <td class="px-4 py-2" x-text="row.socialUrl"></td> 
                                        <td class="px-4 py-2" x-text="row.postUrl"></td> 







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

                                            <label class="block text-sm font-medium mb-1"> Description</label>

                                            <textarea class="md:col-span-2" type="text" x-model="modalData.des"

                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"

                                                :class="!isEditing ? 'bg-gray-50' : ''" name="des"

                                                placeholder="Description"> </textarea>

                                        </div>                                     



                                        <div>

                                            <label class="block text-sm font-medium mb-1">Icon</label>

                                            <input type="text" x-model="modalData.icon" name="icon"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Icon Colour</label>

                                            <input type="text" x-model="modalData.iconColour" name="iconColour"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>
                                         <div>

                                            <label class="block text-sm font-medium mb-1">Icon Background</label>

                                            <input type="text" x-model="modalData.iconBg" name="iconBg"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Highlight</label>

                                            <input type="text" x-model="modalData.highLight" name="highLight"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                         <div>

                                            <label class="block text-sm font-medium mb-1">Highlight Background</label>

                                            <input type="text" x-model="modalData.highLightBg" name="highLightBg"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>
                                           <div>

                                            <label class="block text-sm font-medium mb-1">Highlight Colour</label>

                                            <input type="text" x-model="modalData.highLightColour" name="highLightColour"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Platform</label>

                                            <input type="text" x-model="modalData.platform" name="platform"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Platform Colour</label>

                                            <input type="text" x-model="modalData.platformColour" name="platformColour"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Platform Background</label>

                                            <input type="text" x-model="modalData.platformBg" name="platformBg"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>

                                        <div>

                                            <label class="block text-sm font-medium mb-1">Social Url</label>

                                            <input type="text" x-model="modalData.socialUrl" name="socialUrl"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

                                        </div>    
                                        
                                        <div>

                                            <label class="block text-sm font-medium mb-1">Post Url</label>

                                            <input type="text" x-model="modalData.postUrl" name="postUrl"

                                                 class="w-full border rounded-lg p-2"

                                                :readonly="!isEditing" :class="!isEditing ? 'bg-gray-50' : ''">

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

                            x-text="`Showing ${startIndex + 1} to ${Math.min(endIndex(), filtered.length)} of ${filtered.length} services`">

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

                        icon: service.icon,

                        iconColour: service.iconColour,

                        iconBg: service.iconBg,

                        highLight: service.highLight,

                        highLightBg: service.highLightBg,

                        highLightColour: service.highLightColour,

                        des: service.des,

                        platform: service.platform,

                        platformColour: service.platformColour,

                        platformBg: service.platformBg,

                        socialUrl: service.socialUrl,
                        postUrl: service.postUrl

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

                        icon: this.modalData.icon,

                        iconColour: this.modalData.iconColour,

                        iconBg: this.modalData.iconBg,

                        highLight: this.modalData.highLight,

                        highLightBg: this.modalData.highLightBg,

                        highLightColour: this.modalData.highLightColour,

                        des: this.modalData.des,

                        platform: this.modalData.platform,

                        platformColour: this.modalData.platformColour,

                        platformBg: this.modalData.platformBg,

                        socialUrl: this.modalData.socialUrl,

                        postUrl: this.modalData.postUrl





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

                    confirmButtonText: "Yes, delete it"

                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch('delete-admin-service.php', {

                                method: 'POST',

                                headers: {

                                    'Content-Type': 'application/x-www-form-urlencoded'

                                },

                                body: 'id=' + encodeURIComponent(id)

                            })

                            .then(res => res.json())

                            .then(data => {

                                if (data.success) {

                                    this.rows = this.rows.filter(r => r.id !== id); // remove from table

                                    this.filterRows();

                                    Swal.fire("Deleted!", "The service has been deleted.", "success");

                                } else {

                                    Swal.fire("Error", data.message || "Could not delete order.", "error");

                                }

                            })

                            .catch(() => {

                                Swal.fire("Error", "Could not contact server.", "error");

                            });

                    }

                });

            }







        }

    }

    </script>

</body>



</html>