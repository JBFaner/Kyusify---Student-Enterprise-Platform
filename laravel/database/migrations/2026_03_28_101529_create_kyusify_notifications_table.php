<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyusify_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');         // e.g. 'new_order', 'order_status', 'new_review', 'new_inquiry', 'product_approved'
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->string('icon')->default('bell'); // bell, order, review, inquiry, product
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyusify_notifications');
    }
};
