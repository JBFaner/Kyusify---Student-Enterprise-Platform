<?php
$files = [];
function find_blade_files($dir) {
    global $files;
    foreach (scandir($dir) as $f) {
        if ($f == '.' || $f == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $f;
        if (is_dir($path)) find_blade_files($path);
        else if (str_ends_with($path, '.blade.php')) $files[] = $path;
    }
}
find_blade_files('resources/views');

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    // 1. replace Storage::url($var) with \App\Helpers\ImageHelper::url($var)
    $content = preg_replace('/Storage::url\(([^)]+)\)/', '\App\Helpers\ImageHelper::url($1)', $content, -1, $c1);
    
    // 2. replace asset('storage/' . $var) with \App\Helpers\ImageHelper::url($var)
    $content = preg_replace('/asset\(\'storage\/\'\s*\.\s*([^)]+)\)/', '\App\Helpers\ImageHelper::url($1)', $content, -1, $c2);
    
    // 3. replace asset("storage/" . $var)
    $content = preg_replace('/asset\(\"storage\/\"\s*\.\s*([^)]+)\)/', '\App\Helpers\ImageHelper::url($1)', $content, -1, $c3);
    
    if ($c1 > 0 || $c2 > 0 || $c3 > 0) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
        $count++;
    }
}
echo "Finished updating $count files.\n";
