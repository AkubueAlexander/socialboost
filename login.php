<?php
include_once 'inc/database.php';
    $error = '';

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $passMd5 = md5($password);
    $sql = 'SELECT * FROM user  WHERE email = :email && pass =:pass LIMIT 1';
        
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email,'pass' => $passMd5]);
    $row = $stmt->fetch();
    if ($row) {

        if ($row -> verifiedStatus == 1) {

            session_start(); 
            $_SESSION['id'] = $row -> id;

           if ($row -> userType == 'earner') {                    
                       
            header('location: earner');
            }
            
            else{
                
                header('location: advertiser');       
            } 
        } else {
            $error = $row -> email.' is not verified, go to your email inbox and click the activation link.'; 
        }   

                 
    }

        else {
        $error = 'Invalid credentials';
        }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Boost | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8fafc;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        border-color: #3b82f6;
    }

    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(29, 78, 216, 0.5);
    }

    .social-icon {
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        transform: scale(1.1);
    }

    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-15px);
        }

        100% {
            transform: translateY(0px);
        }
    }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side - Illustration -->
        <div class="w-full md:w-1/2 gradient-bg flex items-center justify-center p-8 text-white">
            <div class="max-w-md text-center">
                <div class="floating mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mx-auto">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4">Welcome to Social Boost</h1>
                <p class="text-lg opacity-90 mb-6">Connect with friends, share your moments, and boost your social
                    presence with our platform.</p>
                <p class="text-sm opacity-75">Don't have an account? <a href="register-earner"
                        class="font-semibold underline hover:opacity-90">Sign up as earner</a></p>
                <p class="text-sm opacity-75"><a href="register-advertiser"
                        class="font-semibold underline hover:opacity-90">Sign up as Adveriser</a></p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Login to Your Account</h2>
                    <p class="text-gray-600">Enter your credentials to access your dashboard</p>
                </div>

                <form id="loginForm" class="space-y-6" method="POST">
                    <p class="text-red-500 text-sm">
                        <?php echo $error; ?>
                    </p>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-blue-500"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="your@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-blue-500"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="••••••••">
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-3 text-gray-400 hover:text-blue-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                        </div>
                    </div>

                    <button type="submit" name="submit"
                        class="w-full btn-hover flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 transform">
                        Sign In
                    </button>

                    <!-- Login Link -->
                    <p class="mt-4 text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="register-earner"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                            Sign up as Earner
                        </a> | 
                        <a href="register-advertiser"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                            Sign up as Adveriser
                        </a>
                    </p>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <a href="#"
                            class="social-icon w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-blue-600">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#"
                            class="social-icon w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-blue-400">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#"
                            class="social-icon w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-red-500">
                            <i class="fab fa-google text-xl"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                '<i class="fas fa-eye-slash"></i>';
        });

        // Form submission
        const loginForm = document.getElementById('loginForm');

        loginForm.addEventListener('submit', function(e) {


            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Simple validation
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }


        });

        // Add floating animation to social icons on hover
        const socialIcons = document.querySelectorAll('.social-icon');
        socialIcons.forEach(icon => {
            icon.addEventListener('mouseenter', () => {
                icon.classList.add('floating');
            });
            icon.addEventListener('mouseleave', () => {
                icon.classList.remove('floating');
            });
        });
    });
    </script>
</body>

</html>