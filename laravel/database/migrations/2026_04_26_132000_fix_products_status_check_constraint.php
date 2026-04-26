<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Drop the stale `products_status_check` constraint (from the old
     * enum('active','pending','suspended') column) and add a new check
     * constraint that matches the allowed values used by the application.
     *
     * Background: when the column was changed from enum → string via Doctrine
     * ->change(), PostgreSQL kept the original check constraint intact.
     * This migration removes it and replaces it with the correct one.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE products DROP CONSTRAINT IF EXISTS products_status_check');

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT products_status_check
            CHECK (status IN ('pending', 'approved', 'hidden', 'rejected'))
        ");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products DROP CONSTRAINT IF EXISTS products_status_check');

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT products_status_check
            CHECK (status IN ('active', 'pending', 'suspended'))
        ");
    }
};
