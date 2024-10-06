<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id'); // Sử dụng unsignedInteger
            $table->unsignedBigInteger('product_id'); // Giữ nguyên unsignedBigInteger
            $table->integer('quantity');
            $table->integer('price');
            $table->timestamps();

            // Định nghĩa khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
