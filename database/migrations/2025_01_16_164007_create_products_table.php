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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('weight', 10, 2);
            $table->decimal('price_after_discount', 10, 2)->nullable();
            $table->decimal('quantity',10, 2)->default(0);
            $table->string('image')->nullable();
            $table->string('sku')->nullable();
            $table->string('uom');
            $table->boolean('status')->default(true);
            $table->boolean('is_subscription')->default(false);
            $table->boolean('is_discount')->default(false);
            $table->index('created_by');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
