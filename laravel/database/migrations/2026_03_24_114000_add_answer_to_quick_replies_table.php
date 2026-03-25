<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quick_replies', function (Blueprint $table) {
            // Rename existing 'message' to 'question'
            $table->renameColumn('message', 'question');
            // Add 'answer' column
            $table->text('answer')->after('question');
        });
    }

    public function down(): void
    {
        Schema::table('quick_replies', function (Blueprint $table) {
            $table->renameColumn('question', 'message');
            $table->dropColumn('answer');
        });
    }
};
