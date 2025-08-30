<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agent Bookr - Lead Generation & Cold Calling Solutions for Real Estate Agents')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/hero-image.png');
            background-size: cover;
            background-position: center 30%;
            background-repeat: no-repeat;
            min-height: 100vh;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        .navbar-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.1);
        }
        .navbar-transition {
            transition: all 0.5s ease-in-out;
        }
        .text-transition {
            transition: color 0.5s ease-in-out;
        }
        .bg-transition {
            transition: background-color 0.5s ease-in-out, box-shadow 0.5s ease-in-out;
        }
        .service-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
        }
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-white">
    @include('include.header')
    
    @yield('content')
    
    @include('include.footer')

    <script>
        let hasScrolled = false;
        let isAnimating = false;

        // Initialize navbar styles
        function initializeNavbar() {
            const phoneBar = document.getElementById('phone-bar');
            const mainHeader = document.getElementById('main-header');
            const emailIcon = document.getElementById('email-icon');
            const emailText = document.getElementById('email-text');
            const phoneIcon = document.getElementById('phone-icon');
            const phoneText = document.getElementById('phone-text');
            const logoText = document.getElementById('logo-text');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');

            // Set initial styles (transparent navbar with white text)
            phoneBar.classList.add('navbar-blur', 'bg-black');
            mainHeader.classList.add('navbar-blur');
            emailIcon.classList.add('text-white');
            emailText.classList.add('text-white');
            phoneIcon.classList.add('text-white');
            phoneText.classList.add('text-white');
            logoText.classList.add('text-white');
            navLinks.forEach(link => {
                link.classList.add('text-white');
                link.addEventListener('mouseenter', () => {
                    if (!hasScrolled) {
                        link.classList.add('hover:text-gray-300');
                    } else {
                        link.classList.add('hover:text-blue-500');
                    }
                });
                link.addEventListener('mouseleave', () => {
                    if (!hasScrolled) {
                        link.classList.remove('hover:text-gray-300');
                    } else {
                        link.classList.remove('hover:text-blue-500');
                    }
                });
            });
            mobileMenuBtn.classList.add('text-white');
        }

        // Handle scroll functionality with smooth animation
        function handleScroll() {
            if (isAnimating) return;

            const scrollPosition = window.pageYOffset;
            const triggerPoint = 50; // Trigger animation after 50px scroll

            if (scrollPosition > triggerPoint && !hasScrolled) {
                hasScrolled = true;
                isAnimating = true;
                animateToWhiteNavbar();
            } else if (scrollPosition <= triggerPoint && hasScrolled) {
                hasScrolled = false;
                isAnimating = true;
                animateToTransparentNavbar();
            }
        }

        function animateToWhiteNavbar() {
            const phoneBar = document.getElementById('phone-bar');
            const mainHeader = document.getElementById('main-header');
            const emailIcon = document.getElementById('email-icon');
            const emailText = document.getElementById('email-text');
            const phoneIcon = document.getElementById('phone-icon');
            const phoneText = document.getElementById('phone-text');
            const logoText = document.getElementById('logo-text');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');

            // Animate to white navbar with black text
            phoneBar.classList.remove('navbar-blur', 'bg-black');
            phoneBar.classList.add('bg-white', 'shadow-md');
            mainHeader.classList.remove('navbar-blur');
            mainHeader.classList.add('bg-white', 'shadow-md');
            
            // Animate text colors
            setTimeout(() => {
                emailIcon.classList.remove('text-white');
                emailIcon.classList.add('text-black');
                emailText.classList.remove('text-white');
                emailText.classList.add('text-black');
                phoneIcon.classList.remove('text-white');
                phoneIcon.classList.add('text-black');
                phoneText.classList.remove('text-white');
                phoneText.classList.add('text-black');
                logoText.classList.remove('text-white');
                logoText.classList.add('text-black');
                navLinks.forEach(link => {
                    link.classList.remove('text-white');
                    link.classList.add('text-black');
                });
                mobileMenuBtn.classList.remove('text-white');
                mobileMenuBtn.classList.add('text-black');
                
                setTimeout(() => {
                    isAnimating = false;
                }, 500);
            }, 250);
        }

        function animateToTransparentNavbar() {
            const phoneBar = document.getElementById('phone-bar');
            const mainHeader = document.getElementById('main-header');
            const emailIcon = document.getElementById('email-icon');
            const emailText = document.getElementById('email-text');
            const phoneIcon = document.getElementById('phone-icon');
            const phoneText = document.getElementById('phone-text');
            const logoText = document.getElementById('logo-text');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');

            // Animate text colors first
            emailIcon.classList.remove('text-black');
            emailIcon.classList.add('text-white');
            emailText.classList.remove('text-black');
            emailText.classList.add('text-white');
            phoneIcon.classList.remove('text-black');
            phoneIcon.classList.add('text-white');
            phoneText.classList.remove('text-black');
            phoneText.classList.add('text-white');
            logoText.classList.remove('text-black');
            logoText.classList.add('text-white');
            navLinks.forEach(link => {
                link.classList.remove('text-black');
                link.classList.add('text-white');
            });
            mobileMenuBtn.classList.remove('text-black');
            mobileMenuBtn.classList.add('text-white');

            // Then animate background
            setTimeout(() => {
                phoneBar.classList.remove('bg-white', 'shadow-md');
                phoneBar.classList.add('navbar-blur', 'bg-black');
                mainHeader.classList.remove('bg-white', 'shadow-md');
                mainHeader.classList.add('navbar-blur');
                
                setTimeout(() => {
                    isAnimating = false;
                }, 500);
            }, 250);
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = event.target.closest('button');
            
            if (!mobileMenu.contains(event.target) && !menuButton) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Initialize and add scroll listener
        document.addEventListener('DOMContentLoaded', function() {
            initializeNavbar();
            window.addEventListener('scroll', handleScroll);
        });
    </script>
    @yield('scripts')
</body>
</html>
