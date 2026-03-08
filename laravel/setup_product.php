<?php
$enterprise = \App\Models\Enterprise::first();
if ($enterprise) {
    \App\Models\Product::firstOrCreate(
        ['name' => 'QCU Logo T-Shirt', 'enterprise_id' => $enterprise->id],
        ['description' => 'Official QCU merchandise t-shirt, 100% cotton.', 'price' => 250.00, 'stock' => 50, 'status' => 'pending']
    );
}
echo "Product created.\n";
