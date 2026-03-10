<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('category');
            }
            if (!Schema::hasColumn('products', 'featured_order')) {
                $table->integer('featured_order')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['pending', 'approved', 'hidden', 'rejected'])->default('approved')->after('featured_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'featured_order', 'status']);
        });
    }
};
