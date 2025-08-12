<?php
      session_start();
    if (!isset($_SESSION['id'])) {
        header('location: login');
        exit;
    }

    $id = $_SESSION['id'];

    include_once 'inc/database.php';

    // Fetch orders
    $sql = 'SELECT socialorder.id AS orderId, socialorder.*, service.* 
            FROM socialorder 
            INNER JOIN service ON socialorder.serviceId = service.id 
            WHERE socialorder.advId = :id 
            ORDER BY socialorder.id DESC'; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $rows = $stmt->fetchAll();


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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
        if (isset($_GET['status']) && $_GET['status'] === 'success') {
            echo "<script>
                Swal.fire({
                    title: 'Order Placed Successfully',
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
                        <h2 class="text-xl font-semibold text-gray-800">Order History</h2>
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
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50" x-data="orderTable()" x-init="init()">

                <div class="max-w-7xl mx-auto bg-white p-6 rounded-xl shadow">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <h1 class="text-xl font-semibold text-gray-800">Order History</h1>
                        <input type="text" placeholder="Search..."
                            class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                            x-model="search" @input="filterRows()" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 font-semibold">
                                <tr>
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Price</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Social URL</th>
                                    <th class="px-4 py-2 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in paginatedRows()" :key="row.id">
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2" x-text="row.id"></td>
                                        <td class="px-4 py-2" x-text="row.title"></td>
                                        <td class="px-4 py-2" x-text="row.amount"></td>
                                        <td class="px-4 py-2" x-text="row.quantity"></td>
                                        <td class="px-4 py-2">
                                            <span class="text-xs px-2 py-1 rounded-full"
                                                :class="row.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                                x-text="row.status"></span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <a :href="row.socialurl" class="text-blue-600 underline break-all"
                                                x-text="row.socialurl"></a>
                                        </td>
                                        <td class="px-4 py-2" x-text="row.date"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
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



    const ordersFromPHP = <?php echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function orderTable() {
        return {
            search: '',
            currentPage: 1,
            perPage: 5,
            startIndex: 0,
            rows: [],
            filtered: [],
            init() {
                this.rows = ordersFromPHP.map(order => ({
                    id: order.orderId,
                    title: order.title,
                    amount: `$${parseFloat(order.amountSpent).toFixed(2)}`,
                    quantity: order.quantity,
                    status: order.status,
                    socialurl: order.socialUrl,
                    date: formatDate(order.orderDate || order.date ||
                    new Date()) // fallback if column name is different
                }));
                this.filtered = this.rows;
            },
            filterRows() {
                const keyword = this.search.toLowerCase();
                this.filtered = this.rows.filter(row =>
                    Object.values(row).some(value =>
                        String(value).toLowerCase().includes(keyword)
                    )
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
            }
        }
    }
    </script>
</body>

</html>