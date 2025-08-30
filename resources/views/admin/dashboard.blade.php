@extends('layouts.app')

@section('title', 'Admin Dashboard - Agent Bookr')

@section('content')
    <!-- Admin Dashboard Section -->
    <section class="min-h-screen bg-gray-50" style="padding-top: 120px;">
        <div class="container mx-auto px-4 py-8">
            <!-- Admin Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-[#2F3E46] mb-2">Admin Dashboard</h1>
                        <p class="text-gray-600">Manage users, subscriptions, and system settings</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">
                            Administrator Access
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Full system control</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#FFB703] rounded-lg">
                            <i class="fas fa-users text-[#2F3E46] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">{{ $users->count() + 1 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#52796F] rounded-lg">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Paid Users</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">{{ $users->where('tier', 'paid')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#2F3E46] rounded-lg">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Free Users</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">{{ $users->where('tier', 'user')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#CAD2C5] rounded-lg">
                            <i class="fas fa-chart-line text-[#2F3E46] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Revenue</p>
                            <p class="text-2xl font-semibold text-[#2F3E46]">${{ number_format($users->where('tier', 'paid')->count() * 79) }}/mo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46]">User Management</h3>
                    <button class="bg-[#FFB703] text-[#2F3E46] px-4 py-2 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add User
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-[#FFB703] flex items-center justify-center">
                                                <span class="text-[#2F3E46] font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-[#2F3E46]">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($user->tier === 'paid') bg-green-100 text-green-800 
                                        @elseif($user->tier === 'user') bg-gray-100 text-gray-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $user->getTierDisplayName() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->hasActiveSubscription())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-[#52796F] hover:text-[#2F3E46] transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-[#FFB703] hover:text-[#2F3E46] transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- System Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- System Settings -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">System Settings</h3>
                    <div class="space-y-4">
                        <button class="w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 text-left">
                            <i class="fas fa-cog mr-2"></i>
                            General Settings
                        </button>
                        <button class="w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 text-left">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Security Settings
                        </button>
                        <button class="w-full bg-[#2F3E46] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#2F3E46]/90 transition-all duration-200 text-left">
                            <i class="fas fa-database mr-2"></i>
                            Database Management
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-[#2F3E46] mb-4">Quick Actions</h3>
                    <div class="space-y-4">
                        <button class="w-full bg-[#FFB703] text-[#2F3E46] py-3 px-6 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200 text-left">
                            <i class="fas fa-download mr-2"></i>
                            Export User Data
                        </button>
                        <button class="w-full bg-[#52796F] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#52796F]/90 transition-all duration-200 text-left">
                            <i class="fas fa-chart-bar mr-2"></i>
                            View Analytics
                        </button>
                        <button class="w-full bg-[#2F3E46] text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#2F3E46]/90 transition-all duration-200 text-left">
                            <i class="fas fa-bell mr-2"></i>
                            System Notifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
