<x-seller-layout>
    <x-slot name="header">
        Create your store
    </x-slot>

    <div class="max-w-xl">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-2">Set up your enterprise</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
            Your seller account does not have a store profile yet. Enter your business or brand name to continue. You can add details and verification documents on the next step.
        </p>

        @if (session('info'))
            <div class="mb-6 p-4 rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800/40 text-sm text-violet-800 dark:text-violet-200">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('seller.profile.enterprise.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Business / store name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    name="business_name"
                    id="business_name"
                    value="{{ old('business_name') }}"
                    required
                    maxlength="255"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0A0F] text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-shadow"
                    placeholder="e.g. Campus Crafts Co."
                />
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 text-white font-semibold shadow-lg shadow-violet-500/25 transition-all">
                Create store profile
            </button>
        </form>
    </div>
</x-seller-layout>
