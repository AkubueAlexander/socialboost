<?php    

session_start();
    if (!isset($_SESSION['id'])) {
         header('location: login');
    } 

include_once 'inc/database.php';

 $orderId = $_GET['orderId'];
 $taskId = $_GET['taskId'];

 

 $sql = "SELECT socialorder.id AS orderId,
    socialorder.socialUrl, service.earnerDes,service.title
    FROM socialorder
    INNER JOIN service ON socialorder.serviceId = service.id
    WHERE socialorder.id = :orderId LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['orderId' => $orderId]);
    $row = $stmt->fetch();

 
if (isset($_POST['submit'])) {
    $earnerId = $_SESSION['id'];
    

    // Handle file upload
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'screenshot/';
        $fileName = uniqid('receipt_', true) . '.' . pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath)) {

            $statusTask = 'Pending Approval';
            $statusOrder = 'Completed';

            $sqlUpdateTask = 'UPDATE task SET status =  :status, receipt = :receipt WHERE id = :id';
            $stmtUpdateTask = $pdo->prepare($sqlUpdateTask);
            $stmtUpdateTask->execute(['status' => $statusTask, 'id' => $taskId, 'receipt' => $filePath]);

            $sqlCheck = 'SELECT orderCountTrack FROM socialorder  WHERE id = :id LIMIT 1';        
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(['id' => $orderId]);
            $rowCheck = $stmtCheck->fetch();

            $orderTrackCount = $rowCheck -> orderCountTrack;

            if ($orderTrackCount == 0) {
                $sqlUpdateOrder = 'UPDATE socialOrder SET status =  :status WHERE id = :id';
                $stmtUpdateOrder = $pdo->prepare($sqlUpdateOrder);
                $stmtUpdateOrder->execute(['status' => $statusOrder, 'id' => $orderId]); 
            }

                               
            
            header('location: earnings?success=Task submitted successfully');
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
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
                            <span class="text-xl font-semibold">JS</span>
                        </div>
                        <div>
                            <p class="font-medium text-dark">John Smith</p>
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
                <button class="w-full flex items-center space-x-3 p-3 rounded-lg text-gray-600 hover:bg-primary/10 hover:text-primary">
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
                                <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-red-500 text-white text-xs rounded-full">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-600 hover:text-primary">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center bg-primary text-white text-xs rounded-full">5</span>
                            </button>
                        </div>
                        <button class="w-10 h-10 rounded-full bg-gray-100 text-primary flex items-center justify-center">
                            JS
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                
                
  <!-- Toast Notification -->
  <div id="toast"
       class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg opacity-0 transition-opacity duration-500 ease-in-out z-50">
    Link copied successfully!
  </div>

  <div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl shadow-xl">
    <h2 class="text-2xl font-bold mb-4 text-gray-800"> <?php echo $row -> title; ?></h2>

    <!-- Advertiser Link -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Advertiser Link</label>
      <div class="flex items-center bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
        <input id="advertiserLink" type="text" readonly value="<?php echo $row -> socialUrl; ?>"
               class="flex-1 bg-transparent text-sm text-gray-600 outline-none">
        <button onclick="copyLink()" class="text-blue-600 hover:text-blue-800 ml-3">
          <i class="fas fa-copy"></i>
        </button>
      </div>
    </div>

    <!-- Instructions -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
      <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-1">
        <li>Copy on the advertiser's link above.</li>
        <li>Visit the link.</li>
        <li><?php echo $row -> earnerDes; ?></li>
        <li>Take a screenshot and upload.</li>
      </ol>
    </div>

    <!-- Upload Screenshot -->
     <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Screenshot</label>
        <label for="screenshot"
                class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4a4 4 0 014-4h2a4 4 0 014 4v12m4 4H3m6-4l3 3m0 0l3-3m-3 3V10"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag & drop</p>
            <p class="text-xs text-gray-500">PNG, JPG, or JPEG (max 2MB)</p>
            </div>
            <input id="screenshot" name="receipt" type="file" class="hidden" accept="image/*" onchange="showFileName(event)" />
        </label>

            <!-- File Name Preview -->
        <div id="fileNameDisplay" class="mt-3 text-sm text-gray-600 hidden">
            📁 <span id="fileNameText"></span>
        </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-md font-medium text-sm transition duration-200">
        Submit Task
        </button>
    </form>

  </div>

  <script>
    function copyLink() {
      const input = document.getElementById("advertiserLink");
      input.select();
      input.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(input.value);

      const toast = document.getElementById("toast");
      toast.classList.remove("opacity-0");
      toast.classList.add("opacity-100");

      setTimeout(() => {
        toast.classList.remove("opacity-100");
        toast.classList.add("opacity-0");
      }, 3000);
    }

    function showFileName(event) {
      const file = event.target.files[0];
      if (file) {
        const fileNameDisplay = document.getElementById("fileNameDisplay");
        const fileNameText = document.getElementById("fileNameText");

        fileNameText.textContent = file.name;
        fileNameDisplay.classList.remove("hidden");
      }
    }
  </script>      
                
                
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

       
    </script>
</body>
</html>