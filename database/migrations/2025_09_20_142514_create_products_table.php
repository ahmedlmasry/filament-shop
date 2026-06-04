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
            $table->string('slug');            
            $table->string('small_desc');
            $table->longText('desc');
            $table->boolean('status')->default(1);
            $table->string('sku')->nullable();
            $table->date('available_for')->nullable();
            $table->integer('views')->default(0);

            $table->decimal('price', 8 ,2)->nullable();

            $table->boolean('has_discount')->default(0);
            $table->decimal('discount')->nullable();
            $table->date('start_discount')->nullable();
            $table->date('end_discount')->nullable();

            $table->boolean('has_variants')->default(0);

            $table->boolean('manage_stock')->default(0);
            $table->integer('stock')->nullable();
            $table->integer('available_in_stock')->default(1);

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');

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
