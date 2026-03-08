<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$enterprise = App\Models\Enterprise::where('status', 'approved')->first() ?? App\Models\Enterprise::first();
$user = App\Models\User::where('role', 'student')->first() ?? App\Models\User::first();
$product = App\Models\Product::where('enterprise_id', $enterprise->id)->first();

if ($enterprise && $user && $product) {
    if ($enterprise->orders()->count() == 0) {
        $order = App\Models\Order::create([
            'user_id' => $user->id,
            'enterprise_id' => $enterprise->id,
            'total_amount' => $product->price * 2,
            'status' => 'pending',
            'payment_method' => 'cash'
        ]);
        
        App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
            'subtotal' => $product->price * 2
        ]);
        
        echo "Order created successfully.";
    } else {
        echo "Orders already exist.";
    }
} else {
    echo "Missing required data to create test order. enterprise: " . !!$enterprise . " user: " . !!$user . " product: " . !!$product;
}

