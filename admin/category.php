<?php
     session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 
    include_once '../inc/database.php';

  


    // Fetch orders
    $sql = 'SELECT *  FROM category'; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rowsCategory = $stmt->fetchAll();


    if (isset($_POST['edit'])) {

      

        $id = $_POST['id'];               
        $name = $_POST['name'];
        $description = $_POST['description'];
         
        
    
        $update_sql = 'UPDATE category SET name = :name,description = :description Where id = :id ';
        $update = $pdo->prepare($update_sql);        
        $update->execute(['name' => $name,
        'description' => $description,'id' => $id]);
        
    
        echo '<script>
                    setTimeout(function() {
                    window.location.href = "category?updated=true";
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
                    title: 'Order Updated Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        ?>
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

                <div class="max-w-7xl mx-auto bg-white p-6 rounded-xl shadow" x-data="categoryTable()" x-init="init()">

                    <div class="flex justify-end mb-4">
                        <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded"
                            onclick="window.location.href='create-category';">
                            Create
                        </button>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <h1 class="text-xl font-semibold text-gray-800">Category</h1>
                        <input type="text" placeholder="Search..."
                            class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                            x-model="search" @input="filterRows()" />
                    </div>

                    <div class="overflow-x-auto ">
                        <table class="min-w-max divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 font-semibold">
                                <tr>
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in paginatedRows()" :key="row.id">
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2" x-text="row.id"></td>
                                        <td class="px-4 py-2" x-text="row.name"></td>
                                        <td class="px-4 py-2" x-text="row.description"></td>

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

                        <!-- Modal (drop-in replacement) -->
                        <div x-show="modalOpen" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition
                            @keydown.escape.window="modalOpen=false" @click.self="modalOpen=false">
                            <div class="bg-white w-[95%] max-w-2xl rounded-xl shadow-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-semibold" x-text="isEditing ? 'Edit Order' : 'View Order'">
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


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Name</label>
                                            <input type="text" x-model="modalData.name" name="name"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium mb-1">Description</label>
                                            <input type="text" x-model="modalData.description" name="description"
                                                class="w-full border rounded-lg p-2" :readonly="!isEditing"
                                                :class="!isEditing ? 'bg-gray-50' : ''">
                                        </div>

                                    </div>

                                    <div class="flex justify-end gap-2 mt-6">
                                        <button @click="modalOpen=false"
                                            class="px-4 py-2 rounded-lg border">Close</button>
                                        <button type="submit" x-show="isEditing" @click="saveEdit()" name="edit"
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

    const categoriesFromPHP =
        <?php echo json_encode($rowsCategory, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function categoryTable() {
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
            statuses: ['Pending', 'In Progress', 'Completed'],

            // helpers
            toISODate(dateStr) {
                // robust ISO yyyy-mm-dd for input[type=date]
                const d = dateStr ? new Date(dateStr) : new Date();
                // avoid timezone offset issues
                const tz = d.getTimezoneOffset() * 60000;
                return new Date(d.getTime() - tz).toISOString().slice(0, 10);
            },
            formatDisplayDate(iso) {
                if (!iso) return '';
                const [y, m, d] = iso.split('-');
                const dt = new Date(`${y}-${m}-${d}T00:00:00`);
                return dt.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            },

            init() {
                this.rows = categoriesFromPHP.map(category => {

                    return {
                        id: category.id,
                        name: category.name,
                        description: category.description,

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
                    const amountNum = Number(this.modalData.amountValue) || 0;
                    const iso = this.modalData.dateISO || this.toISODate(new Date());
                    this.rows[i] = {
                        ...this.rows[i],
                        name: this.modalData.name,
                        description: this.modalData.description
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
                        fetch('delete-category.php', {
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
                                    Swal.fire("Deleted!", "The Category has been deleted.", "success");
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