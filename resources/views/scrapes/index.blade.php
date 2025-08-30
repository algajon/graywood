@extends('layouts.app')

@section('title', 'Lead Generation - Agent Bookr')

@section('content')
    <!-- Lead Generation Section -->
    <section class="min-h-screen bg-[#2F3E46]" style="padding-top: 120px;">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4">Lead Generation Tool</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Generate high-quality real estate leads with our advanced scraping technology. 
                    Start building your prospect list today.
                </p>
            </div>

            <!-- Lead Generation Form -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-[#ffffff] px-8 py-6 text-center">
                        <h2 class="text-2xl font-bold text-[#2F3E46] mb-2">Start Lead Generation</h2>
                        <p class="text-[#2F3E46] text-lg">Configure your search parameters</p>
                    </div>
                    
                    <!-- Form Content -->
                    <div class="px-8 py-8">
                        <form method="POST" action="{{ route('scrapes.start') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Search URL Field -->
                            <div>
                                <label for="base_url" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Search URL *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-link text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        name="base_url" 
                                        type="url" 
                                        required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                        placeholder="https://www.kijiji.ca/b-real-estate/..."
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    Enter the Kijiji search URL for the area and property type you want to target
                                </p>
                            </div>

                            <!-- Max Listings Field -->
                            <div>
                                <label for="max_listings" class="block text-sm font-semibold text-[#2F3E46] mb-2">
                                    Maximum Listings
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-list-ol text-[#52796F]"></i>
                                    </div>
                                    <input 
                                        name="max_listings" 
                                        type="number" 
                                        min="1" 
                                        max="1000" 
                                        value="50" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFB703] focus:border-transparent transition-all duration-200"
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    Choose how many listings to scrape (1-1000). Higher numbers may take longer to process.
                                </p>
                            </div>

                            <!-- User Tier Information -->
                            <div class="bg-[#CAD2C5] rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-info-circle text-[#52796F] mr-2"></i>
                                    <span class="font-semibold text-[#2F3E46]">Your Plan: {{ $user->getTierDisplayName() }}</span>
                                </div>
                                <p class="text-sm text-[#2F3E46]">
                                    @if($user->tier === 'user')
                                        You can generate up to 500 leads per month with your Starter plan.
                                    @elseif($user->tier === 'paid')
                                        You can generate up to 2,000 leads per month with your Professional plan.
                                    @else
                                        You have unlimited lead generation with your Administrator plan.
                                    @endif
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-[#FFB703] text-[#2F3E46] py-4 px-8 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 transform hover:scale-105 shadow-lg text-lg"
                            >
                                <i class="fas fa-search mr-2"></i>
                                Start Lead Generation
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">Pro Tips for Better Results</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <div class="p-2 bg-[#FFB703] rounded-lg mr-3 mt-1">
                                <i class="fas fa-lightbulb text-[#2F3E46]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-[#2F3E46]">Target Specific Areas</h4>
                                <p class="text-sm text-gray-600">Focus on neighborhoods with high property turnover for better lead quality.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 bg-[#52796F] rounded-lg mr-3 mt-1">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-[#2F3E46]">Optimal Timing</h4>
                                <p class="text-sm text-gray-600">Run searches during business hours for the most up-to-date listings.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 bg-[#2F3E46] rounded-lg mr-3 mt-1">
                                <i class="fas fa-filter text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-[#2F3E46]">Use Filters</h4>
                                <p class="text-sm text-gray-600">Apply price and property type filters to target your ideal prospects.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 bg-[#CAD2C5] rounded-lg mr-3 mt-1">
                                <i class="fas fa-download text-[#2F3E46]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-[#2F3E46]">Export Results</h4>
                                <p class="text-sm text-gray-600">Download your leads as CSV for easy import into your CRM system.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
