<?php
include_once 'inc/database.php';
include_once 'config.php';
include_once 'method.php';


//Load Composer's autoloader
require 'phpmailer/vendor/autoload.php';
 use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



$error = '';
$email = '';
$userName = ''; 
$fullName = '';
$phone = '';
$password = '';
$confirmPassword = '';
$userId = '';
$referererUserName = '';
$ref = $_GET['ref'] ?? '';





if (isset($_POST['submit'])) {   
    $email = trim($_POST['email']);    
    $userName = $_POST['userName'];      
    $password = trim($_POST['password']);
    $passwordHash = md5($password);    
    $fullName = $_POST['fullName'];  
    $phone = trim($_POST['phone']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $referererUserName = trim($_POST['referererUserName']);
    $userType = 'advertiser';
    $otp = rand(10000, 99999);
    

    $query = 'SELECT COUNT(*) FROM user WHERE email = :email LIMIT 1';
    $stmtEmail = $pdo->prepare($query);
    $stmtEmail->bindParam(':email',$email);
    $stmtEmail->execute();
    $row = $stmtEmail->fetchColumn();    
    if ($row > 0 ) {
        $error = 'Email already exist';
    }
    
    elseif ($password != $confirmPassword) {
       $error = 'Password do not match';
    }
    elseif (empty($email)) {
       $error = 'Email fild cannot be empty';
    }
    else{  
        
        $sql = 'INSERT INTO user (userName, email, pass,userType, fullName,phone, otp) VALUES (:userName,:email,:pass,:userType,:fullName,:phone, :otp)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userName' => $userName,'email' => $email,'pass' => $passwordHash,'userType' => $userType,'fullName' => $fullName,'phone' => $phone,'otp' => $otp]);
        
        $id = $pdo->lastInsertId();


        $sqlWallet = 'INSERT INTO wallet ( userId) VALUES (:userId)';
        $stmtWallet = $pdo->prepare($sqlWallet);
        $stmtWallet->execute(['userId' => $id]);

        $to1 = $email;
        $subject_1 = 'Registeration successful';
        $fullName_1 = $fullName;       
        $body_1= '<div style="background:#1e293b;color:white;padding:15px">'; 
        $body_1 .= '<p style="margin:10px 0"><img style="height:50px" src="https://starnetweb.com/assets/img/logo.png" alt=""></p>';
       
        $body_1 .= '<h2 style="margin:10px 0">Hi investor,'.' '.$fullName_1.'</h2>';
        $body_1 .= '<p style="margin:10px 0;line-height:2;">Thanks you for creating an account
        on our website. Click the activate link below to activate your account</p><br/>';
        $body_1 .= '<p style="margin:10px 0;">Thank you for choosing Social Boost</p>';
        $body_1 .= '<div style="margin:30px 0">
        <a style="padding:15px 30px;border-radius:10px;background-color:#6366f1;border-color:#6366f1;color:white"
         href="'.SITE_NAME.'/verify.php?otp='.$otp.'&id='.$id.'">Activate Account</a></div>';
        
       
        $body_1 .= '</div>'; 
        send_email($to1,$subject_1,$fullName_1,$body_1,new PHPMailer());
                    

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $pdo->lastInsertId();
;
            
                        
        if ($referererUserName != '') {
            $queryUser = 'SELECT * FROM user WHERE userName = :referererUserName LIMIT 1';
            $stmtUser = $pdo->prepare($queryUser);
            $stmtUser->bindParam(':referererUserName',$referererUserName);
            $stmtUser->execute();
            $rowUser = $stmtUser->fetch();  

        if ($rowUser) {
            $sqlRef = 'INSERT INTO referral ( userId) VALUES (:userId)';
            $stmtRef = $pdo->prepare($sqlRef);
            $stmtRef->execute(['userId' => $rowUser -> id]);    
            
            
            $to2 = $rowUser -> email;
            $subject_2 = 'You have new referral';
            $fullName_2 = $rowUser -> fullName;           
            $body_2 = '<div style="background:black;color:white;padding:15px">'; 
            $body_2 .= '<p style="margin:10px 0"><img style="height:50px" src="https://starnetweb.com/assets/img/logo.png" alt=""></p>';
            $body_2 .= '<h2>You have new referral</h2>';  
            $body_2 .= '<p> Name : '.$fullName_2.'</p>';            
            $body_2 .= '<p>Email : '.$email.'</p>';    
            $body_1 .= '</div>';   
            send_email($to2,$subject_2,$fullName_2,$body_2,new PHPMailer());
            }               
            
            
            
     }
        


                         echo'<script>
                            setTimeout(function() {
                            window.location.href = "login";
                            }, 200);
                            </script>';

               
               


    
    }


         

  
    
   
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Boost | Register</title>
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
                <p class="text-sm opacity-75">Have an account? <a href="login"
                        class="font-semibold underline hover:opacity-90">Login here</a></p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Register an Account</h2>
                    <p class="text-gray-600">Enter your credentials to set up your account</p>
                </div>

                <form id="registerForm" class="space-y-6" method="POST">
                    <p class="text-red-500 text-sm">
                        <?php echo $error; ?>
                    </p>
                    <div>
                        <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">User Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                            <input type="text" id="userName" name="userName" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="username">
                        </div>
                    </div>
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
                        <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                            <input type="text" id="fullName" name="fullName" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="full name">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-blue-500"></i>
                            </div>
                            <input type="text" id="phone" name="phone" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="phone">
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

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                            Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-blue-500"></i>
                            </div>
                            <input type="password" id="confirmPassword" name="confirmPassword" required
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="••••••••">
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute right-3 top-3 text-gray-400 hover:text-blue-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="referererUserName" class="block text-sm font-medium text-gray-700 mb-1">Referrer's
                            Username (Optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                            <input type="text" id="referererUserName" name="referererUserName" readonly
                                class="pl-10 input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="referrer's Username" value="<?php echo $ref;?>">
                        </div>
                    </div>



                    <button type="submit" name="submit"
                        class="w-full btn-hover flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 transform">
                        Register
                    </button>

                    <!-- Login Link -->
                    <p class="mt-4 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="login"
                            class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                            Login
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

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                '<i class="fas fa-eye-slash"></i>';
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                '<i class="fas fa-eye-slash"></i>';
        });

        // Form submission
        const registerForm = document.getElementById('registerForm');

        registerForm.addEventListener('submit', function(e) {


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