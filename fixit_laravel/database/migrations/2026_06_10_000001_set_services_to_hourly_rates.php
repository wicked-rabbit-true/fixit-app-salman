<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->update(['duration_unit' => 'hours']);
    }

    public function down(): void
    {
        // No rollback — hourly pricing is the platform default.
    }
};
