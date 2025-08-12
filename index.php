<?php
    include_once 'inc/database.php';

    $sql = 'SELECT * FROM service';
        
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialBoost - Premium Social Media Services</title>
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
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-rocket text-2xl text-primary"></i>
                <h1 class="text-xl font-bold text-dark">SocialBoost</h1>
            </div>
            
            <nav class="hidden md:flex space-x-8">
                <a href="#" class="text-dark hover:text-primary font-medium">Home</a>
                <div class="dropdown relative">
                    <button class="text-dark hover:text-primary font-medium flex items-center">
                        Services <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="dropdown-menu absolute hidden bg-white mt-2 py-2 w-48 rounded-md shadow-lg z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">App Reviews</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Social Followers</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Website Traffic</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">YouTube Services</a>
                    </div>
                </div>
                <a href="#" class="text-dark hover:text-primary font-medium">Pricing</a>
                <a href="#" class="text-dark hover:text-primary font-medium">FAQ</a>
                <a href="#" class="text-dark hover:text-primary font-medium">Contact</a>
            </nav>
            
            <div class="flex items-center space-x-4">
                <button class="hidden md:block bg-secondary hover:bg-primary text-white px-4 py-2 rounded-md font-medium transition" onclick="window.location.href='login';">
                    Login
                </button>
                <button class="hidden md:block bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="window.location.href='register-advertiser';">
                    Create Advertiser Account
                </button>
                <button class="hidden md:block bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md font-medium transition" onclick="window.location.href='register-earner';">
                    Create Earner Account
                </button>
                <button class="md:hidden text-dark">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" 
                 alt="Social media connections" 
                 class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Boost Your Social Presence</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">High-quality social media services for apps, websites, and platforms. Real results, fast delivery.</p>
            
            <div class="max-w-md mx-auto relative">
                <input type="text" placeholder="Search services..." class="w-full py-4 px-6 rounded-full text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                <button class="absolute right-2 top-2 bg-primary hover:bg-secondary text-white py-2 px-6 rounded-full font-medium">
                    Search
                </button>
            </div>
            
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>100% Safe</span>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3 flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    <span>Instant Delivery</span>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3 flex items-center">
                    <i class="fas fa-shield-alt mr-2"></i>
                    <span>Guaranteed Results</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Popular Services</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Choose from our most popular social media services to boost your online presence</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-6 mb-8">
                <button class="px-4 py-2 bg-primary text-white rounded-full font-medium" onclick="filterServices('all')">All Services</button>
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full font-medium" onclick="filterServices('reviews')">App Reviews</button>
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full font-medium" onclick="filterServices('followers')">Followers</button>
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full font-medium" onclick="filterServices('likes')">Likes</button>
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full font-medium" onclick="filterServices('views')">Views</button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Service Card -->
                 <?php foreach ($rows as $row):?>
                  
                <div class="service-card bg-white rounded-xl shadow-md overflow-hidden transition duration-300 cursor-pointer" onclick="window.location.href='check-out?service=<?php echo $row -> title; ?>'" >
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
            
            <div class="text-center mt-12">
                <button class="px-6 py-3 bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-md font-medium transition">
                    View All Services (50+)
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50 relative">
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                 alt="Abstract background" 
                 class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Why Choose SocialBoost?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We provide the highest quality social media services with guaranteed results</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bolt text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Fast Delivery</h3>
                    <p class="text-gray-600">Most services start within minutes of your order. No waiting for days to see results.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">100% Safe</h3>
                    <p class="text-gray-600">Our methods are completely safe and won't get your accounts banned or penalized.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Our support team is available around the clock to assist you with any questions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">What Our Clients Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Don't just take our word for it - hear from some of our satisfied customers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                 alt="John D." 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-dark">John D.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"The Google Play reviews I purchased helped my app jump from page 5 to page 2 in just 3 days. Incredible service!"</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                                 alt="Sarah M." 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-dark">Sarah M.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"I was skeptical at first, but the Instagram followers I got were real accounts that actually engage with my posts."</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" 
                                 alt="Alex R." 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-dark">Alex R.</h4>
                            <div class="flex text-yellow-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"The YouTube views service helped my video get recommended more often. My organic views increased by 300% after using SocialBoost."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Boost Your Social Presence?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Join thousands of satisfied customers who have grown their online presence with our services.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button class="bg-white text-primary hover:bg-gray-100 px-8 py-4 rounded-md font-bold text-lg transition">
                    Get Started Now
                </button>
                <button class="bg-transparent border-2 border-white hover:bg-white hover:bg-opacity-10 px-8 py-4 rounded-md font-bold text-lg transition">
                    Learn More
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-rocket text-2xl text-primary"></i>
                        <h3 class="text-xl font-bold">SocialBoost</h3>
                    </div>
                    <p class="text-gray-400 mb-4">Premium social media services to help you grow your online presence.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">App Reviews</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Social Followers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Website Traffic</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">YouTube Services</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">All Services</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Refund Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; 2023 SocialBoost. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Service filtering function
        function filterServices(category) {
            const services = document.querySelectorAll('.service-card');
            services.forEach(service => {
                if (category === 'all') {
                    service.style.display = 'block';
                } else {
                    const serviceType = service.getAttribute('data-category');
                    if (serviceType.includes(category)) {
                        service.style.display = 'block';
                    } else {
                        service.style.display = 'none';
                    }
                }
            });
        }

        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.md\\:hidden');
            const mobileMenu = document.querySelector('nav.md\\:flex');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('block');
                mobileMenu.classList.toggle('absolute');
                mobileMenu.classList.toggle('bg-white');
                mobileMenu.classList.toggle('w-full');
                mobileMenu.classList.toggle('left-0');
                mobileMenu.classList.toggle('p-4');
                mobileMenu.classList.toggle('shadow-lg');
                mobileMenu.classList.toggle('z-40');
            });
            
            // Service card hover effect
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>