<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            // restrictOnDelete: a category or supplier that still has items cannot be deleted
            // (the database throws a QueryException that the controller catches).
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('sku', 64)->unique();
            $table->string('name', 255);
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('reorder_level')->default(0);
            $table->decimal('price', 10, 2)->default(0);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
