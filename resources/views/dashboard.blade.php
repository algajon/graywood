@extends('layouts.app')

@section('title', 'Dashboard - Agent Bookr')

@section('content')
    <!-- Dashboard Section -->
    <section class="min-h-screen bg-[#2F3E46]" style="padding-top: 120px;">
        <div class="container mx-auto px-4 py-8">
            <!-- Welcome Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-[#2F3E46] mb-2">Welcome back, {{ $user->name }}!</h1>
                        <p class="text-gray-600">Manage your lead generation and cold calling tools</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-[#FFB703] text-[#2F3E46] px-4 py-2 rounded-lg font-semibold">
                            {{ $user->getTierDisplayName() }} Plan
                        </span>
                        @if($user->hasActiveSubscription())
                            <p class="text-sm text-green-600 mt-4">✓ Active Subscription</p>
                        @else
                            <p class="text-sm text-red-600 mt-4">⚠ Subscription Expired</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#FFB703] rounded-lg">
                            <i class="fas fa-chart-line text-[#2F3E46] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Leads</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">
                                @if($user->tier === 'user') 500 @elseif($user->tier === 'paid') 2,000 @else Unlimited @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#52796F] rounded-lg">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Scripts Available</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">
                                @if($user->tier === 'user') Basic @elseif($user->tier === 'paid') Advanced @else All @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#2F3E46] rounded-lg">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Training Access</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">
                                @if($user->tier === 'user') Limited @elseif($user->tier === 'paid') Full @else Complete @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#CAD2C5] rounded-lg">
                            <i class="fas fa-headset text-[#2F3E46] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Support Level</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">
                                @if($user->tier === 'user') Email @elseif($user->tier === 'paid') Priority @else 24/7 @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Lead Generation -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">Lead Generation</h3>
                    <p class="text-gray-600 mb-6">Generate high-quality leads with our advanced targeting tools.</p>
                    <div class="space-y-3">
                        <a href="{{ route('scrapes.index') }}" class="block w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 text-center">
                            <i class="fas fa-search mr-2"></i>
                            Start Lead Generation
                        </a>
                        <a href="{{ route('scrapes.index') }}" class="block w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 text-center">
                            <i class="fas fa-history mr-2"></i>
                            View Recent Runs
                        </a>
                        <a href="#" class="block w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 text-center">
                            <i class="fas fa-download mr-2"></i>
                            Download Lead Lists
                        </a>
                    </div>
                </div>

                <!-- Cold Calling Tools -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">Cold Calling Tools</h3>
                    <p class="text-gray-600 mb-6">Access proven scripts and training materials to improve your success rate.</p>
                    <div class="space-y-3">
                        <a href="#" class="block w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 text-center">
                            <i class="fas fa-file-alt mr-2"></i>
                            View Scripts
                        </a>
                        <a href="#" class="block w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 text-center">
                            <i class="fas fa-play mr-2"></i>
                            Watch Tutorials
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-[#FFB703] rounded-lg mr-4">
                            <i class="fas fa-search text-[#2F3E46]"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-[#2F3E46]">Lead Generation Started</p>
                            <p class="text-sm text-gray-600">Searching for properties in Austin, TX</p>
                        </div>
                        <span class="text-sm text-gray-500">2 hours ago</span>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-[#52796F] rounded-lg mr-4">
                            <i class="fas fa-download text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-[#2F3E46]">Lead List Downloaded</p>
                            <p class="text-sm text-gray-600">150 new leads added to your database</p>
                        </div>
                        <span class="text-sm text-gray-500">1 day ago</span>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-[#2F3E46] rounded-lg mr-4">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-[#2F3E46]">Training Completed</p>
                            <p class="text-sm text-gray-600">Advanced Cold Calling Techniques</p>
                        </div>
                        <span class="text-sm text-gray-500">3 days ago</span>
                    </div>
                </div>
            </div>

            <!-- Upgrade CTA (for free users) -->
            @if($user->tier === 'user')
            <div class="bg-gradient-to-r from-[#FFB703] to-[#FFB703]/80 rounded-lg shadow-md p-8 mt-8 text-center">
                <h3 class="text-2xl font-bold text-[#2F3E46] mb-4">Ready to Scale Your Business?</h3>
                <p class="text-[#2F3E46] text-lg mb-6">Upgrade to Professional and get access to unlimited leads, advanced scripts, and priority support.</p>
                <a href="#" class="inline-block bg-[#2F3E46] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#2F3E46]/90 transition-all duration-200">
                    <i class="fas fa-rocket mr-2"></i>
                    Upgrade to Professional
                </a>
            </div>
            @endif
        </div>
    </section>
@endsection
