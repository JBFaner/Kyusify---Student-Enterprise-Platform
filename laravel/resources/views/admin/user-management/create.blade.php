<x-admin-layout>
    <x-slot name="header">
        User Management - Create
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-800 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Add New User</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a new account manually</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Basic Info -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror" placeholder="John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('email') border-red-500 @enderror" placeholder="john@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role <span class="text-red-500">*</span></label>
                                <select name="role" id="role" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow">
                                    <option value="customer" @selected(old('role') == 'customer')>Customer</option>
                                    <option value="seller" @selected(old('role') == 'seller')>Seller</option>
                                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow">
                                    <option value="active" @selected(old('status') == 'active')>Active</option>
                                    <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                                    <option value="inactive" @selected(old('status') == 'inactive')>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Role/Status & Password -->
                    <div class="space-y-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('password') border-red-500 @enderror" placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-sm font-medium shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg shadow-violet-500/30">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
