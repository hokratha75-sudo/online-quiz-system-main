<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Auto-populate first_name and last_name from username if they are null
        DB::table('users')->whereNull('first_name')->orWhere('first_name', '')->update([
            'first_name' => DB::raw('username')
        ]);
        
        DB::table('users')->whereNull('last_name')->orWhere('last_name', '')->update([
            'last_name' => 'User' // Default fallback since we only have one username field
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed as we're just populating existing nulls
    }
};
