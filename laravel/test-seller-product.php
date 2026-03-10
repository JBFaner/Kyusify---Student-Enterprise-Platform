<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::firstOrCreate(
    ['email' => 'seller-demo@example.com'],
    [
        'name' => 'Demo Seller',
        'password' => bcrypt('password'),
        'role' => 'seller'
    ]
);

$enterprise = \App\Models\Enterprise::firstOrCreate(
    ['user_id' => $user->id],
    [
        'name' => 'Demo Enterprise',
        'description' => 'A test store',
        'contact_email' => 'contact@demoenterprise.com',
        'contact_phone' => '1234567890'
    ]
);

$category = \App\Models\Category::firstOrCreate(
    ['slug' => 'demo-category'],
    [
        'name' => 'Demo Category',
        'description' => 'A demo category',
        'status' => true
    ]
);

$product = $enterprise->products()->firstOrCreate(
    ['name' => 'Demo Product v2'],
    [
        'description' => 'A demo product v2 test',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'status' => 'pending'
    ]
);

echo "Seller created: " . $user->email . "\n";
echo "Enterprise created: " . $enterprise->name . "\n";
echo "Product created: " . $product->name . " with status: " . $product->status . "\n";
