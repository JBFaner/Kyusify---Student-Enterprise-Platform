<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

try {
    $kernel->call('migrate', ['--force' => true]);
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
