@extends('layouts.app')

@section('title', 'Register - Agent Bookr')

@section('content')
    <!-- Registration Section -->
    <section class="min-h-screen flex items-center justify-center relative" style="padding-top: 120px; background: linear-gradient(135deg, #2F3E46 0%, #52796F 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, #333 0px, #333 2px, transparent 2px, transparent 8px), repeating-linear-gradient(-45deg, #333 0px, #333 2px, transparent 2px, transparent 8px); background-size: 16px 16px;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-2xl mx-auto">
                <!-- Registration Card -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-[#ffffff] px-8 py-6 text-center">
                        <h1 class="text-3xl font-bold text-[#2F3E46] mb-2">Join Agent Bookr</h1>
                        <p class="text-[#2F3E46] text-lg">Start generating more leads today</p>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="px-8 py-8">
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Full Name *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Enter your full name"
                                        required
                                    >
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Email Address *
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

                            <!-- Company Field -->
                            <div>
                                <label for="company" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Company Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="company" 
                                        name="company" 
                                        value="{{ old('company') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Enter your company name"
                                    >
                                </div>
                                @error('company')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Phone Number
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Enter your phone number"
                                    >
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subscription Tier Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-[#2F3E46] mb-4">
                                    Choose Your Plan *
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Starter Plan -->
                                    <label class="relative cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="subscription_tier" 
                                            value="user" 
                                            class="sr-only"
                                            {{ old('subscription_tier') === 'user' ? 'checked' : '' }}
                                            required
                                        >
                                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-[#FFB703] transition-all duration-200">
                                            <div class="text-center">
                                                <h3 class="font-semibold text-[#2F3E46]">Starter</h3>
                                                <p class="text-sm text-gray-600">Free</p>
                                                <ul class="text-xs text-gray-500 mt-2 space-y-1">
                                                    <li>• 500 leads/month</li>
                                                    <li>• Basic scripts</li>
                                                    <li>• Email support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Professional Plan -->
                                    <label class="relative cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="subscription_tier" 
                                            value="paid" 
                                            class="sr-only"
                                            {{ old('subscription_tier') === 'paid' ? 'checked' : '' }}
                                        >
                                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-[#FFB703] transition-all duration-200">
                                            <div class="text-center">
                                                <h3 class="font-semibold text-[#2F3E46]">Professional</h3>
                                                <p class="text-sm text-gray-600">$79/month</p>
                                                <ul class="text-xs text-gray-500 mt-2 space-y-1">
                                                    <li>• 2,000 leads/month</li>
                                                    <li>• Advanced scripts</li>
                                                    <li>• Priority support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Admin Plan (Hidden by default) -->
                                    <label class="relative cursor-pointer hidden">
                                        <input 
                                            type="radio" 
                                            name="subscription_tier" 
                                            value="admin" 
                                            class="sr-only"
                                            {{ old('subscription_tier') === 'admin' ? 'checked' : '' }}
                                        >
                                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-[#FFB703] transition-all duration-200">
                                            <div class="text-center">
                                                <h3 class="font-semibold text-[#2F3E46]">Admin</h3>
                                                <p class="text-sm text-gray-600">Enterprise</p>
                                                <ul class="text-xs text-gray-500 mt-2 space-y-1">
                                                    <li>• Unlimited access</li>
                                                    <li>• Full control</li>
                                                    <li>• 24/7 support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('subscription_tier')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Password *
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
                                        placeholder="Create a password (min. 8 characters)"
                                        required
                                        autocomplete="new-password"
                                    >
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Confirm Password *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="Confirm your password"
                                        required
                                        autocomplete="new-password"
                                    >
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    id="terms" 
                                    name="terms" 
                                    class="h-4 w-4 text-[#FFB703] focus:ring-[#FFB703] border-gray-300 rounded mt-1"
                                    required
                                >
                                <label for="terms" class="ml-2 block text-sm text-[#2F3E46]">
                                    I agree to the 
                                    <a href="#" class="text-[#FFB703] hover:text-[#FFB703]/80">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-[#FFB703] hover:text-[#FFB703]/80">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Register Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 transform hover:scale-105 shadow-lg"
                            >
                                <i class="fas fa-user-plus mr-2"></i>
                                Create Account
                            </button>
                        </form>
                        
                        <!-- Login Link -->
                        <div class="mt-8 text-center">
                            <p class="text-[#2F3E46]">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-[#FFB703] hover:text-[#FFB703]/80 font-semibold transition-colors">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
