<x-admin-layout>
    <x-slot name="header">
        Enterprise Management - Edit
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.enterprises.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-800 text-gray-500 hover:text-violet-600 dark:text-gray-400 dark:hover:text-violet-400 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Enterprise Info</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Modifying details for {{ $enterprise->name }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#13111C] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
        <div class="p-8">
            <form action="{{ route('admin.enterprises.update', $enterprise) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Details -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enterprise Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $enterprise->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="5" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-gray-50 dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow resize-none @error('description') border-red-500 @enderror">{{ old('description', $enterprise->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column: Settings and Admin Overrides -->
                    <div class="space-y-6">
                        <div class="p-6 border border-gray-100 dark:border-gray-800 rounded-xl bg-gray-50/50 dark:bg-gray-900/30">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Administration</h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enterprise Status <span class="text-red-500">*</span></label>
                                    <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-[#0B0A0F] text-gray-900 dark:text-white transition-shadow">
                                        <option value="pending" @selected(old('status', $enterprise->status) == 'pending')>Pending Review</option>
                                        <option value="approved" @selected(old('status', $enterprise->status) == 'approved')>Approved System-wide</option>
                                        <option value="rejected" @selected(old('status', $enterprise->status) == 'rejected')>Rejected</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <label class="flex items-center p-3 sm:p-4 bg-white dark:bg-[#0B0A0F] rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-violet-300 dark:hover:border-violet-800 transition-colors">
                                    <input type="checkbox" name="is_student_verified" value="1" class="w-5 h-5 text-violet-600 border-gray-300 rounded focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ old('is_student_verified', $enterprise->is_student_verified) ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">Student Identity Verified</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">They have provided QCU proof of enrollment.</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end space-x-3">
                    <a href="{{ route('admin.enterprises.index') }}" class="px-5 py-2.5 bg-white dark:bg-[#13111C] border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-sm font-medium shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg shadow-violet-500/30">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
