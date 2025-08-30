<!-- Phone Bar -->
<div class="fixed top-0 left-0 right-0 z-30 navbar-transition bg-transition" id="phone-bar">
    <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center">
            <!-- Email on the left -->
            <div class="flex items-center">
                <i class="fas fa-envelope mr-2 text-transition" id="email-icon"></i>
                <span class="font-bold text-transition" id="email-text">support@agentbookr.com</span>
            </div>
            <!-- Phone on the right -->
            <div class="flex items-center">
                <i class="fas fa-phone mr-2 text-transition" id="phone-icon"></i>
                <span class="font-bold text-transition" id="phone-text">1-800-AGENT-BKR</span>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-20 navbar-transition bg-transition" id="main-header" style="margin-top: 40px;">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-transition" id="logo-text">Agent Bookr</a>
            </div>
            
            <!-- Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <div class="flex items-center space-x-6">
                    <div class="dropdown relative">
                        <a href="#" class="text-transition flex items-center nav-link text-white">
                            Solutions
                            <i class="fas fa-chevron-down ml-1 text-xs text-white"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-64 bg-white shadow-lg rounded-lg py-2 z-50">
                            <div class="grid grid-cols-1 gap-1">
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Lead Generation</a>
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Cold Calling Scripts</a>
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Tutorials</a>
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">CRM Integration</a>
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Analytics</a>
                                <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Training</a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="text-transition nav-link text-white">Pricing</a>
                    <a href="#" class="text-transition nav-link text-white">Resources</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-transition nav-link text-white">Dashboard</a>
                    @endauth
                    <div class="dropdown relative">
                        <a href="#" class="text-transition flex items-center nav-link text-white">
                            About
                            <i class="fas fa-chevron-down ml-1 text-xs text-white"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 z-50">
                            <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Our Story</a>
                            <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Success Stories</a>
                            <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#CAD2C5] hover:text-[#52796F]">Team</a>
                            <a href="#" class="block px-4 py-2 text-[#2F3E46] hover:bg-[#52796F]">Careers</a>
                        </div>
                    </div>
                    <a href="#" class="text-transition nav-link text-white">Blog</a>
                    <a href="#" class="text-transition nav-link text-white">Support</a>
                </div>
                
                <!-- Authentication Buttons -->
                <div class="ml-6 flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="px-6 py-2 text-white font-semibold hover:text-[#52796F] transition duration-300">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-[#FFB703] text-[#2F3E46] font-semibold rounded-md hover:bg-[#FFB703]/90 transition duration-300">
                            Sign Up
                        </a>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-transition nav-link text-white font-medium hover:text-[#52796F] transition duration-300">{{ Auth::user()->name }}</a>
                            <span class="text-sm text-[#000000] bg-[#CAD2C5] px-2 py-1 rounded">{{ Auth::user()->getTierDisplayName() }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-transition nav-link text-white font-semibold hover:text-[#52796F] transition duration-300">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-transition text-white" id="mobile-menu-btn">
                <i class="fas fa-bars text-xl text-white"></i>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu (Hidden by default) -->
<div class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden" id="mobile-menu">
    <div class="bg-white h-full w-80 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <h2 class="text-xl font-bold text-black">Agent Bookr</h2>
            </div>
            <button class="text-black" onclick="toggleMobileMenu()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <nav class="space-y-4">
            <div class="border-b border-gray-200 pb-4">
                <a href="#" class="block text-black hover:text-[#52796F] transition-colors font-semibold">Solutions</a>
                <div class="ml-4 mt-2 space-y-2">
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Lead Generation</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Cold Calling Scripts</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Tutorials</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">CRM Integration</a>
                </div>
            </div>
            <a href="#" class="block text-black hover:text-[#52796F] transition-colors">Pricing</a>
            <a href="#" class="block text-black hover:text-[#52796F] transition-colors">Resources</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block text-black hover:text-[#52796F] transition-colors">Dashboard</a>
            @endauth
            <div class="border-b border-gray-200 pb-4">
                <a href="#" class="block text-black hover:text-[#52796F] transition-colors font-semibold">About</a>
                <div class="ml-4 mt-2 space-y-2">
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Our Story</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Success Stories</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Team</a>
                    <a href="#" class="block text-gray-600 hover:text-[#52796F] transition-colors">Careers</a>
                </div>
            </div>
            <a href="#" class="block text-black hover:text-[#52796F] transition-colors">Blog</a>
            <a href="#" class="block text-black hover:text-[#52796F] transition-colors">Support</a>
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center">
                    <i class="fas fa-phone mr-2 text-black"></i>
                    <span class="font-bold text-black">1-800-AGENT-BKR</span>
                </div>
            </div>
        </nav>
    </div>
</div>
