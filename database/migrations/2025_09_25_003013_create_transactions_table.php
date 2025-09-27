<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained(table: 'categories')->noActionOnDelete();
            $table->foreignId('color_id');
            $table->enum('status', ['pending', 'progress', 'finish', 'done'])->default('pending');
            $table->string("buyer_name");
            $table->bigInteger("buyer_phone");
            $table->integer("product_amount");
            $table->integer("product_count");
            $table->integer("acrylic_mm");
            $table->string("notes");
            $table->date("order_date");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
