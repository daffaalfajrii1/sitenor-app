<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')
            ->where('name', 'kepala cabor')
            ->update(['name' => 'admin cabor']);
    }

    public function down(): void
    {
        DB::table('roles')
            ->where('name', 'admin cabor')
            ->update(['name' => 'kepala cabor']);
    }
};
