<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->default('STAFF');
        });

        $now = now();

        // Sample accounts for development. Change these passwords before the demo.
        DB::table('users')->insert([
            ['created_at' => $now, 'updated_at' => $now, 'name' => 'Admin', 'email' => 'admin@stock.com', 'password' => Hash::make('password'), 'role' => 'ADMIN'],
            ['created_at' => $now, 'updated_at' => $now, 'name' => 'Staff', 'email' => 'staff@stock.com', 'password' => Hash::make('password'), 'role' => 'STAFF'],
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
