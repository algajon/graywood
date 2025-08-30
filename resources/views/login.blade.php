@extends('layouts.app')

@section('title', 'Login - Agent Bookr')

@section('content')
    <!-- Login Section -->
    <section class="min-h-screen flex items-center justify-center relative" style="padding-top: 120px; background: linear-gradient(135deg, #2F3E46 0%, #52796F 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, #333 0px, #333 2px, transparent 2px, transparent 8px), repeating-linear-gradient(-45deg, #333 0px, #333 2px, transparent 2px, transparent 8px); background-size: 16px 16px;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-md mx-auto">
                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-[#ffffff] px-8 py-6 text-center">
                        <h1 class="text-3xl font-bold text-[#2F3E46] mb-2">Welcome Back</h1>
                        <p class="text-[#2F3E46] text-lg">Sign in to your Agent Bookr account</p>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="px-8 py-8">
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Enter your email address"
                                        required
                                        autocomplete="email"
                                    >
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Password Field -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                    >
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="remember" 
                                        name="remember" 
                                        class="h-4 w-4 text-[#FFB703] focus:ring-[#FFB703] border-gray-300 rounded"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    <label for="remember" class="ml-2 block text-sm text-[#2F3E46]">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="text-sm text-[#52796F] hover:text-[#2F3E46] transition-colors">
                                    Forgot password?
                                </a>
                            </div>
                            
                            <!-- Login Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 transform hover:scale-105 shadow-lg"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="my-6 flex items-center">
                            <div class="flex-1 border-t border-gray-300"></div>
                            <span class="px-4 text-sm text-gray-500">or</span>
                            <div class="flex-1 border-t border-gray-300"></div>
                        </div>
                        
                        <!-- Social Login Options -->
                        <div class="space-y-3">
                            <button class="w-full bg-[#2F3E46] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#2F3E46]/90 transition-all duration-200 flex items-center justify-center">
                                <i class="fab fa-google mr-2"></i>
                                Continue with Google
                            </button>
                            <button class="w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 flex items-center justify-center">
                                <i class="fab fa-linkedin mr-2"></i>
                                Continue with LinkedIn
                            </button>
                        </div>
                        
                        <!-- Sign Up Link -->
                        <div class="mt-8 text-center">
                            <p class="text-[#2F3E46]">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-[#FFB703] hover:text-[#FFB703]/80 font-semibold transition-colors">
                                    Sign up for free
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <!-- <div class="mt-8 text-center text-white">
                    <h3 class="text-xl font-semibold mb-3">Why Choose Agent Bookr?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-chart-line text-[#FFB703] mr-2"></i>
                            <span>Generate More Leads</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-phone text-[#FFB703] mr-2"></i>
                            <span>Proven Scripts</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-[#FFB703] mr-2"></i>
                            <span>Expert Training</span>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
@endsection
