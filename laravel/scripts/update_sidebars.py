import re
import os

files_to_update = [
    'resources/views/components/admin-layout.blade.php',
    'resources/views/components/seller-layout.blade.php'
]

for filepath in files_to_update:
    if not os.path.exists(filepath):
        print(f"Skipping {filepath}, file not found.")
        continue
        
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Update sidebar aside tag
    content = re.sub(
        r'<aside :class="sidebarOpen \? \'w-64\' : \'w-\[80px\]\'" class="flex-shrink-0 bg-white dark:bg-\[#13111C\] border-r border-gray-100 dark:border-gray-800 transition-all duration-300 ease-in-out z-20 flex flex-col shadow-\[4px_0_24px_rgba\(0,0,0,0\.02\)\] dark:shadow-\[4px_0_24px_rgba\(0,0,0,0\.2\)\]">',
        '<aside :class="sidebarOpen ? \'w-64\' : \'w-[80px]\'" class="flex-shrink-0 bg-violet-950 border-r border-violet-900 transition-all duration-300 ease-in-out z-20 flex flex-col shadow-2xl">',
        content
    )
    
    # Update title area background colors to remove border
    content = re.sub(
        r'border-b border-gray-100 dark:border-gray-800/60',
        'border-b border-violet-900/50',
        content
    )
    
    # Update Kyusify text
    content = re.sub(
        r'<h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 tracking-tight">',
        '<h1 class="text-xl font-bold text-white tracking-tight">',
        content
    )
    
    # Update subtext under Kyusify
    content = re.sub(
        r'<p class="text-\[10px\] font-semibold tracking-widest uppercase text-violet-600 dark:text-violet-400">',
        '<p class="text-[10px] font-semibold tracking-widest uppercase text-violet-300">',
        content
    )
    
    # Update toggle button
    content = re.sub(
        r'class="p-2 rounded-xl text-gray-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:text-violet-400 dark:hover:bg-violet-500/10 focus:outline-none transition-all duration-200"',
        'class="p-2 rounded-xl text-violet-400 hover:text-white hover:bg-violet-800/50 focus:outline-none transition-all duration-200"',
        content
    )
    
    # Update Menu headers
    content = re.sub(
        r'class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2',
        'class="px-4 text-xs font-semibold text-violet-300/70 uppercase tracking-wider mb-2',
        content
    )
    
    # Update Active/Inactive Link conditional classes (A Tag)
    # Finding the pattern: 'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'
    content = re.sub(
        r'\'bg-gradient-to-r from-violet-600 to-violet-500 text-white shadow-lg shadow-violet-500/25\' : \'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white\'',
        "'bg-white text-violet-950 shadow-lg font-bold' : 'text-violet-200 hover:bg-white/10 hover:text-white'",
        content
    )
    
    # Non-route based links (Insights & support)
    content = re.sub(
        r'class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white"',
        'class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-violet-200 hover:bg-white/10 hover:text-white"',
        content
    )
    
    # Update Active/Inactive Icon classes (SVG Tag)
    # Finding: 'text-white' : 'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200'
    content = re.sub(
        r'\'text-white\' : \'text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200\'',
        "'text-violet-900' : 'text-violet-400 group-hover:text-white transition-colors duration-200'",
        content
    )
    
    # Non-route based icon classes
    content = re.sub(
        r'class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-violet-500 dark:text-gray-500 dark:group-hover:text-violet-400 transition-colors duration-200"',
        'class="w-5 h-5 flex-shrink-0 text-violet-400 group-hover:text-white transition-colors duration-200"',
        content
    )
    
    # Update Bottom Area (Logout)
    content = re.sub(
        r'border-t border-gray-100 dark:border-gray-800/60 transition-all duration-300"',
        'border-t border-violet-900/50 transition-all duration-300"',
        content
    )
    
    content = re.sub(
        r'class="w-full flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400"',
        'class="w-full flex items-center px-3 py-3 rounded-xl transition-all duration-200 group relative text-violet-200 hover:bg-red-500/20 hover:text-red-400"',
        content
    )
    
    content = re.sub(
        r'class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-red-500 dark:text-gray-500 dark:group-hover:text-red-400 transition-colors duration-200"',
        'class="w-5 h-5 flex-shrink-0 text-violet-400 group-hover:text-red-400 transition-colors duration-200"',
        content
    )
    
    # Change tooltips
    content = re.sub(
        r'class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50"',
        'class="absolute left-14 bg-white text-violet-950 font-semibold shadow-xl border border-violet-100 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50"',
        content
    )

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

    print(f"Updated {filepath}")
