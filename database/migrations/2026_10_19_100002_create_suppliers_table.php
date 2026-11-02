<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->string('code', 64)->unique();
            $table->string('name', 255);
            $table->string('phone', 32);
            $table->string('email', 255);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
